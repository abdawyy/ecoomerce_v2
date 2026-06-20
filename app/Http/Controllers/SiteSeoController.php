<?php

namespace App\Http\Controllers;

use App\Models\SeoPage;
use App\Models\SiteSetting;
use App\Services\BrandingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SiteSeoController extends Controller
{
    public function edit(BrandingService $branding)
    {
        $pages = Schema::hasTable('seo_pages')
            ? SeoPage::orderBy('page_key')->get()
            : collect();

        return view('admin.settings.seo', [
            'settings' => $branding->settings(),
            'pages' => $pages,
        ]);
    }

    public function update(Request $request, BrandingService $branding)
    {
        $validated = $request->validate([
            'meta_title_en' => 'nullable|string|max:255',
            'meta_title_ar' => 'nullable|string|max:255',
            'meta_description_en' => 'nullable|string|max:500',
            'meta_description_ar' => 'nullable|string|max:500',
            'meta_keywords_en' => 'nullable|string|max:500',
            'meta_keywords_ar' => 'nullable|string|max:500',
            'og_title_en' => 'nullable|string|max:255',
            'og_title_ar' => 'nullable|string|max:255',
            'og_description_en' => 'nullable|string|max:500',
            'og_description_ar' => 'nullable|string|max:500',
            'twitter_card' => 'nullable|string|max:32',
            'robots' => 'nullable|string|max:64',
            'canonical_url' => 'nullable|url|max:500',
        ]);

        $settings = SiteSetting::current();
        $settings->fill($validated);
        $settings->save();
        $branding->clearCache();

        if (Schema::hasTable('seo_pages') && $request->has('pages')) {
            foreach ($request->input('pages', []) as $pageKey => $data) {
                SeoPage::updateOrCreate(
                    ['page_key' => $pageKey],
                    [
                        'meta_title_en' => $data['meta_title_en'] ?? null,
                        'meta_title_ar' => $data['meta_title_ar'] ?? null,
                        'meta_description_en' => $data['meta_description_en'] ?? null,
                        'meta_description_ar' => $data['meta_description_ar'] ?? null,
                        'meta_keywords_en' => $data['meta_keywords_en'] ?? null,
                        'meta_keywords_ar' => $data['meta_keywords_ar'] ?? null,
                        'is_indexable' => isset($data['is_indexable']),
                    ]
                );
            }
        }

        return redirect()->route('admin.settings.seo')->with('success', __('seo.saved'));
    }
}
