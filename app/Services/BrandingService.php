<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;

class BrandingService
{
    protected ?SiteSetting $settings = null;

    public function settings(): SiteSetting
    {
        if ($this->settings === null) {
            $this->settings = Cache::remember('site_settings', 300, fn () => SiteSetting::current());
        }

        return $this->settings;
    }

    public function clearCache(): void
    {
        Cache::forget('site_settings');
        $this->settings = null;
    }

    public function siteName(): string
    {
        return $this->settings()->site_name
            ?: config('branding.defaults.site_name', config('app.name'));
    }

    public function tagline(): string
    {
        $locale = app()->getLocale();

        return $locale === 'ar'
            ? ($this->settings()->tagline_ar ?: config('branding.defaults.tagline_ar', ''))
            : ($this->settings()->tagline_en ?: config('branding.defaults.tagline_en', ''));
    }

    public function logoAlt(): string
    {
        $locale = app()->getLocale();

        return $locale === 'ar'
            ? ($this->settings()->logo_alt_ar ?: config('branding.defaults.logo_alt_ar', $this->siteName()))
            : ($this->settings()->logo_alt_en ?: config('branding.defaults.logo_alt_en', $this->siteName()));
    }

    public function primaryColor(): string
    {
        return $this->settings()->primary_color ?: config('branding.defaults.primary_color', '#0d6efd');
    }

    public function supportEmail(): ?string
    {
        return $this->settings()->support_email ?: config('branding.defaults.support_email');
    }

    public function supportPhone(): ?string
    {
        return $this->settings()->support_phone ?: config('branding.defaults.support_phone');
    }

    public function cartPolicyTitle(): string
    {
        $locale = app()->getLocale();
        $settings = $this->settings();

        $title = $locale === 'ar'
            ? ($settings->cart_policy_title_ar ?: $settings->cart_policy_title_en)
            : ($settings->cart_policy_title_en ?: $settings->cart_policy_title_ar);

        return trim((string) $title) !== ''
            ? trim((string) $title)
            : __('cart.policy_title_default');
    }

    /**
     * @return array<int, string>
     */
    public function cartPolicyParagraphs(): array
    {
        $locale = app()->getLocale();
        $settings = $this->settings();

        $body = $locale === 'ar'
            ? ($settings->cart_policy_body_ar ?: $settings->cart_policy_body_en)
            : ($settings->cart_policy_body_en ?: $settings->cart_policy_body_ar);

        $body = trim(strip_tags((string) $body));

        if ($body === '') {
            $body = __('cart.policy_body_default');
        }

        return array_values(array_filter(
            array_map('trim', preg_split("/\r\n|\n|\r/", $body) ?: []),
            fn ($line) => $line !== ''
        ));
    }

    public function hasCartPolicy(): bool
    {
        return count($this->cartPolicyParagraphs()) > 0;
    }

    /**
     * @return array<int, array{platform: string, url: string, label: string, icon: string}>
     */
    public function socialLinks(): array
    {
        $platforms = config('branding.social_platforms', []);
        $links = [];

        foreach ($platforms as $platform => $meta) {
            $raw = trim((string) ($this->settings()->{"{$platform}_url"} ?? config("branding.defaults.{$platform}_url") ?? ''));
            $hidden = (bool) ($this->settings()->{"{$platform}_hidden"} ?? false);

            if ($raw === '' || $hidden) {
                continue;
            }

            $url = $this->normalizeSocialUrl($raw, $platform);
            $labelKey = $meta['label_key'] ?? "branding.social_{$platform}";
            $label = __($labelKey);
            if ($label === $labelKey) {
                $label = ucfirst(str_replace('_', ' ', $platform));
            }

            $links[] = [
                'platform' => $platform,
                'url' => $url,
                'label' => $label,
                'icon' => $this->iconForSocialUrl($url, $meta['icon'] ?? 'bi-link-45deg'),
            ];
        }

        return $links;
    }

    public function normalizeSocialUrl(string $url, string $platform = ''): string
    {
        $url = trim($url);

        if ($url === '') {
            return '';
        }

        if ($platform === 'whatsapp') {
            if (preg_match('#^https?://#i', $url)) {
                return $url;
            }
            $digits = preg_replace('/\D+/', '', $url);

            return $digits !== '' ? "https://wa.me/{$digits}" : $url;
        }

        if (! preg_match('#^https?://#i', $url)) {
            return 'https://'.$url;
        }

        return $url;
    }

