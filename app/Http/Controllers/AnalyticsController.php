<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response as ResponseFacade;
use Illuminate\Support\Facades\Schema;

class AnalyticsController extends Controller
{
    public function index(Request $request, AnalyticsService $analytics)
    {
        [$start, $end] = $analytics->parseDateRange(
            $request->get('range', 'last_30'),
            $request->get('from'),
            $request->get('to')
        );

        $kpis = $analytics->dashboardKpis($start, $end);

        return view('admin.analytics.index', [
            'range' => $request->get('range', 'last_30'),
            'from' => $request->get('from'),
            'to' => $request->get('to'),
            'start' => $start,
            'end' => $end,
            'tab' => $request->get('tab', 'overview'),
            'liveCount' => $analytics->liveVisitorCount(),
            'liveVisitors' => $analytics->liveVisitors(15),
            'kpis' => $kpis,
            'sales' => $kpis['current']['sales'],
            'pageViews' => $kpis['current']['page_views'],
            'productViews' => $kpis['current']['product_views'],
            'uniqueVisitors' => $kpis['current']['unique_visitors'],
            'change' => $kpis['change'],
            'topProducts' => $analytics->topProducts($start, $end, 15),
            'topPages' => $analytics->topPages($start, $end, 10),
            'categoryPerformance' => $analytics->categoryPerformance($start, $end, 10),
            'salesByCity' => $analytics->salesByCity($start, $end, 10),
            'trafficByHour' => $analytics->trafficByHour($start, $end),
            'localeBreakdown' => $analytics->localeBreakdown($start, $end),
            'funnel' => $analytics->funnelSummary($start, $end),
            'windowShoppers' => $analytics->windowShoppers($start, $end, 10),
            'chartViews' => $analytics->viewsTrend($start, $end),
            'chartRevenue' => $analytics->revenueTrend($start, $end),
            'customerOverview' => $analytics->customerOverview($start, $end),
            'deviceBreakdown' => $analytics->deviceBreakdown($start, $end),
            'trafficSources' => $analytics->trafficSourceBreakdown($start, $end),
            'topSearchTerms' => $analytics->topSearchTerms($start, $end, 15),
            'topCustomers' => $analytics->topCustomers($start, $end, 15),
            'salesByWeekday' => $analytics->salesByWeekday($start, $end),
            'paymentMethods' => $analytics->paymentMethodBreakdown($start, $end),
        ]);
    }

    public function live(AnalyticsService $analytics)
    {
        return response()->json([
            'count' => $analytics->liveVisitorCount(),
            'visitors' => $analytics->liveVisitors(20)->map(fn ($v) => [
                'path' => $v->current_path,
                'page' => $v->current_page_key,
                'product' => $v->product?->name,
                'user' => $v->user?->name,
                'locale' => $v->locale,
                'last_seen' => $v->last_seen_at?->diffForHumans(),
            ]),
        ]);
    }

    public function export(Request $request, AnalyticsService $analytics)
    {
        [$start, $end] = $analytics->parseDateRange(
            $request->get('range', 'last_30'),
            $request->get('from'),
            $request->get('to')
        );

        $type = $request->get('type', 'products');

        if ($type === 'customers') {
            $csv = '';
            foreach ($analytics->customerExportRows($start, $end) as $row) {
                $csv .= collect($row)->map(function ($cell) {
                    $value = (string) $cell;
                    if (str_contains($value, ',') || str_contains($value, '"')) {
                        return '"'.str_replace('"', '""', $value).'"';
                    }

                    return $value;
                })->implode(',')."\n";
            }

            return ResponseFacade::make($csv, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="customer-analytics.csv"',
            ]);
        }

        $rows = $analytics->topProducts($start, $end, 500);
        $csv = "product_id,product_name,views,unique_visitors\n";
        foreach ($rows as $row) {
            $csv .= sprintf(
                "%d,%s,%d,%d\n",
                $row->product_id,
                '"'.str_replace('"', '""', $row->product?->name ?? '').'"',
                $row->views,
                $row->uniques
            );
        }

        return ResponseFacade::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="product-views.csv"',
        ]);
    }

    public function productDetail(Request $request, $id, AnalyticsService $analytics)
    {
        $product = products::findOrFail($id);
        [$start, $end] = $analytics->parseDateRange(
            $request->get('range', 'last_30'),
            $request->get('from'),
            $request->get('to')
        );

        $daily = collect();
        if (Schema::hasTable('analytics_daily')) {
            $daily = \App\Models\AnalyticsDaily::where('product_id', $id)
                ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
                ->orderBy('date')
                ->get();
        }

        $range = $request->get('range', 'last_30');

        return view('admin.analytics.product', compact('product', 'start', 'end', 'daily', 'range'));
    }
}
