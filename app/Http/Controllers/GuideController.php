<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use App\Services\AnalyticsService;
use App\Services\PdfService;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
class GuideController extends Controller
{
    public function index(SeoService $seo)
    {
        if (! Schema::hasTable('guides')) {
            return view('guides.index', ['guides' => collect(), 'seo' => $seo->forPage('guides')->resolve()]);
        }

        $guides = Guide::active()->orderBy('sort_order')->orderBy('title_en')->get();

        return view('guides.index', [
            'guides' => $guides,
            'seo' => $seo->forPage('guides')->resolve(),
        ]);
    }

    public function download(string $slug, Request $request, PdfService $pdf, AnalyticsService $analytics)
    {
        if (! Schema::hasTable('guides')) {
            abort(404);
        }

        $guide = Guide::active()->where('slug', $slug)->firstOrFail();

        if ($guide->requires_auth && ! auth()->check()) {
            return redirect()->route('login')->with('error', __('guides.requires_auth'));
        }

        $guide->increment('download_count');

        if (Schema::hasTable('page_views')) {
            $analytics->recordPageView('guide_download', $request);
        }

        return $pdf->streamGuide($guide);
    }
}