    public function iconForSocialUrl(string $url, string $fallback = 'bi-link-45deg'): string
    {
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        $host = preg_replace('/^www\./', '', $host) ?? $host;

        foreach (config('branding.social_platforms', []) as $meta) {
            foreach ($meta['hosts'] ?? [] as $pattern) {
                if ($host === $pattern || str_ends_with($host, '.'.$pattern) || str_contains($host, $pattern)) {
                    return $meta['icon'] ?? $fallback;
                }
            }
        }

        return $fallback;
    }

    public function logoUrl(bool $dark = false): string
    {
        $path = $dark
            ? ($this->settings()->logo_dark_path ?: $this->settings()->logo_path)
            : $this->settings()->logo_path;

        return $this->assetUrl($path ?: config('branding.defaults.logo_path'));
    }

    public function logoPath(bool $dark = false): ?string
    {
        $path = $dark
            ? ($this->settings()->logo_dark_path ?: $this->settings()->logo_path)
            : $this->settings()->logo_path;

        $path = $path ?: config('branding.defaults.logo_path');

        return $this->filesystemPath($path);
    }

    public function faviconUrl(): string
    {
        return $this->assetUrl(
            $this->settings()->favicon_path ?: config('branding.defaults.favicon_path', config('branding.defaults.logo_path'))
        );
    }

    public function ogImageUrl(): string
    {
        return $this->assetUrl(
            $this->settings()->og_image_path ?: config('branding.defaults.og_image_path')
        );
    }

    public function footerLogoUrl(): string
    {
        $path = $this->settings()->footer_logo_path ?: $this->settings()->logo_path;

        return $this->assetUrl($path ?: config('branding.defaults.logo_path'));
    }

    public function placeholderProductUrl(): string
    {
        return $this->assetUrl(
            $this->settings()->placeholder_product_path ?: config('branding.defaults.placeholder_product_path')
        );
    }

    public function heroImageUrl(): string
    {
        return $this->assetUrl(
            $this->settings()->hero_image_path ?: config('branding.defaults.hero_image_path')
        );
    }

    public function categoryImage1Url(): string
    {
        return $this->assetUrl(
            $this->settings()->category_image_1_path ?: config('branding.defaults.category_image_1_path')
        );
    }

    public function categoryImage2Url(): string
    {
        return $this->assetUrl(
            $this->settings()->category_image_2_path ?: config('branding.defaults.category_image_2_path')
        );
    }

    /**
     * Two homepage category tiles: name, URL, and image from Branding & Media.
     *
     * @return array<int, array{name: string, url: string, image: string}>
     */
    public function homeCategoryTiles(): array
    {
        $settings = $this->settings();

        return [
            $this->homeCategoryTile(
                $settings->home_category_1_id ?? null,
                $settings->home_tile_1_title_en ?? null,
                $settings->home_tile_1_title_ar ?? null,
                $this->categoryImage1Url(),
                __('web.top')
            ),
            $this->homeCategoryTile(
                $settings->home_category_2_id ?? null,
                $settings->home_tile_2_title_en ?? null,
                $settings->home_tile_2_title_ar ?? null,
                $this->categoryImage2Url(),
                __('web.Long Sleeve')
            ),
        ];
    }

    /**
     * @return array{name: string, url: string, image: string}
     */
    protected function homeCategoryTile(
        mixed $categoryId,
        ?string $titleEn,
        ?string $titleAr,
        string $image,
        string $fallbackName
    ): array {
        $category = $categoryId
            ? \App\Models\Category::query()->find($categoryId)
            : null;

        $locale = app()->getLocale();
        $custom = trim((string) ($locale === 'ar'
            ? ($titleAr ?: $titleEn)
            : ($titleEn ?: $titleAr)));

        $name = $custom !== '' ? $custom : ($category?->name ?: $fallbackName);
        $url = $category
            ? route('product.List', ['id' => $category->id])
            : route('product.List');

        return compact('name', 'url', 'image');
    }

