<?php

namespace App\Services;

use App\Models\orders;
use App\Models\products;
use App\Models\ProductView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MetaPixelService
{
    public function pixelId(): string
    {
        return (string) config('meta.pixel_id', '');
    }

    public function capiEnabled(): bool
    {
        return $this->pixelId() !== '' && filled(config('meta.capi_access_token'));
    }

    public function newEventId(): string
    {
        return (string) Str::uuid();
    }

    public function productValue(products $product): float
    {
        $price = (float) ($product->price ?? 0);
        $sale = (float) ($product->sale ?? 0);

        return round($price - ($price * $sale / 100), 2);
    }

    public function customData(array $contents, float $value, ?string $eventId = null): array
    {
        $contents = array_values(array_filter($contents, fn ($row) => ($row['id'] ?? '') !== ''));
        $ids = array_values(array_unique(array_map(fn ($row) => (string) $row['id'], $contents)));
        $qty = array_sum(array_map(fn ($row) => (int) ($row['quantity'] ?? 1), $contents));

        return [
            'content_ids' => $ids,
            'content_type' => 'product',
            'currency' => 'EGP',
            'value' => round($value, 2),
            'contents' => $contents,
            'num_items' => max(1, $qty),
            'event_id' => $eventId ?: $this->newEventId(),
        ];
    }

    public function productCustomData(products $product, int $quantity = 1, ?string $eventId = null): array
    {
        $quantity = max(1, $quantity);
        $unit = $this->productValue($product);

        return $this->customData([
            [
                'id' => (string) $product->id,
                'quantity' => $quantity,
                'item_price' => $unit,
            ],
        ], $unit * $quantity, $eventId);
    }

    public function orderCustomData(orders $order, ?string $eventId = null): array
    {
        $contents = [];
        foreach ($order->orderItems ?? [] as $item) {
            $id = (string) ($item->products_id ?? '');
            if ($id === '') {
                continue;
            }
            $qty = max(1, (int) ($item->quantity ?? 1));
            $line = (float) ($item->price ?? 0);
            $contents[] = [
                'id' => $id,
                'quantity' => $qty,
                'item_price' => round($qty > 0 ? $line / $qty : $line, 2),
            ];
        }

        $value = round((float) ($order->total_amount ?? 0), 2);

        return $this->customData($contents, $value, $eventId);
    }

    public function sendCapi(string $eventName, array $customData, Request $request, ?string $eventSourceUrl = null, array $userExtras = []): void
    {
        if (! $this->capiEnabled()) {
            return;
        }

        $eventId = (string) ($customData['event_id'] ?? $this->newEventId());
        $data = $customData;
        unset($data['event_id']);

        $payload = [
            'data' => [[
                'event_name' => $eventName,
                'event_time' => time(),
                'event_id' => $eventId,
                'event_source_url' => $eventSourceUrl ?: $this->absoluteUrl($request->fullUrl()),
                'action_source' => 'website',
                'user_data' => $this->userData($request, $userExtras),
                'custom_data' => $data,
            ]],
        ];

        $testCode = config('meta.capi_test_event_code');
        if (filled($testCode)) {
            $payload['test_event_code'] = $testCode;
        }

        try {
            $url = sprintf(
                'https://graph.facebook.com/%s/%s/events',
                config('meta.api_version', 'v21.0'),
                $this->pixelId()
            );

            $response = Http::timeout((int) config('meta.timeout', 3))
                ->asJson()
                ->post($url, $payload + ['access_token' => config('meta.capi_access_token')]);

            if ($response->failed()) {
                report(new \RuntimeException('Meta CAPI failed: '.$response->status().' '.$response->body()));
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }

    public function persistOrderEventId(orders $order): string
    {
        if (filled($order->meta_event_id ?? null)) {
            return (string) $order->meta_event_id;
        }

        $eventId = $this->newEventId();
        if (Schema::hasColumn('orders', 'meta_event_id')) {
            $order->meta_event_id = $eventId;
            $order->save();
        }

        return $eventId;
    }

    public function shouldSendViewContentCapi(products $product, Request $request, AnalyticsService $analytics): bool
    {
        try {
            if ($analytics->isBot($request->userAgent())) {
                return false;
            }

            if (! Schema::hasTable('product_views')) {
                return true;
            }

            $sessionId = $analytics->sessionId($request);
            $throttle = now()->subMinutes(config('analytics.product_view_throttle_minutes', 30));

            return ! ProductView::where('product_id', $product->id)
                ->where('session_id', $sessionId)
                ->where('viewed_at', '>=', $throttle)
                ->exists();
        } catch (\Throwable $e) {
            report($e);

            return false;
        }
    }

    protected function userData(Request $request, array $extras = []): array
    {
        $data = [
            'client_ip_address' => $request->ip(),
            'client_user_agent' => Str::limit((string) $request->userAgent(), 512, ''),
        ];

        $fbp = $request->cookie('_fbp');
        $fbc = $request->cookie('_fbc');
        if (is_string($fbp) && $fbp !== '') {
            $data['fbp'] = $fbp;
        }
        if (is_string($fbc) && $fbc !== '') {
            $data['fbc'] = $fbc;
        } elseif ($request->query('fbclid')) {
            $data['fbc'] = 'fb.1.'.time().'.'.$request->query('fbclid');
        }

        $email = $extras['email'] ?? (auth()->user()?->email);
        $phone = $extras['phone'] ?? null;
        if (filled($email)) {
            $data['em'] = [$this->hash((string) $email)];
        }
        if (filled($phone)) {
            $data['ph'] = [$this->hashPhone((string) $phone)];
        }

        return array_filter($data, fn ($v) => $v !== null && $v !== '' && $v !== []);
    }

    protected function hash(string $value): string
    {
        return hash('sha256', strtolower(trim($value)));
    }

    protected function hashPhone(string $value): string
    {
        $digits = preg_replace('/\D+/', '', $value) ?? '';

        return hash('sha256', $digits);
    }

    protected function absoluteUrl(string $url): string
    {
        $app = rtrim((string) config('app.url'), '/');
        if ($app === '') {
            return $url;
        }
        $path = parse_url($url, PHP_URL_PATH) ?: '/';
        $query = parse_url($url, PHP_URL_QUERY);

        return $app.$path.($query ? '?'.$query : '');
    }
}
