<?php

namespace App\Services;

use App\Models\Category;
use App\Models\products;
use App\Models\SeoPage;
use App\Models\SiteSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SeoService
{
    protected array $context = [];

    protected ?array $resolved = null;

    public function setContext(array $context): self
    {
        $this->context = $context;
        $this->resolved = null;

        return $this;
    }

    public function forPage(string $pageKey): self
    {
        return $this->setContext(['page_key' => $pageKey]);
    }

    public function forProduct(products $product): self
    {
        return $this->setContext(['product' => $product]);
    }

    public function forCategory(Category $category): self
    {
        return $this->setContext(['category' => $category]);
    }

    public function resolve(): array
    {
        if ($this->resolved !== null) {
            return $this->resolved;
        }

        $locale = app()->getLocale();
        $branding = app(BrandingService::class);
        $settings = $this->settings();
        $canonical = url()->current();

        $title = $this->field($settings, 'meta_title', $locale)
            ?: $branding->siteName().' — '.$branding->tagline();
        $description = $this->field($settings, 'meta_description', $locale) ?: $branding->tagline();
        $keywords = $this->field($settings, 'meta_keywords', $locale) ?: '';
        $ogTitle = $this->field($settings, 'og_title', $locale) ?: $title;
        $ogDescription = $this->field($settings, 'og_description', $locale) ?: $description;
        $ogImage = $branding->ogImageUrl();
        $robots = $settings->robots ?? config('seo.default_robots');
        $isIndexable = true;
        $jsonLd = [];

        if ($page = $this->context['page_key'] ?? null) {
            $seoPage = SeoPage::where('page_key', $page)->first();
            if ($seoPage) {
                $title = $this->entityField($seoPage, 'meta_title', $locale) ?: $title;
                $description = $this->entityField($seoPage, 'meta_description', $locale) ?: $description;
                $keywords = $this->entityField($seoPage, 'meta_keywords', $locale) ?: $keywords;
                if ($seoPage->og_image_path) {
                    $ogImage = $branding->assetUrl($seoPage->og_image_path);
                }
                $isIndexable = (bool) $seoPage->is_indexable;
            }
        }

        if ($product = $this->context['product'] ?? null) {
            $title = $this->entityField($product, 'meta_title', $locale) ?: $product->name.' — '.$branding->siteName();
            $description = $this->entityField($product, 'meta_description', $locale)
                ?: Str::limit(strip_tags($product->description ?? ''), 160);
            $keywords = $this->entityField($product, 'meta_keywords', $locale) ?: $keywords;
            if ($product->og_image_path) {
                $ogImage = $branding->assetUrl($product->og_image_path);
            } elseif ($product->productImages?->first()) {
                $ogImage = asset('storage/'.$product->productImages->first()->images);
            }
            $isIndexable = (bool) ($product->is_indexable ?? true);
            $canonical = $product->slug
                ? url('/p/'.$product->slug)
                : route('product.show', $product->id);
            $jsonLd[] = $this->productJsonLd($product, $branding, $canonical);
        }

        if (! empty($this->context['title'])) {
            $title = $this->context['title'];
        }
        if (! empty($this->context['description'])) {
            $description = $this->context['description'];
        }
        if (! empty($this->context['canonical'])) {
            $canonical = $this->context['canonical'];
        }
        if (! empty($this->context['og_image'])) {
            $ogImage = $this->context['og_image'];
        }
        if (isset($this->context['robots'])) {
            $robots = $this->context['robots'];
        }

        if (! $isIndexable) {
            $robots = 'noindex, nofollow';
        }

        if ($settings->canonical_url && ($this->context['page_key'] ?? null) === 'home') {
            $canonical = $settings->canonical_url;
        }

        $jsonLd[] = $this->organizationJsonLd($branding);

        return $this->resolved = [
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'og_title' => $ogTitle,
            'og_description' => $ogDescription,
            'og_image' => $ogImage,
            'twitter_card' => $settings->twitter_card ?? 'summary_large_image',
            'robots' => $robots,
            'canonical' => $canonical,
            'json_ld' => $jsonLd,
        ];
    }

    public function organizationJsonLd(BrandingService $branding): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $branding->siteName(),
            'url' => url('/'),
            'logo' => $branding->logoUrl(),
        ];
    }

    public function productJsonLd(products $product, BrandingService $branding, string $url): array
    {
        $price = $product->price - ($product->price * ($product->sale ?? 0) / 100);

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => Str::limit(strip_tags($product->description ?? ''), 300),
            'image' => $product->productImages?->first()
                ? asset('storage/'.$product->productImages->first()->images)
                : $branding->placeholderProductUrl(),
            'offers' => [
                '@type' => 'Offer',
                'priceCurrency' => 'EGP',
                'price' => number_format($price, 2, '.', ''),
                'availability' => 'https://schema.org/InStock',
                'url' => $url,
            ],
        ];
    }

    protected function settings(): SiteSetting
    {
        return SiteSetting::current();
    }

    protected function field(SiteSetting $settings, string $base, string $locale): ?string
    {
        $key = $locale === 'ar' ? "{$base}_ar" : "{$base}_en";

        return $settings->{$key} ?: null;
    }

    protected function entityField(Model $entity, string $base, string $locale): ?string
    {
        $key = $locale === 'ar' ? "{$base}_ar" : "{$base}_en";

        return $entity->{$key} ?: null;
    }
}
