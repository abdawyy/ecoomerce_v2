<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use App\Models\SiteSetting;
use App\Services\BrandingService;
use App\Services\PdfService;
use App\Traits\Apptraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AdminGuideController extends Controller
{
    use Apptraits;

    public function list(BrandingService $branding)
    {
        return view('admin.guides.list', [
            'chartUrl' => $branding->sizeChartUrl(),
        ]);
    }

    public function updateSizeChart(Request $request, BrandingService $branding)
    {
        if (! Schema::hasColumn('site_settings', 'size_chart_path')) {
            return redirect()->route('guides.admin.list')->with('error', __('guides.chart_missing_column'));
        }

        $request->validate([
            'size_chart' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $settings = SiteSetting::current();
        $previous = $settings->size_chart_path;
        $settings->size_chart_path = $branding->uploadFile($request->file('size_chart'), 'size_chart');
        $settings->save();
        $branding->clearCache();

        if ($previous && $previous !== $settings->size_chart_path) {
            $branding->deleteStoredFile($previous);
        }

        return redirect()->route('guides.admin.list')->with('success', __('guides.chart_saved'));
    }

    public function edit(Request $request, PdfService $pdfService, $id = null)
    {
        $model = $id ? Guide::find($id) : null;

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'title_en' => 'required|string|max:255',
                'title_ar' => 'nullable|string|max:255',
                'description_en' => 'nullable|string|max:1000',
                'description_ar' => 'nullable|string|max:1000',
                'slug' => 'nullable|string|max:255|unique:guides,slug,'.($id ?: 'NULL').',id',
                'content_type' => 'required|in:upload,html',
                'html_content_en' => 'nullable|string',
                'html_content_ar' => 'nullable|string',
                'sort_order' => 'nullable|integer|min:0',
                'is_active' => 'nullable|boolean',
                'requires_auth' => 'nullable|boolean',
                'pdf_file' => 'nullable|file|mimes:pdf|max:'.config('pdf.guides_max_upload_kb', 10240),
            ]);

            $guide = $model ?? new Guide;
            $guide->fill([
                'title_en' => $validated['title_en'],
                'title_ar' => $validated['title_ar'] ?? null,
                'description_en' => $validated['description_en'] ?? null,
                'description_ar' => $validated['description_ar'] ?? null,
                'slug' => $validated['slug'] ?: Str::slug($validated['title_en']),
                'content_type' => $validated['content_type'],
                'html_content_en' => $validated['html_content_en'] ?? null,
                'html_content_ar' => $validated['html_content_ar'] ?? null,
                'sort_order' => $validated['sort_order'] ?? 0,
                'is_active' => $request->boolean('is_active', true),
                'requires_auth' => $request->boolean('requires_auth'),
                'published_at' => $guide->published_at ?? now(),
            ]);

            if ($request->hasFile('pdf_file')) {
                $pdfService->deleteGuideFile($guide->file_path);
                $guide->file_path = $pdfService->uploadGuideFile($request->file('pdf_file'));
                $guide->content_type = 'upload';
            }

            $guide->save();

            return redirect()->route('guides.admin.list')->with('success', __('guides.saved'));
        }

        return view('admin.guides.edit', compact('model'));
    }

    public function delete($id, PdfService $pdfService)
    {
        $guide = Guide::findOrFail($id);
        $pdfService->deleteGuideFile($guide->file_path);
        $guide->delete();

        return redirect()->route('guides.admin.list')->with('success', __('guides.saved'));
    }

    public function toggleStatus($id)
    {
        $guide = Guide::findOrFail($id);
        $guide->is_active = ! $guide->is_active;
        $guide->save();

        return redirect()->back();
    }

    public function downloadManual(Request $request, PdfService $pdf)
    {
        return $pdf->streamAdminManual($request->get('lang'));
    }

    public function previewSample(Request $request, PdfService $pdf)
    {
        return $pdf->previewEditableSample($request->get('lang'));
    }

    public function previewGuide($id, Request $request, PdfService $pdf)
    {
        $guide = Guide::findOrFail($id);
        $locale = in_array($request->get('lang'), ['en', 'ar'], true) ? $request->get('lang') : app()->getLocale();

        return $pdf->streamGuide($guide, $locale);
    }
}
