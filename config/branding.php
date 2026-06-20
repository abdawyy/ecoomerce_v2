<?php

return [

    'defaults' => [
        'site_name' => env('BRAND_SITE_NAME', env('APP_NAME', 'Store')),
        'tagline_en' => env('BRAND_TAGLINE_EN', 'Modern Fashion for Every Style'),
        'tagline_ar' => env('BRAND_TAGLINE_AR', 'أزياء عصرية لكل ذوق'),
        'logo_path' => env('BRAND_LOGO_PATH', 'assets/img/logo.png'),
        'logo_dark_path' => env('BRAND_LOGO_DARK_PATH'),
        'favicon_path' => env('BRAND_FAVICON_PATH'),
        'og_image_path' => env('BRAND_OG_IMAGE_PATH', 'assets/img/logo.png'),
        'footer_logo_path' => env('BRAND_FOOTER_LOGO_PATH'),
        'placeholder_product_path' => env('BRAND_PLACEHOLDER_PATH', 'assets/img/default.jpg'),
        'hero_image_path' => env('BRAND_HERO_IMAGE_PATH', 'assets/img/main2.jpg'),
        'category_image_1_path' => env('BRAND_CATEGORY_IMAGE_1_PATH', 'assets/img/main.jpg'),
        'category_image_2_path' => env('BRAND_CATEGORY_IMAGE_2_PATH', 'assets/img/main3.jpg'),
        'logo_alt_en' => env('BRAND_LOGO_ALT_EN', 'Store logo'),
        'logo_alt_ar' => env('BRAND_LOGO_ALT_AR', 'شعار المتجر'),
        'primary_color' => env('BRAND_PRIMARY_COLOR', '#0d6efd'),
        'support_email' => env('HAYAH_ADMIN_EMAIL', env('MAIL_FROM_ADDRESS')),
        'support_phone' => env('BRAND_SUPPORT_PHONE'),
        'instagram_url' => env('BRAND_INSTAGRAM_URL'),
        'facebook_url' => env('BRAND_FACEBOOK_URL'),
        'tiktok_url' => env('BRAND_TIKTOK_URL'),
        'youtube_url' => env('BRAND_YOUTUBE_URL'),
        'whatsapp_url' => env('BRAND_WHATSAPP_URL'),
        'twitter_url' => env('BRAND_TWITTER_URL'),
        'linkedin_url' => env('BRAND_LINKEDIN_URL'),
        'telegram_url' => env('BRAND_TELEGRAM_URL'),
        'pinterest_url' => env('BRAND_PINTEREST_URL'),
        'snapchat_url' => env('BRAND_SNAPCHAT_URL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Social platforms (URL field: {key}_url on site_settings)
    | Icons are Bootstrap Icons classes — auto-matched from URL when possible.
    |--------------------------------------------------------------------------
    */
    'social_platforms' => [
        'instagram' => [
            'icon' => 'bi-instagram',
            'label_key' => 'branding.social_instagram',
            'hosts' => ['instagram.com', 'instagr.am'],
        ],
        'facebook' => [
            'icon' => 'bi-facebook',
            'label_key' => 'branding.social_facebook',
            'hosts' => ['facebook.com', 'fb.com', 'fb.me'],
        ],
        'tiktok' => [
            'icon' => 'bi-tiktok',
            'label_key' => 'branding.social_tiktok',
            'hosts' => ['tiktok.com'],
        ],
        'youtube' => [
            'icon' => 'bi-youtube',
            'label_key' => 'branding.social_youtube',
            'hosts' => ['youtube.com', 'youtu.be'],
        ],
        'whatsapp' => [
            'icon' => 'bi-whatsapp',
            'label_key' => 'branding.social_whatsapp',
            'hosts' => ['wa.me', 'whatsapp.com', 'api.whatsapp.com'],
        ],
        'twitter' => [
            'icon' => 'bi-twitter-x',
            'label_key' => 'branding.social_twitter',
            'hosts' => ['twitter.com', 'x.com'],
        ],
        'linkedin' => [
            'icon' => 'bi-linkedin',
            'label_key' => 'branding.social_linkedin',
            'hosts' => ['linkedin.com'],
        ],
        'telegram' => [
            'icon' => 'bi-telegram',
            'label_key' => 'branding.social_telegram',
            'hosts' => ['t.me', 'telegram.me', 'telegram.org'],
        ],
        'pinterest' => [
            'icon' => 'bi-pinterest',
            'label_key' => 'branding.social_pinterest',
            'hosts' => ['pinterest.com', 'pin.it'],
        ],
        'snapchat' => [
            'icon' => 'bi-snapchat',
            'label_key' => 'branding.social_snapchat',
            'hosts' => ['snapchat.com'],
        ],
    ],

    'storage_disk' => 'public',
    'storage_directory' => 'branding',

];