    public function departmentKey(?string $name): ?string
    {
        $name = strtolower(trim((string) $name));

        if ($name === '') {
            return null;
        }

        if (str_contains($name, 'women') || str_contains($name, 'woman') || str_contains($name, 'female')) {
            return 'women';
        }

        if ($name === 'men' || $name === 'man' || str_contains($name, 'male')) {
            return 'men';
        }

        return null;
    }

    public function departmentLabel(?string $name): ?string
    {
        $key = $this->departmentKey($name);

        return $key ? __('web.nav_'.$key) : null;
    }

    /**
     * Men and Women menus for the navbar, hero, and homepage cards.
     *
     * @return array<int, array{key: string, label: string, shop_label: string, url: string, image: string, columns: array<int, array{title: string, links: array<int, array{name: string, url: string}>}>}>
     */
    public function departmentMenus(): array
    {
        $types = \App\Models\Type::query()->where('is_active', 1)->get();
        $categories = \App\Models\Category::query()->where('is_active', 1)->orderBy('name')->get();
        $categoryIdsByType = \App\Models\products::query()
            ->sellable()
            ->select('type_id', 'category_id')
            ->distinct()
            ->get()
            ->groupBy('type_id')
            ->map(fn ($rows) => $rows->pluck('category_id')->map(fn ($id) => (int) $id)->all());

        $menus = [];
        foreach (['men' => $this->categoryImage1Url(), 'women' => $this->categoryImage2Url()] as $key => $image) {
            $type = $types->first(fn ($item) => $this->departmentKey($item->name) === $key);
            if (! $type) {
                continue;
            }

            $allowed = $categoryIdsByType->get($type->id, []);
            $links = $categories
                ->filter(fn ($category) => in_array((int) $category->id, $allowed, true))
                ->map(fn ($category) => [
                    'name' => $category->name,
                    'url' => route('product.List', ['id' => $category->id, 'type_id' => $type->id]),
                    'group' => $this->categoryMenuGroup($category->name),
                ]);

            $columns = [];
            foreach ([
                'nightwear' => __('web.mega_nightwear'),
                'pajamas' => __('web.mega_pajamas'),
                'more' => __('web.mega_more'),
            ] as $group => $title) {
                $groupLinks = $links->where('group', $group)->map(fn ($link) => [
                    'name' => $link['name'],
                    'url' => $link['url'],
                ])->values()->all();

                if ($groupLinks !== []) {
                    $columns[] = ['title' => $title, 'links' => $groupLinks];
                }
            }

            $menus[] = [
                'key' => $key,
                'label' => __('web.nav_'.$key),
                'shop_label' => __('web.shop_'.$key.'_collection'),
                'url' => route('product.List', ['type_id' => $type->id]),
                'image' => $image,
                'columns' => $columns,
            ];
        }

        return $menus;
    }

    protected function categoryMenuGroup(string $name): string
    {
        $name = strtolower($name);

        if (str_contains($name, 'night') || str_contains($name, 'gown')) {
            return 'nightwear';
        }

        if (str_contains($name, 'pajama') || str_contains($name, 'pajma') || str_contains($name, 'capri')) {
            return 'pajamas';
        }

        return 'more';
    }

    public function assetUrl(?string $path): string
    {
        if (empty($path)) {
            return '';
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, 'assets/')) {
            return asset($path).$this->versionQuery();
        }

        return asset('storage/'.$path).$this->versionQuery();
    }

    public function filesystemPath(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        if (str_starts_with($path, 'assets/')) {
            $full = public_path($path);

            return file_exists($full) ? $full : null;
        }

        return app(PublicUpload::class)->locate($path);
    }

    public function uploadFile(UploadedFile $file, string $field): string
    {
        $directory = config('branding.storage_directory', 'branding');
        $extension = strtolower((string) ($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg'));
        $extension = preg_replace('/[^a-z0-9]/', '', $extension) ?: 'jpg';

        return app(PublicUpload::class)->store($file, $directory, $field.'_'.time().'.'.$extension);
    }

    public function deleteStoredFile(?string $path): void
    {
        if (empty($path) || str_starts_with($path, 'assets/')) {
            return;
        }

        app(PublicUpload::class)->delete($path);
    }

    protected function versionQuery(): string
    {
        $updated = $this->settings()->updated_at;

        return $updated ? '?v='.$updated->timestamp : '';
    }
}
