<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'tagline_en',
        'tagline_ar',
        'logo_path',
        'logo_dark_path',
        'favicon_path',
        'og_image_path',
        'footer_logo_path',
        'placeholder_product_path',
        'hero_image_path',
        'category_image_1_path',
        'category_image_2_path',
        'logo_alt_en',
        'logo_alt_ar',
        'primary_color',
        'accent_color',
        'support_email',
        'support_phone',
        'instagram_url',
        'facebook_url',
        'tiktok_url',
        'youtube_url',
        'whatsapp_url',
        'twitter_url',
        'linkedin_url',
        'telegram_url',
        'pinterest_url',
        'snapchat_url',
        'pdf_footer_en',
        'pdf_footer_ar',
        'pdf_thank_you_en',
        'pdf_thank_you_ar',
        'invoice_notes_en',
        'invoice_notes_ar',
        'cart_policy_title_en',
        'cart_policy_title_ar',
        'cart_policy_body_en',
        'cart_policy_body_ar',
        'meta_title_en',
        'meta_title_ar',
        'meta_description_en',
        'meta_description_ar',
        'meta_keywords_en',
        'meta_keywords_ar',
        'og_title_en',
        'og_title_ar',
        'og_description_en',
        'og_description_ar',
        'twitter_card',
        'robots',
        'canonical_url',
    ];

    public static function current(): self
    {
        if (! Schema::hasTable('site_settings')) {
            return new self(config('branding.defaults', []));
        }

        return static::query()->firstOrCreate([], []);
    }
}
