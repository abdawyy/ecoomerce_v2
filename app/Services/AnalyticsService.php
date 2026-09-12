<?php

namespace App\Services;

use App\Models\AnalyticsDaily;
use App\Models\CustomerEvent;
use App\Models\GuestUser;
use App\Models\orders;
use App\Models\PageView;
use App\Models\payments;
use App\Models\ProductView;
use App\Models\products;
use App\Models\User;
use App\Models\VisitorPresence;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AnalyticsService
{
    public const EVENT_ADD_TO_CART = 'add_to_cart';

    public const EVENT_CHECKOUT_START = 'checkout_start';

    public const EVENT_PURCHASE = 'purchase';

    public const EVENT_SEARCH = 'search';

    public const EVENT_REGISTER = 'register';

    public function sessionId(Request $request): string
    {
        if (! $request->session()->has('analytics_session_id')) {
            $request->session()->put('analytics_session_id', bin2hex(random_bytes(16)));
        }

        return $request->session()->get('analytics_session_id');
    }

    public function deviceFromUserAgent(?string $userAgent): string
    {
        $ua = strtolower((string) $userAgent);
        if ($ua === '') {
            return 'unknown';
        }
        if (preg_match('/ipad|tablet|playbook|silk/i', $ua)) {
            return 'tablet';
        }
        if (preg_match('/mobile|iphone|ipod|android.*mobile|windows phone/i', $ua)) {
            return 'mobile';
        }

        return 'desktop';
    }

    public function trafficSource(?string $referrer, Request $request): string
    {
        $utm = strtolower(trim((string) $request->query('utm_source', '')));
        if ($utm !== '') {
            return Str::limit(preg_replace('/[^a-z0-9._-]/', '', $utm) ?: 'campaign', 32, '');
        }

        $host = strtolower((string) parse_url((string) $referrer, PHP_URL_HOST));
        if ($host === '' || $host === strtolower((string) $request->getHost())) {
            return 'direct';
        }
        if (str_contains($host, 'google.') || str_contains($host, 'bing.') || str_contains($host, 'yahoo.') || str_contains($host, 'duckduckgo.')) {
            return 'search';
        }
        if (str_contains($host, 'facebook.') || str_contains($host, 'fb.') || str_contains($host, 'instagram.') || str_contains($host, 'tiktok.') || str_contains($host, 'twitter.') || str_contains($host, 'x.com') || str_contains($host, 'snapchat.') || str_contains($host, 'pinterest.') || str_contains($host, 't.me') || str_contains($host, 'linkedin.')) {
            return 'social';
        }

        return 'referral';
    }

    public function isBot(?string $userAgent): bool
    {
        return (bool) preg_match('/bot|crawl|spider|slurp|bingpreview|facebookexternalhit/i', (string) $userAgent);
    }

    /**
     * @param  array<string, mixed>  $properties
     */
    public function recordEvent(string $eventName, Request $request, array $properties = [], ?int $productId = null, ?int $orderId = null, ?int $guestId = null): void
    {
        if (! Schema::hasTable('customer_events')) {
            return;
        }

        try {
            $referrer = Str::limit($request->headers->get('referer', ''), 500);
            CustomerEvent::create([
                'event_name' => $eventName,
                'session_id' => $this->sessionId($request),
                'user_id' => auth()->id(),
                'guest_id' => $guestId,
                'product_id' => $productId,
                'order_id' => $orderId,
                'device' => $this->deviceFromUserAgent($request->userAgent()),
                'traffic_source' => $this->trafficSource($referrer, $request),
                'locale' => app()->getLocale(),
                'properties' => $properties ?: null,
                'occurred_at' => now(),
            ]);
        } catch (\Throwable) {
            // Collection must never block checkout or cart.
        }
    }

    public function recordSearch(Request $request, string $query): void
    {
        $term = Str::limit(trim($query), 80, '');
        if ($term === '' || mb_strlen($term) < 2) {
            return;
        }

        if (! Schema::hasTable('customer_events')) {
            return;
        }

        $sessionId = $this->sessionId($request);
        $recent = CustomerEvent::query()
            ->where('event_name', self::EVENT_SEARCH)
            ->where('session_id', $sessionId)
            ->where('occurred_at', '>=', now()->subMinutes(10))
            ->latest('occurred_at')
            ->first();

        if ($recent && ($recent->properties['q'] ?? null) === $term) {
            return;
        }

        $this->recordEvent(self::EVENT_SEARCH, $request, ['q' => $term]);
    }

    public function recordProductView(products $product, Request $request): void
    {
        if (! Schema::hasTable('product_views')) {
            return;
        }

        if ($this->isBot($request->userAgent())) {
            return;
        }

        $sessionId = $this->sessionId($request);
        $throttle = now()->subMinutes(config('analytics.product_view_throttle_minutes', 30));

        $exists = ProductView::where('product_id', $product->id)
            ->where('session_id', $sessionId)
            ->where('viewed_at', '>=', $throttle)
            ->exists();

        if ($exists) {
            return;
        }

        ProductView::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'session_id' => $sessionId,
            'ip_hash' => hash('sha256', $request->ip().config('app.key')),
            'user_agent' => Str::limit($request->userAgent() ?? '', 500),
            'referrer' => Str::limit($request->headers->get('referer', ''), 500),
            'locale' => app()->getLocale(),
            'viewed_at' => now(),
        ]);
    }

    public function recordPageView(string $pageKey, Request $request, ?int $productId = null): void
    {
        if (! Schema::hasTable('page_views')) {
            return;
        }

        if ($this->isBot($request->userAgent())) {
            $this->touchPresence($request, $pageKey, $productId);

            return;
        }

        $referrer = Str::limit($request->headers->get('referer', ''), 500);
        $payload = [
            'page_key' => $pageKey,
            'path' => $request->path(),
            'user_id' => auth()->id(),
            'session_id' => $this->sessionId($request),
            'ip_hash' => hash('sha256', $request->ip().config('app.key')),
            'locale' => app()->getLocale(),
            'viewed_at' => now(),
        ];

        if (Schema::hasColumn('page_views', 'referrer')) {
            $payload['referrer'] = $referrer;
        }
        if (Schema::hasColumn('page_views', 'device')) {
            $payload['device'] = $this->deviceFromUserAgent($request->userAgent());
        }
        if (Schema::hasColumn('page_views', 'traffic_source')) {
            $payload['traffic_source'] = $this->trafficSource($referrer, $request);
        }

        PageView::create($payload);

        $this->touchPresence($request, $pageKey, $productId);
    }

    public function touchPresence(Request $request, ?string $pageKey = null, ?int $productId = null): void
    {
        if (! Schema::hasTable('visitor_presence')) {
            return;
        }

        $sessionId = $this->sessionId($request);
        $presence = VisitorPresence::firstOrNew(['session_id' => $sessionId]);
        if (! $presence->exists) {
            $presence->first_seen_at = now();
        }
        $presence->fill([
            'user_id' => auth()->id(),
            'current_path' => '/'.$request->path(),
            'current_page_key' => $pageKey,
            'product_id' => $productId,
            'locale' => app()->getLocale(),
            'last_seen_at' => now(),
        ]);
        $presence->save();
    }

    public function liveVisitorCount(): int
    {
        if (! Schema::hasTable('visitor_presence')) {
            return 0;
        }

        $cutoff = now()->subMinutes(config('analytics.presence_timeout_minutes', 5));

        return VisitorPresence::where('last_seen_at', '>=', $cutoff)->count();
    }

    public function liveVisitors(int $limit = 20)
    {
        if (! Schema::hasTable('visitor_presence')) {
            return collect();
        }

        $cutoff = now()->subMinutes(config('analytics.presence_timeout_minutes', 5));

        return VisitorPresence::with(['user', 'product'])
            ->where('last_seen_at', '>=', $cutoff)
            ->orderByDesc('last_seen_at')
            ->limit($limit)
            ->get();
    }

    public function parseDateRange(?string $preset, ?string $from, ?string $to): array
    {
        $end = Carbon::today()->endOfDay();

        $start = match ($preset) {
            'today' => Carbon::today()->startOfDay(),
            'yesterday' => Carbon::yesterday()->startOfDay(),
            'last_7' => Carbon::today()->subDays(6)->startOfDay(),
            'last_30' => Carbon::today()->subDays(29)->startOfDay(),
            'this_month' => Carbon::today()->startOfMonth(),
            'last_month' => Carbon::today()->subMonth()->startOfMonth(),
            'this_year' => Carbon::today()->startOfYear(),
            'last_year' => Carbon::today()->subYear()->startOfYear(),
            'all' => Carbon::create(2000, 1, 1),
            'custom' => $from ? Carbon::parse($from)->startOfDay() : Carbon::today()->subDays(29)->startOfDay(),
            default => Carbon::today()->subDays(29)->startOfDay(),
        };

        if ($preset === 'yesterday') {
            $end = Carbon::yesterday()->endOfDay();
        } elseif ($preset === 'last_month') {
            $end = Carbon::today()->subMonth()->endOfMonth();
        } elseif ($preset === 'last_year') {
            $end = Carbon::today()->subYear()->endOfYear();
        } elseif ($preset === 'custom' && $to) {
            $end = Carbon::parse($to)->endOfDay();
        }

        return [$start, $end];
    }

    public function topProducts(Carbon $start, Carbon $end, int $limit = 10)
    {
        if (! Schema::hasTable('product_views')) {
            return collect();
        }

        return ProductView::query()
            ->select('product_id', DB::raw('COUNT(*) as views'), DB::raw('COUNT(DISTINCT session_id) as uniques'))
            ->whereBetween('viewed_at', [$start, $end])
            ->groupBy('product_id')
            ->orderByDesc('views')
            ->limit($limit)
            ->get()
            ->map(function ($row) {
                $row->product = products::find($row->product_id);

                return $row;
            });
    }

    public function salesSummary(Carbon $start, Carbon $end): array
    {
        $orders = orders::whereBetween('created_at', [$start, $end]);
        $completed = (clone $orders)->whereIn('status', ['Completed', 'completed']);
        $count = $orders->count();
        $completedCount = $completed->count();
        $revenue = (float) $completed->sum('total_amount');

        return [
            'orders' => $count,
            'completed_orders' => $completedCount,
            'revenue' => $revenue,
            'aov' => $completedCount > 0 ? round($revenue / $completedCount, 2) : 0,
        ];
    }

    public function pageViewsCount(Carbon $start, Carbon $end): int
    {
        if (! Schema::hasTable('page_views')) {
            return 0;
        }

        return PageView::whereBetween('viewed_at', [$start, $end])->count();
    }

    public function uniqueVisitors(Carbon $start, Carbon $end): int
    {
        if (! Schema::hasTable('page_views')) {
            return 0;
        }

        return PageView::whereBetween('viewed_at', [$start, $end])->distinct('session_id')->count('session_id');
    }

    public function productViewsCount(Carbon $start, Carbon $end): int
    {
        if (! Schema::hasTable('product_views')) {
            return 0;
        }

        return ProductView::whereBetween('viewed_at', [$start, $end])->count();
    }

    public function aggregateDaily(Carbon $date): void
    {
        if (! Schema::hasTable('analytics_daily')) {
            return;
        }

        $start = $date->copy()->startOfDay();
        $end = $date->copy()->endOfDay();

        if (Schema::hasTable('product_views')) {
            $rows = ProductView::query()
                ->select('product_id', DB::raw('COUNT(*) as views'), DB::raw('COUNT(DISTINCT session_id) as uniques'))
                ->whereBetween('viewed_at', [$start, $end])
                ->groupBy('product_id')
                ->get();

            foreach ($rows as $row) {
                AnalyticsDaily::updateOrCreate(
                    ['date' => $date->toDateString(), 'product_id' => $row->product_id, 'page_key' => null],
                    ['views' => $row->views, 'unique_visitors' => $row->uniques]
                );
            }
        }

        $sales = orders::whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['Completed', 'completed'])
            ->selectRaw('COUNT(*) as cnt, SUM(total_amount) as rev')
            ->first();

        AnalyticsDaily::updateOrCreate(
            ['date' => $date->toDateString(), 'product_id' => null, 'page_key' => 'sales'],
            ['orders_count' => (int) ($sales->cnt ?? 0), 'revenue' => (float) ($sales->rev ?? 0)]
        );
    }

    public function previousPeriod(Carbon $start, Carbon $end): array
    {
        $days = max(1, $start->copy()->startOfDay()->diffInDays($end->copy()->startOfDay()) + 1);
        $prevEnd = $start->copy()->subDay()->endOfDay();
        $prevStart = $prevEnd->copy()->subDays($days - 1)->startOfDay();

        return [$prevStart, $prevEnd];
    }

    public function percentChange(float|int $current, float|int $previous): ?float
    {
        if ($previous == 0) {
            return $current > 0 ? 100.0 : 0.0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }

    public function dashboardKpis(Carbon $start, Carbon $end): array
    {
        [$prevStart, $prevEnd] = $this->previousPeriod($start, $end);

        $current = [
            'page_views' => $this->pageViewsCount($start, $end),
            'product_views' => $this->productViewsCount($start, $end),
            'unique_visitors' => $this->uniqueVisitors($start, $end),
            'sales' => $this->salesSummary($start, $end),
        ];

        $previous = [
            'page_views' => $this->pageViewsCount($prevStart, $prevEnd),
            'product_views' => $this->productViewsCount($prevStart, $prevEnd),
            'unique_visitors' => $this->uniqueVisitors($prevStart, $prevEnd),
            'sales' => $this->salesSummary($prevStart, $prevEnd),
        ];

        return [
            'current' => $current,
            'previous' => $previous,
            'change' => [
                'page_views' => $this->percentChange($current['page_views'], $previous['page_views']),
                'product_views' => $this->percentChange($current['product_views'], $previous['product_views']),
                'unique_visitors' => $this->percentChange($current['unique_visitors'], $previous['unique_visitors']),
                'revenue' => $this->percentChange($current['sales']['revenue'], $previous['sales']['revenue']),
                'orders' => $this->percentChange($current['sales']['orders'], $previous['sales']['orders']),
            ],
        ];
    }

    public function viewsTrend(Carbon $start, Carbon $end): array
    {
        $labels = [];
        $pageViews = [];
        $productViews = [];
        $orders = [];

        $cursor = $start->copy()->startOfDay();
        while ($cursor <= $end) {
            $dayStart = $cursor->copy()->startOfDay();
            $dayEnd = $cursor->copy()->endOfDay();
            $labels[] = $cursor->format('M j');
            $pageViews[] = Schema::hasTable('page_views')
                ? PageView::whereBetween('viewed_at', [$dayStart, $dayEnd])->count() : 0;
            $productViews[] = Schema::hasTable('product_views')
                ? ProductView::whereBetween('viewed_at', [$dayStart, $dayEnd])->count() : 0;
            $orders[] = orders::whereBetween('created_at', [$dayStart, $dayEnd])->count();
            $cursor->addDay();
        }

        return compact('labels', 'pageViews', 'productViews', 'orders');
    }

    public function revenueTrend(Carbon $start, Carbon $end): array
    {
        $labels = [];
        $revenue = [];
        $cursor = $start->copy()->startOfDay();
        while ($cursor <= $end) {
            $dayStart = $cursor->copy()->startOfDay();
            $dayEnd = $cursor->copy()->endOfDay();
            $labels[] = $cursor->format('M j');
            $revenue[] = (float) orders::whereBetween('created_at', [$dayStart, $dayEnd])
                ->whereIn('status', ['Completed', 'completed'])
                ->sum('total_amount');
            $cursor->addDay();
        }

        return compact('labels', 'revenue');
    }

    public function topPages(Carbon $start, Carbon $end, int $limit = 10)
    {
        if (! Schema::hasTable('page_views')) {
            return collect();
        }

        return PageView::query()
            ->select('page_key', DB::raw('COUNT(*) as views'), DB::raw('COUNT(DISTINCT session_id) as uniques'))
            ->whereBetween('viewed_at', [$start, $end])
            ->groupBy('page_key')
            ->orderByDesc('views')
            ->limit($limit)
            ->get();
    }

    public function categoryPerformance(Carbon $start, Carbon $end, int $limit = 10)
    {
        if (! Schema::hasTable('product_views') || ! Schema::hasTable('products')) {
            return collect();
        }

        return ProductView::query()
            ->join('products', 'products.id', '=', 'product_views.product_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->select(
                'categories.id as category_id',
                'categories.name as category_name',
                DB::raw('COUNT(*) as views'),
                DB::raw('COUNT(DISTINCT product_views.session_id) as uniques')
            )
            ->whereBetween('product_views.viewed_at', [$start, $end])
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('views')
            ->limit($limit)
            ->get();
    }

    public function salesByCity(Carbon $start, Carbon $end, int $limit = 10)
    {
        return orders::query()
            ->join('cities', 'cities.id', '=', 'orders.city_id')
            ->select(
                'cities.name as city_name',
                DB::raw('COUNT(*) as orders_count'),
                DB::raw('SUM(CASE WHEN orders.status IN ("Completed","completed") THEN orders.total_amount ELSE 0 END) as revenue')
            )
            ->whereBetween('orders.created_at', [$start, $end])
            ->groupBy('cities.id', 'cities.name')
            ->orderByDesc('revenue')
            ->limit($limit)
            ->get();
    }

    public function trafficByHour(Carbon $start, Carbon $end): array
    {
        $hours = array_fill(0, 24, 0);
        if (! Schema::hasTable('page_views')) {
            return $hours;
        }

        $rows = PageView::query()
            ->select(DB::raw('HOUR(viewed_at) as hr'), DB::raw('COUNT(*) as cnt'))
            ->whereBetween('viewed_at', [$start, $end])
            ->groupBy('hr')
            ->get();

        foreach ($rows as $row) {
            $hours[(int) $row->hr] = (int) $row->cnt;
        }

        return $hours;
    }

    public function localeBreakdown(Carbon $start, Carbon $end)
    {
        if (! Schema::hasTable('page_views')) {
            return collect();
        }

        return PageView::query()
            ->select('locale', DB::raw('COUNT(*) as views'))
            ->whereBetween('viewed_at', [$start, $end])
            ->groupBy('locale')
            ->orderByDesc('views')
            ->get();
    }

    public function funnelSummary(Carbon $start, Carbon $end): array
    {
        if (! Schema::hasTable('page_views')) {
            return [];
        }

        $keys = ['home', 'product_list', 'product', 'cart', 'checkout'];
        $counts = PageView::query()
            ->select('page_key', DB::raw('COUNT(DISTINCT session_id) as sessions'))
            ->whereBetween('viewed_at', [$start, $end])
            ->whereIn('page_key', $keys)
            ->groupBy('page_key')
            ->pluck('sessions', 'page_key');

        return collect($keys)->mapWithKeys(fn ($k) => [$k => (int) ($counts[$k] ?? 0)])->all();
    }

    public function windowShoppers(Carbon $start, Carbon $end, int $limit = 10)
    {
        if (! Schema::hasTable('product_views')) {
            return collect();
        }

        $viewed = ProductView::query()
            ->select('product_id', DB::raw('COUNT(*) as views'))
            ->whereBetween('viewed_at', [$start, $end])
            ->groupBy('product_id')
            ->orderByDesc('views')
            ->limit(50)
            ->get()
            ->keyBy('product_id');

        if ($viewed->isEmpty()) {
            return collect();
        }

        $sold = orders::query()
            ->join('order_items', 'order_items.orders_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->whereIn('orders.status', ['Completed', 'completed'])
            ->whereIn('order_items.products_id', $viewed->keys())
            ->select('order_items.products_id', DB::raw('SUM(order_items.quantity) as qty'))
            ->groupBy('order_items.products_id')
            ->pluck('qty', 'products_id');

        return $viewed->map(function ($row) use ($sold) {
            $row->orders_qty = (int) ($sold[$row->product_id] ?? 0);
            $row->product = products::find($row->product_id);

            return $row;
        })
            ->filter(fn ($r) => $r->views >= 5 && $r->orders_qty <= 1)
            ->sortByDesc('views')
            ->take($limit)
            ->values();
    }

    public function eventCount(string $eventName, Carbon $start, Carbon $end): int
    {
        if (! Schema::hasTable('customer_events')) {
            return 0;
        }

        return CustomerEvent::query()
            ->where('event_name', $eventName)
            ->whereBetween('occurred_at', [$start, $end])
            ->count();
    }

    public function customerOverview(Carbon $start, Carbon $end): array
    {
        $ordersInRange = orders::query()->whereBetween('created_at', [$start, $end]);
        $completed = (clone $ordersInRange)->whereIn('status', ['Completed', 'completed']);

        $guestOrders = (clone $ordersInRange)->whereNull('user_id')->count();
        $registeredOrders = (clone $ordersInRange)->whereNotNull('user_id')->count();

        $buyers = orders::query()
            ->whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['Completed', 'completed'])
            ->selectRaw('COALESCE(user_id, CONCAT("g-", guest_id)) as buyer_key, COUNT(*) as order_count, SUM(total_amount) as revenue')
            ->groupBy('buyer_key')
            ->get();

        $firstOrders = orders::query()
            ->whereIn('status', ['Completed', 'completed'])
            ->selectRaw('COALESCE(user_id, CONCAT("g-", guest_id)) as buyer_key, MIN(created_at) as first_at')
            ->groupBy('buyer_key')
            ->pluck('first_at', 'buyer_key');

        $newCustomers = 0;
        $returningCustomers = 0;
        foreach ($buyers as $buyer) {
            $firstAt = $firstOrders[$buyer->buyer_key] ?? $start;
            if (Carbon::parse($firstAt)->lt($start)) {
                $returningCustomers++;
            } else {
                $newCustomers++;
            }
        }

        $repeatBuyers = $buyers->filter(fn ($b) => (int) $b->order_count >= 2)->count();
        $uniqueBuyers = $buyers->count();

        $newAccounts = Schema::hasTable('users')
            ? User::whereBetween('created_at', [$start, $end])->count()
            : 0;
        $newGuests = Schema::hasTable('guest_users')
            ? GuestUser::whereBetween('created_at', [$start, $end])->count()
            : 0;

        $addToCart = $this->eventCount(self::EVENT_ADD_TO_CART, $start, $end);
        $checkoutStarts = $this->eventCount(self::EVENT_CHECKOUT_START, $start, $end);
        $purchases = $this->eventCount(self::EVENT_PURCHASE, $start, $end);
        $searches = $this->eventCount(self::EVENT_SEARCH, $start, $end);
        $registers = $this->eventCount(self::EVENT_REGISTER, $start, $end);

        $productViewSessions = Schema::hasTable('product_views')
            ? ProductView::whereBetween('viewed_at', [$start, $end])->distinct('session_id')->count('session_id')
            : 0;
        $atcSessions = Schema::hasTable('customer_events')
            ? CustomerEvent::where('event_name', self::EVENT_ADD_TO_CART)->whereBetween('occurred_at', [$start, $end])->distinct('session_id')->count('session_id')
            : 0;

        return [
            'guest_orders' => $guestOrders,
            'registered_orders' => $registeredOrders,
            'new_customers' => $newCustomers,
            'returning_customers' => $returningCustomers,
            'unique_buyers' => $uniqueBuyers,
            'repeat_buyers' => $repeatBuyers,
            'repeat_rate' => $uniqueBuyers > 0 ? round(($repeatBuyers / $uniqueBuyers) * 100, 1) : 0,
            'new_accounts' => $newAccounts,
            'new_guests' => $newGuests,
            'add_to_cart' => $addToCart,
            'checkout_starts' => $checkoutStarts,
            'purchases' => $purchases ?: (int) $completed->count(),
            'searches' => $searches,
            'registers' => $registers ?: $newAccounts,
            'atc_rate' => $productViewSessions > 0 ? round(($atcSessions / $productViewSessions) * 100, 1) : 0,
            'checkout_rate' => $atcSessions > 0 ? round(($checkoutStarts / $atcSessions) * 100, 1) : 0,
            'purchase_rate' => $checkoutStarts > 0 ? round((($purchases ?: (int) $completed->count()) / $checkoutStarts) * 100, 1) : 0,
        ];
    }

    public function deviceBreakdown(Carbon $start, Carbon $end)
    {
        if (Schema::hasTable('customer_events') && CustomerEvent::whereBetween('occurred_at', [$start, $end])->exists()) {
            return CustomerEvent::query()
                ->select('device', DB::raw('COUNT(*) as events'), DB::raw('COUNT(DISTINCT session_id) as sessions'))
                ->whereBetween('occurred_at', [$start, $end])
                ->whereNotNull('device')
                ->groupBy('device')
                ->orderByDesc('sessions')
                ->get();
        }

        if (! Schema::hasTable('page_views') || ! Schema::hasColumn('page_views', 'device')) {
            return collect();
        }

        return PageView::query()
            ->select('device', DB::raw('COUNT(*) as events'), DB::raw('COUNT(DISTINCT session_id) as sessions'))
            ->whereBetween('viewed_at', [$start, $end])
            ->whereNotNull('device')
            ->groupBy('device')
            ->orderByDesc('sessions')
            ->get();
    }

    public function trafficSourceBreakdown(Carbon $start, Carbon $end)
    {
        if (Schema::hasTable('customer_events') && CustomerEvent::whereBetween('occurred_at', [$start, $end])->exists()) {
            return CustomerEvent::query()
                ->select('traffic_source', DB::raw('COUNT(*) as events'), DB::raw('COUNT(DISTINCT session_id) as sessions'))
                ->whereBetween('occurred_at', [$start, $end])
                ->whereNotNull('traffic_source')
                ->groupBy('traffic_source')
                ->orderByDesc('sessions')
                ->get();
        }

        if (! Schema::hasTable('page_views') || ! Schema::hasColumn('page_views', 'traffic_source')) {
            return collect();
        }

        return PageView::query()
            ->select('traffic_source', DB::raw('COUNT(*) as events'), DB::raw('COUNT(DISTINCT session_id) as sessions'))
            ->whereBetween('viewed_at', [$start, $end])
            ->whereNotNull('traffic_source')
            ->groupBy('traffic_source')
            ->orderByDesc('sessions')
            ->get();
    }

    public function topSearchTerms(Carbon $start, Carbon $end, int $limit = 15)
    {
        if (! Schema::hasTable('customer_events')) {
            return collect();
        }

        $rows = CustomerEvent::query()
            ->where('event_name', self::EVENT_SEARCH)
            ->whereBetween('occurred_at', [$start, $end])
            ->get(['properties']);

        return $rows
            ->map(fn ($row) => strtolower(trim((string) ($row->properties['q'] ?? ''))))
            ->filter()
            ->countBy()
            ->sortDesc()
            ->take($limit)
            ->map(fn ($count, $term) => (object) ['term' => $term, 'searches' => $count])
            ->values();
    }

    public function topCustomers(Carbon $start, Carbon $end, int $limit = 15)
    {
        $rows = orders::query()
            ->whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['Completed', 'completed'])
            ->selectRaw('user_id, guest_id, COUNT(*) as orders_count, SUM(total_amount) as revenue, MAX(created_at) as last_order_at')
            ->groupBy('user_id', 'guest_id')
            ->orderByDesc('revenue')
            ->limit($limit)
            ->get();

        $userIds = $rows->pluck('user_id')->filter()->unique();
        $guestIds = $rows->pluck('guest_id')->filter()->unique();
        $users = $userIds->isNotEmpty() ? User::whereIn('id', $userIds)->get(['id', 'name'])->keyBy('id') : collect();
        $guests = $guestIds->isNotEmpty() && Schema::hasTable('guest_users')
            ? GuestUser::whereIn('id', $guestIds)->get(['id', 'name'])->keyBy('id')
            : collect();

        return $rows->map(function ($row) use ($users, $guests) {
            if ($row->user_id) {
                $row->customer_name = $users[$row->user_id]->name ?? ('#'.$row->user_id);
                $row->customer_type = 'registered';
            } else {
                $row->customer_name = $guests[$row->guest_id]->name ?? __('analytics.guest');
                $row->customer_type = 'guest';
            }

            return $row;
        });
    }

    public function salesByWeekday(Carbon $start, Carbon $end): array
    {
        $days = array_fill(0, 7, ['orders' => 0, 'revenue' => 0.0]);
        $rows = orders::query()
            ->whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['Completed', 'completed'])
            ->selectRaw('WEEKDAY(created_at) as wd, COUNT(*) as cnt, SUM(total_amount) as rev')
            ->groupBy('wd')
            ->get();

        foreach ($rows as $row) {
            $days[(int) $row->wd] = [
                'orders' => (int) $row->cnt,
                'revenue' => (float) $row->rev,
            ];
        }

        return $days;
    }

    public function paymentMethodBreakdown(Carbon $start, Carbon $end)
    {
        if (! Schema::hasTable('payments')) {
            return collect();
        }

        return payments::query()
            ->join('orders', 'orders.id', '=', 'payments.orders_id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->select('payments.payment_method', DB::raw('COUNT(*) as cnt'), DB::raw('SUM(payments.amount) as amount'))
            ->groupBy('payments.payment_method')
            ->orderByDesc('cnt')
            ->get();
    }

    public function customerExportRows(Carbon $start, Carbon $end): array
    {
        $overview = $this->customerOverview($start, $end);
        $rows = [
            ['metric', 'value'],
            ['guest_orders', $overview['guest_orders']],
            ['registered_orders', $overview['registered_orders']],
            ['new_customers', $overview['new_customers']],
            ['returning_customers', $overview['returning_customers']],
            ['unique_buyers', $overview['unique_buyers']],
            ['repeat_rate_pct', $overview['repeat_rate']],
            ['new_accounts', $overview['new_accounts']],
            ['add_to_cart', $overview['add_to_cart']],
            ['searches', $overview['searches']],
            ['atc_rate_pct', $overview['atc_rate']],
        ];

        $rows[] = ['customer', 'type', 'orders', 'revenue'];
        foreach ($this->topCustomers($start, $end, 200) as $c) {
            $rows[] = [$c->customer_name, $c->customer_type, $c->orders_count, $c->revenue];
        }

        return $rows;
    }
}
