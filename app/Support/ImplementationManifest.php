<?php

namespace App\Support;

class ImplementationManifest
{
    /** @return array<string, string> path => label */
    public static function requiredFiles(): array
    {
        return [
            'database/migrations/2026_06_04_000001_create_site_settings_table.php' => 'Task 1 migration',
            'app/Models/SiteSetting.php' => 'SiteSetting model',
            'app/Services/BrandingService.php' => 'BrandingService',
            'config/branding.php' => 'branding config',
            'app/Http/Controllers/SiteSettingsController.php' => 'SiteSettingsController',
            'resources/views/admin/settings/branding.blade.php' => 'admin branding view',
            'resources/views/components/branding/logo.blade.php' => 'logo component',
            'resources/views/components/branding/product-image.blade.php' => 'product-image component',

            'database/migrations/2026_06_04_000002_add_seo_to_site_settings.php' => 'SEO site_settings migration',
            'database/migrations/2026_06_04_000003_add_seo_to_catalog_tables.php' => 'SEO catalog migration',
            'database/migrations/2026_06_04_000004_create_seo_pages_table.php' => 'seo_pages migration',
            'app/Services/SeoService.php' => 'SeoService',
            'app/Http/Controllers/SiteSeoController.php' => 'SiteSeoController',
            'app/Http/Controllers/SeoPublicController.php' => 'SeoPublicController',
            'app/Models/SeoPage.php' => 'SeoPage model',
            'resources/views/components/seo/meta.blade.php' => 'seo meta component',
            'resources/views/admin/settings/seo.blade.php' => 'admin SEO view',
            'config/seo.php' => 'seo config',
            'resources/lang/en/seo.php' => 'seo lang EN',
            'resources/lang/ar/seo.php' => 'seo lang AR',

            'database/migrations/2026_06_04_000005_create_analytics_tables.php' => 'analytics migration',
            'app/Models/ProductView.php' => 'ProductView model',
            'app/Models/PageView.php' => 'PageView model',
            'app/Models/VisitorPresence.php' => 'VisitorPresence model',
            'app/Models/AnalyticsDaily.php' => 'AnalyticsDaily model',
            'app/Services/AnalyticsService.php' => 'AnalyticsService',
            'app/Http/Controllers/AnalyticsController.php' => 'AnalyticsController',
            'app/Http/Middleware/TrackAnalytics.php' => 'TrackAnalytics middleware',
            'app/Jobs/AggregateAnalyticsDaily.php' => 'AggregateAnalyticsDaily job',
            'app/Console/Commands/AggregateAnalyticsCommand.php' => 'analytics:aggregate command',
            'resources/views/admin/analytics/index.blade.php' => 'analytics dashboard',
            'resources/views/admin/analytics/product.blade.php' => 'analytics product drill-down',
            'config/analytics.php' => 'analytics config',
            'resources/lang/en/analytics.php' => 'analytics lang EN',
            'resources/lang/ar/analytics.php' => 'analytics lang AR',

            'database/migrations/2026_06_04_000006_create_guides_and_pdf_settings.php' => 'guides migration',
            'app/Models/Guide.php' => 'Guide model',
            'app/Http/Controllers/GuideController.php' => 'GuideController',
            'app/Http/Controllers/AdminGuideController.php' => 'AdminGuideController',
            'resources/views/guides/index.blade.php' => 'guides storefront',
            'resources/views/admin/guides/list.blade.php' => 'admin guides list',
            'resources/views/admin/guides/edit.blade.php' => 'admin guides edit',
            'resources/lang/en/guides.php' => 'guides lang EN',
            'resources/lang/ar/guides.php' => 'guides lang AR',

            'resources/views/pdf/layout.blade.php' => 'pdf layout',
            'resources/views/pdf/invoice.blade.php' => 'pdf invoice',
            'resources/views/pdf/guide.blade.php' => 'pdf guide',
            'resources/views/pdf/partials/header.blade.php' => 'pdf header partial',
            'resources/views/pdf/partials/footer.blade.php' => 'pdf footer partial',
            'resources/views/pdf/partials/styles.blade.php' => 'pdf styles partial',
            'app/Services/PdfService.php' => 'PdfService',
            'config/pdf.php' => 'pdf config',
            'resources/lang/en/pdf.php' => 'pdf lang EN',
            'resources/lang/ar/pdf.php' => 'pdf lang AR',
        ];
    }

    public static function requiredRoutes(): array
    {
        return [
            'home',
            'guides.index',
            'guides.download',
            'sitemap',
            'robots',
            'admin.settings.branding',
            'admin.settings.seo',
            'admin.analytics',
            'guides.admin.list',
            'order.invoice',
            'admin.settings.pdf.preview',
        ];
    }
}
