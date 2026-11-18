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
            'categories' => Category::orderBy('name')->get(),
            'branding' => $branding,
        ]);
    }

    public function update(Request $request, BrandingService $branding)
    {
        $rules = [
            'site_name' => 'nullable|string|max:255',
            'tagline_en' => 'nullable|string|max:255',
            'tagline_ar' => 'nullable|string|max:255',
            'hero_title_en' => 'nullable|string|max:255',
            'hero_title_ar' => 'nullable|string|max:255',
            'hero_lead_en' => 'nullable|string|max:500',
            'hero_lead_ar' => 'nullable|string|max:500',
            'collection_title_en' => 'nullable|string|max:255',
            'collection_title_ar' => 'nullable|string|max:255',
            'collection_lead_en' => 'nullable|string|max:500',
            'collection_lead_ar' => 'nullable|string|max:500',
            'support_email' => 'nullable|email|max:255',
            'support_phone' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'favicon' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,ico|max:1024',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'hero_image_mobile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
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
            $rules["{$platform}_hidden"] = 'nullable|boolean';
        }

        $validated = $request->validate($rules);
        $settings = SiteSetting::current();

        foreach (['home_category_1_id', 'home_category_2_id'] as $idField) {
            $validated[$idField] = $request->filled($idField) ? (int) $request->input($idField) : null;
        }

        foreach (['invoice_notes_en', 'invoice_notes_ar', 'cart_policy_body_en', 'cart_policy_body_ar'] as $notesField) {
            if (array_key_exists($notesField, $validated)) {
                $validated[$notesField] = trim(strip_tags($validated[$notesField] ?? '')) ?: null;
            }
        }

        foreach (['cart_policy_title_en', 'cart_policy_title_ar', 'home_tile_1_title_en', 'home_tile_1_title_ar', 'home_tile_2_title_en', 'home_tile_2_title_ar', 'tagline_en', 'tagline_ar', 'hero_title_en', 'hero_title_ar', 'hero_lead_en', 'hero_lead_ar', 'collection_title_en', 'collection_title_ar', 'collection_lead_en', 'collection_lead_ar'] as $titleField) {
            if (array_key_exists($titleField, $validated)) {
                $validated[$titleField] = trim(strip_tags($validated[$titleField] ?? '')) ?: null;
            }
        }

        foreach (array_keys(config('branding.social_platforms', [])) as $platform) {
            $field = "{$platform}_url";
            if (array_key_exists($field, $validated)) {
                $raw = trim($validated[$field] ?? '');
                $validated[$field] = $raw === '' ? null : $branding->normalizeSocialUrl($raw, $platform);
            }
            $hiddenField = "{$platform}_hidden";
            $validated[$hiddenField] = $request->boolean($hiddenField);
        }

        $scalarFields = [
            'site_name', 'tagline_en', 'tagline_ar',
            'hero_title_en', 'hero_title_ar', 'hero_lead_en', 'hero_lead_ar',
            'collection_title_en', 'collection_title_ar', 'collection_lead_en', 'collection_lead_ar',
            'accent_color', 'support_email', 'support_phone',
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
            $scalarFields[] = "{$platform}_hidden";
        }

        foreach ($scalarFields as $field) {
            if (array_key_exists($field, $validated)) {
                $settings->{$field} = $validated[$field];
            }
        }

        $fileMap = [
            'logo' => 'logo_path',
            'favicon' => 'favicon_path',
            'hero_image' => 'hero_image_path',
            'hero_image_mobile' => 'hero_image_mobile_path',
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

        return redirect()->to(route('admin.settings.branding').'#home-tiles')->with('success', __('branding.saved'));
    }

    public function pdfPreview(Request $request, PdfService $pdf)
    {
        return $pdf->previewSampleInvoice($request->get('lang'));
    }
}
