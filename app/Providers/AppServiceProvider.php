<?php

namespace App\Providers;

use App\Services\BrandingService;
use App\Services\SeoService;
use App\Models\Category;
use App\Models\Cities;
use App\Models\products;
use App\Models\shoppingCart;
use App\Models\Type;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $cartCount;

    public function register(): void
    {
        $this->app->singleton(BrandingService::class);
        $this->app->singleton(SeoService::class);
        $this->app->singleton(\App\Services\PdfService::class);
        $this->app->singleton(\App\Services\AdminNotificationService::class);
    }

    public function boot(): void
    {
        View::share('branding', app(BrandingService::class));

        $shareNavData = function ($view) {
            $categories = Category::where('is_active', 1)->get();
            $types = Type::where('is_active', 1)->get();
            $cities = Cities::where('is_active', 1)->get();

            if (Auth::check()) {
                $cartCount = shoppingCart::where('user_id', Auth::id())->count();
            } else {
                $cartCount = count(Session::get('cart', []));
            }

            $view->with(compact('categories', 'types', 'cartCount', 'cities'));
            Lang::addNamespace('web', resource_path('lang/'.app()->getLocale().'/web'));
        };

        View::composer([
            'components.web.*',
            'index',
            'cart.*',
            'checkout.*',
            'product.*',
            'contact_us',
            'legal',
            'guides.*',
            'account.*',
            'components.account.*',
        ], $shareNavData);

        View::composer('index', function ($view) use ($shareNavData) {
            $shareNavData($view);

            $products = products::with(['productImages', 'category', 'type'])
                ->where('is_active', 1)
                ->whereHas('category', fn ($q) => $q->where('is_active', 1))
                ->whereHas('type', fn ($q) => $q->where('is_active', 1))
                ->orderByDesc('is_highest')
                ->orderByDesc('name')
                ->get();

            $view->with('products', $products);
        });

        View::composer(['components.admin.aside', 'components.admin.navbar'], function ($view) {
            $notifications = app(\App\Services\AdminNotificationService::class);
            $view->with([
                'adminNotifications' => $notifications->counts(),
                'adminActivityFeed' => $notifications->activityFeed(8),
            ]);
        });

        View::composer('admin.*', function ($view) {
            $categories = Category::where('is_active', 1)->get();
            $types = Type::where('is_active', 1)->get();
            $view->with(compact('categories', 'types'));
        });

        $pageSeoMap = [
            'index' => 'home',
            'legal' => 'legal',
            'contact_us' => 'contact',
            'cart.index' => 'cart',
            'checkout.address' => 'checkout',
            'guides.index' => 'guides',
        ];

        View::composer(array_keys($pageSeoMap), function ($view) use ($pageSeoMap) {
            if ($view->offsetExists('seo')) {
                return;
            }
            $pageKey = $pageSeoMap[$view->name()] ?? 'home';
            $view->with('seo', app(SeoService::class)->forPage($pageKey)->resolve());
        });

        View::composer('product.show', function ($view) {
            if ($view->offsetExists('seo') || ! $view->offsetExists('product')) {
                return;
            }
            $view->with('seo', app(SeoService::class)->forProduct($view->product)->resolve());
        });
    }
}
