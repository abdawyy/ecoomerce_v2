<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SiteSetting;
use App\Services\BrandingService;
use App\Services\PdfService;
use Illuminate\Http\Request;

class SiteSettingsController extends Controller
{
    public function edit(BrandingService $branding)
    {
        return view('admin.settings.branding', [
            'settings' => $branding->settings(),
            'categories' => Category::where('is_active', 1)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, BrandingService $branding)
    {
        $rules = [
            'site_name' => 'nullable|string|max:255',
            'tagline_en' => 'nullable|string|max:500',
            'tagline_ar' => 'nullable|string|max:500',
            'logo_alt_en' => 'nullable|string|max:255',
            'logo_alt_ar' => 'nullable|string|max:255',
            'primary_color' => 'nullable|string|max:20',
            'support_email' => 'nullable|email|max:255',
            'support_phone' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'logo_dark' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,svg|max:2048',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'footer_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'placeholder_product' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'category_image_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'category_image_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'accent_color' => 'nullable|string|max:20',
            'pdf_footer_en' => 'nullable|string|max:500',
            'pdf_footer_ar' => 'nullable|string|max:500',
            'pdf_thank_you_en' => 'nullable|string|max:500',
            'pdf_thank_you_ar' => 'nullable|string|max:500',
            'invoice_notes_en' => 'nullable|string',
            'invoice_notes_ar' => 'nullable|string',
            'cart_policy_title_en' => 'nullable|string|max:255',
            'cart_policy_title_ar' => 'nullable|string|max:255',
            'cart_policy_body_en' => 'nullable|string',
            'cart_policy_body_ar' => 'nullable|string',
            'home_category_1_id' => 'nullable|integer|exists:categories,id',
            'home_category_2_id' => 'nullable|integer|exists:categories,id',
            'home_tile_1_title_en' => 'nullable|string|max:80',
            'home_tile_1_title_ar' => 'nullable|string|max:80',
            'home_tile_2_title_en' => 'nullable|string|max:80',
            'home_tile_2_title_ar' => 'nullable|string|max:80',
        ];

        foreach (array_keys(config('branding.social_platforms', [])) as $platform) {
            $rules["{$platform}_url"] = 'nullable|string|max:500';
        }

        $validated = $request->validate($rules);

        $settings = SiteSetting::current();

        foreach (['invoice_notes_en', 'invoice_notes_ar', 'cart_policy_body_en', 'cart_policy_body_ar'] as $notesField) {
            if (array_key_exists($notesField, $validated)) {
                $validated[$notesField] = trim(strip_tags($validated[$notesField] ?? '')) ?: null;
            }
        }

        foreach (['cart_policy_title_en', 'cart_policy_title_ar', 'home_tile_1_title_en', 'home_tile_1_title_ar', 'home_tile_2_title_en', 'home_tile_2_title_ar'] as $titleField) {
            if (array_key_exists($titleField, $validated)) {
                $validated[$titleField] = trim(strip_tags($validated[$titleField] ?? '')) ?: null;
            }
        }

        foreach (array_keys(config('branding.social_platforms', [])) as $platform) {
            $field = "{$platform}_url";
            if (! array_key_exists($field, $validated)) {
                continue;
            }
            $raw = trim($validated[$field] ?? '');
            $validated[$field] = $raw === '' ? null : $branding->normalizeSocialUrl($raw, $platform);
        }

        $scalarFields = [
            'site_name', 'tagline_en', 'tagline_ar', 'logo_alt_en', 'logo_alt_ar',
            'primary_color', 'accent_color', 'support_email', 'support_phone',
            'pdf_footer_en', 'pdf_footer_ar', 'pdf_thank_you_en', 'pdf_thank_you_ar',
            'invoice_notes_en', 'invoice_notes_ar',
            'cart_policy_title_en', 'cart_policy_title_ar',
            'cart_policy_body_en', 'cart_policy_body_ar',
            'home_category_1_id', 'home_category_2_id',
            'home_tile_1_title_en', 'home_tile_1_title_ar',
            'home_tile_2_title_en', 'home_tile_2_title_ar',
        ];
        foreach (array_keys(config('branding.social_platforms', [])) as $platform) {
            $scalarFields[] = "{$platform}_url";
        }

        foreach ($scalarFields as $field) {
            if (array_key_exists($field, $validated)) {
                $settings->{$field} = $validated[$field];
            }
        }

        $fileMap = [
            'logo' => 'logo_path',
            'logo_dark' => 'logo_dark_path',
            'favicon' => 'favicon_path',
            'og_image' => 'og_image_path',
            'footer_logo' => 'footer_logo_path',
            'placeholder_product' => 'placeholder_product_path',
            'hero_image' => 'hero_image_path',
            'category_image_1' => 'category_image_1_path',
            'category_image_2' => 'category_image_2_path',
        ];

        foreach ($fileMap as $input => $column) {
            if ($request->hasFile($input)) {
                $branding->deleteStoredFile($settings->{$column});
                $settings->{$column} = $branding->uploadFile($request->file($input), $input);
            }
        }

        $settings->save();
        $branding->clearCache();

        return redirect()->route('admin.settings.branding')->with('success', __('branding.saved'));
    }

    public function pdfPreview(Request $request, PdfService $pdf)
    {
        return $pdf->previewSampleInvoice($request->get('lang'));
    }
}
