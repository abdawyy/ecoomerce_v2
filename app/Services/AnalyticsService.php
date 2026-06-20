<?php

namespace App\Services;

use App\Models\AnalyticsDaily;
use App\Models\orders;
use App\Models\PageView;
use App\Models\ProductView;
use App\Models\products;
use App\Models\VisitorPresence;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AnalyticsService
{
    public function sessionId(Request $request): string
    {
        if (! $request->session()->has('analytics_session_id')) {
            $request->session()->put('analytics_session_id', bin2hex(random_bytes(16)));
        }

        return $request->session()->get('analytics_session_id');
    }

    public function recordProductView(products $product, Request $request): void
    {
        if (! Schema::hasTable('product_views')) {
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

        PageView::create([
            'page_key' => $pageKey,
            'path' => $request->path(),
            'user_id' => auth()->id(),
            'session_id' => $this->sessionId($request),
            'ip_hash' => hash('sha256', $request->ip().config('app.key')),
            'locale' => app()->getLocale(),
            'viewed_at' => now(),
        ]);

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
}
