<?php

namespace App\Http\Middleware;

use App\Services\AnalyticsService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackAnalytics
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->is('admin/*') || $request->is('api/*') || $request->ajax()) {
            return $response;
        }

        $pageKey = $this->resolvePageKey($request);
        if ($pageKey) {
            app(AnalyticsService::class)->recordPageView($pageKey, $request);
        } else {
            app(AnalyticsService::class)->touchPresence($request);
        }

        return $response;
    }

    protected function resolvePageKey(Request $request): ?string
    {
        $name = $request->route()?->getName();

        return match ($name) {
            'home' => 'home',
            'contact.show' => 'contact',
            'legal' => 'legal',
            'cart.index' => 'cart',
            'checkout', 'checkout.index' => 'checkout',
            'checkout.receipt' => 'receipt',
            'product.show' => 'product',
            'product.slug' => 'product',
            'product.List' => 'product_list',
            'guides.index' => 'guides',
            'guides.download' => 'guide_download',
            'account.dashboard' => 'account',
            'account.orders' => 'account_orders',
            'account.profile' => 'account',
            default => null,
        };
    }
}
