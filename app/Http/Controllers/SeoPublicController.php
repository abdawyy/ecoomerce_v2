<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\SeoPage;
use App\Services\BrandingService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class SeoPublicController extends Controller
{
    public function sitemap(): Response
    {
        $xml = Cache::remember('sitemap.xml', config('seo.sitemap_cache_minutes', 60) * 60, function () {
            $urls = [];
            $urls[] = $this->urlEntry(url('/'), now(), 'daily', '1.0');

            if (Schema::hasTable('seo_pages')) {
                foreach (SeoPage::where('is_indexable', true)->get() as $page) {
                    $cfg = config('seo.static_pages.'.$page->page_key);
                    if ($cfg && isset($cfg['path'])) {
                        $urls[] = $this->urlEntry(url($cfg['path']), $page->updated_at, 'weekly', '0.8');
                    }
                }
            }

            if (Schema::hasColumn('products', 'is_indexable')) {
                $products = products::where('is_active', 1)->where('is_indexable', true)->get();
            } else {
                $products = products::where('is_active', 1)->get();
            }

            if (Schema::hasTable('guides')) {
                foreach (\App\Models\Guide::active()->get() as $guide) {
                    $urls[] = $this->urlEntry(route('guides.download', $guide->slug), $guide->updated_at, 'monthly', '0.6');
                }
            }

            foreach ($products as $product) {
                $loc = ($product->slug ?? null)
                    ? url('/p/'.$product->slug)
                    : route('product.show', $product->id);
                $urls[] = $this->urlEntry($loc, $product->updated_at ?? now(), 'weekly', '0.7');
            }

            $body = implode("\n", $urls);

            return '<?xml version="1.0" encoding="UTF-8"?>'."\n"
                .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n"
                .$body."\n</urlset>";
        });

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    public function robots(): Response
    {
        $branding = app(BrandingService::class);
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /cart',
            'Disallow: /checkout',
            'Sitemap: '.url('/sitemap.xml'),
        ];

        return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain']);
    }

    protected function urlEntry(string $loc, $lastmod, string $changefreq, string $priority): string
    {
        $lm = $lastmod ? (\is_string($lastmod) ? $lastmod : $lastmod->toAtomString()) : now()->toAtomString();

        return '  <url>'
            .'<loc>'.e($loc).'</loc>'
            .'<lastmod>'.$lm.'</lastmod>'
            .'<changefreq>'.$changefreq.'</changefreq>'
            .'<priority>'.$priority.'</priority>'
            .'</url>';
    }
}
