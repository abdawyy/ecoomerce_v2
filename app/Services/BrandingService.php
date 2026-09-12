<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

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

            if ($raw === '') {
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

        $full = public_path('storage/'.$path);

        return file_exists($full) ? $full : null;
    }

    public function uploadFile(UploadedFile $file, string $field): string
    {
        $directory = config('branding.storage_directory', 'branding');
        $extension = $file->getClientOriginalExtension();
        $fileName = $field.'_'.time().'.'.$extension;
        $relativePath = $directory.'/'.$fileName;
        $targetDir = public_path('storage/'.$directory);

        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }

        $file->move($targetDir, $fileName);

        return $relativePath;
    }

    public function deleteStoredFile(?string $path): void
    {
        if (empty($path) || str_starts_with($path, 'assets/')) {
            return;
        }

        $full = public_path('storage/'.$path);
        if (file_exists($full)) {
            File::delete($full);
        }
    }

    protected function versionQuery(): string
    {
        $updated = $this->settings()->updated_at;

        return $updated ? '?v='.$updated->timestamp : '';
    }
}
