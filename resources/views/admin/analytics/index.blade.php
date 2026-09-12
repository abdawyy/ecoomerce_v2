@php
    $changeBadge = function ($pct) {
        if ($pct === null) return '';
        $up = $pct >= 0;
        $cls = $up ? 'text-success' : 'text-danger';
        $icon = $up ? '↑' : '↓';
        return '<small class="'.$cls.'">'.$icon.' '.abs($pct).'%</small>';
    };
    $query = request()->query();
    $tabUrl = fn ($t) => '?' . http_build_query(array_merge($query, ['tab' => $t]));
@endphp

<x-admin.header />
<x-admin.aside />
<x-admin.navbar />

<main id="main">
    <div class="container-fluid">
        <div class="row pt-4">
            <div class="pagetitle d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div>
                    <h1>{{ __('analytics.title') }}</h1>
                    <p class="text-muted mb-0">{{ $start->format('Y-m-d') }} — {{ $end->format('Y-m-d') }}</p>
                </div>
                <div id="live-badge" class="badge bg-success fs-6 px-3 py-2">
                    {{ __('analytics.live_now') }}: <span id="live-count">{{ $liveCount }}</span>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" class="row g-2 align-items-end">
                        <input type="hidden" name="tab" value="{{ $tab }}">
                        <div class="col-md-3">
                            <label class="form-label">{{ __('analytics.range') }}</label>
                            <select name="range" class="form-select" onchange="this.form.submit()">
                                @foreach (['today','yesterday','last_7','last_30','this_month','last_month','this_year','last_year','all','custom'] as $preset)
                                    <option value="{{ $preset }}" @selected($range === $preset)>{{ __('analytics.preset_'.$preset) }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($range === 'custom')
                            <div class="col-md-2"><input type="date" name="from" class="form-control" value="{{ $from }}"></div>
                            <div class="col-md-2"><input type="date" name="to" class="form-control" value="{{ $to }}"></div>
                        @endif
                        <div class="col-md-2">
                            <a href="{{ route('admin.analytics.export', array_merge(request()->query(), ['type' => $tab === 'customers' ? 'customers' : 'products'])) }}" class="btn btn-outline-secondary w-100">{{ __('analytics.export') }}</a>
                        </div>
                    </form>
                </div>
            </div>

            <ul class="nav nav-tabs mb-3 flex-wrap">
                @foreach (['overview','products','traffic','categories','sales','customers','live'] as $t)
                    <li class="nav-item"><a class="nav-link {{ $tab === $t ? 'active' : '' }}" href="{{ $tabUrl($t) }}">{{ __('analytics.tab_'.$t) }}</a></li>
                @endforeach
            </ul>

            @if (in_array($tab, ['overview', 'products', 'traffic', 'categories', 'sales', 'customers']))
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card h-100"><div class="card-body">
                            <h6 class="text-muted">{{ __('analytics.page_views') }}</h6>
                            <h3 class="mb-0">{{ number_format($pageViews) }}</h3>
                            {!! $changeBadge($change['page_views'] ?? 0) !!}
                        </div></div>
                    </div>
                    <div class="col-md-3">
                        <div class="card h-100"><div class="card-body">
                            <h6 class="text-muted">{{ __('analytics.product_views') }}</h6>
                            <h3 class="mb-0">{{ number_format($productViews) }}</h3>
                            {!! $changeBadge($change['product_views'] ?? 0) !!}
                        </div></div>
                    </div>
                    <div class="col-md-3">
                        <div class="card h-100"><div class="card-body">
                            <h6 class="text-muted">{{ __('analytics.uniques') }}</h6>
                            <h3 class="mb-0">{{ number_format($uniqueVisitors) }}</h3>
                            {!! $changeBadge($change['unique_visitors'] ?? 0) !!}
                        </div></div>
                    </div>
                    <div class="col-md-3">
                        <div class="card h-100"><div class="card-body">
                            <h6 class="text-muted">{{ __('analytics.revenue') }}</h6>
                            <h3 class="mb-0">{{ number_format($sales['revenue'], 2) }} EGP</h3>
                            {!! $changeBadge($change['revenue'] ?? 0) !!}
                        </div></div>
                    </div>
                </div>
            @endif

            @if ($tab === 'overview' || $tab === 'traffic')
                <div class="row g-3 mb-4">
                    <div class="col-lg-8">
                        <div class="card h-100"><div class="card-header">{{ __('analytics.chart_traffic') }}</div>
                            <div class="card-body"><canvas id="chartTraffic" height="120"></canvas></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card h-100"><div class="card-header">{{ __('analytics.chart_hours') }}</div>
                            <div class="card-body"><canvas id="chartHours" height="120"></canvas></div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($tab === 'overview' || $tab === 'sales')
                <div class="row g-3 mb-4">
                    <div class="col-lg-6">
                        <div class="card"><div class="card-header">{{ __('analytics.chart_revenue') }}</div>
                            <div class="card-body"><canvas id="chartRevenue" height="120"></canvas></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card"><div class="card-header">{{ __('analytics.funnel') }}</div>
                            <div class="card-body"><canvas id="chartFunnel" height="120"></canvas></div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($tab === 'overview' || $tab === 'products')
                <div class="card mb-4">
                    <div class="card-header">{{ __('analytics.top_products') }}</div>
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead><tr><th>#</th><th>{{ __('analytics.product') }}</th><th>{{ __('analytics.views') }}</th><th>{{ __('analytics.uniques') }}</th></tr></thead>
                            <tbody>
                                @forelse ($topProducts as $i => $row)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>@if($row->product)<a href="{{ route('admin.analytics.product', $row->product_id) }}?range={{ $range }}">{{ $row->product->name }}</a>@else #{{ $row->product_id }} @endif</td>
                                        <td>{{ $row->views }}</td>
                                        <td>{{ $row->uniques }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted">{{ __('analytics.no_data') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($windowShoppers->isNotEmpty())
                    <div class="card mb-4 border-warning">
                        <div class="card-header">{{ __('analytics.window_shoppers') }}</div>
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead><tr><th>{{ __('analytics.product') }}</th><th>{{ __('analytics.views') }}</th><th>{{ __('analytics.units_sold') }}</th></tr></thead>
                                <tbody>
                                    @foreach ($windowShoppers as $row)
                                        <tr>
                                            <td>{{ $row->product?->name ?? '#'.$row->product_id }}</td>
                                            <td>{{ $row->views }}</td>
                                            <td>{{ $row->orders_qty }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endif

            @if ($tab === 'traffic')
                <div class="row g-3">
                    <div class="col-lg-6">
                        <div class="card"><div class="card-header">{{ __('analytics.top_pages') }}</div>
                            <table class="table mb-0">
                                <thead><tr><th>{{ __('analytics.page') }}</th><th>{{ __('analytics.views') }}</th><th>{{ __('analytics.uniques') }}</th></tr></thead>
                                <tbody>
                                    @forelse ($topPages as $p)
                                        <tr><td>{{ $p->page_key }}</td><td>{{ $p->views }}</td><td>{{ $p->uniques }}</td></tr>
                                    @empty
                                        <tr><td colspan="3" class="text-muted text-center">{{ __('analytics.no_data') }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card"><div class="card-header">{{ __('analytics.locales') }}</div>
                            <table class="table mb-0">
                                <thead><tr><th>{{ __('analytics.locale') }}</th><th>{{ __('analytics.views') }}</th></tr></thead>
                                <tbody>
                                    @forelse ($localeBreakdown as $l)
                                        <tr><td>{{ strtoupper($l->locale ?? '?') }}</td><td>{{ $l->views }}</td></tr>
                                    @empty
                                        <tr><td colspan="2" class="text-muted text-center">{{ __('analytics.no_data') }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            @if ($tab === 'categories')
                <div class="card mb-4">
                    <div class="card-header">{{ __('analytics.category_performance') }}</div>
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead><tr><th>{{ __('analytics.category') }}</th><th>{{ __('analytics.views') }}</th><th>{{ __('analytics.uniques') }}</th></tr></thead>
                            <tbody>
                                @forelse ($categoryPerformance as $c)
                                    <tr><td>{{ $c->category_name }}</td><td>{{ $c->views }}</td><td>{{ $c->uniques }}</td></tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted">{{ __('analytics.no_data') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card"><div class="card-body"><canvas id="chartCategories" height="100"></canvas></div></div>
            @endif

            @if ($tab === 'sales')
                <div class="row g-3 mb-4">
                    <div class="col-md-4"><div class="card p-3"><h6>{{ __('analytics.orders') }}</h6><h3>{{ $sales['orders'] }} {!! $changeBadge($change['orders'] ?? 0) !!}</h3></div></div>
                    <div class="col-md-4"><div class="card p-3"><h6>{{ __('analytics.completed') }}</h6><h3>{{ $sales['completed_orders'] }}</h3></div></div>
                    <div class="col-md-4"><div class="card p-3"><h6>{{ __('analytics.aov') }}</h6><h3>{{ number_format($sales['aov'], 2) }} EGP</h3></div></div>
                </div>
                <div class="card">
                    <div class="card-header">{{ __('analytics.sales_by_city') }}</div>
                    <table class="table mb-0">
                        <thead><tr><th>{{ __('analytics.city') }}</th><th>{{ __('analytics.orders') }}</th><th>{{ __('analytics.revenue') }}</th></tr></thead>
                        <tbody>
                            @forelse ($salesByCity as $c)
                                <tr><td>{{ $c->city_name }}</td><td>{{ $c->orders_count }}</td><td>{{ number_format($c->revenue, 2) }} EGP</td></tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted">{{ __('analytics.no_data') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif

            @if ($tab === 'customers')
                <div class="row g-3 mb-4">
                    <div class="col-md-3"><div class="card h-100"><div class="card-body">
                        <h6 class="text-muted">{{ __('analytics.new_customers') }}</h6>
                        <h3 class="mb-0">{{ number_format($customerOverview['new_customers']) }}</h3>
                    </div></div></div>
                    <div class="col-md-3"><div class="card h-100"><div class="card-body">
                        <h6 class="text-muted">{{ __('analytics.returning_customers') }}</h6>
                        <h3 class="mb-0">{{ number_format($customerOverview['returning_customers']) }}</h3>
                    </div></div></div>
                    <div class="col-md-3"><div class="card h-100"><div class="card-body">
                        <h6 class="text-muted">{{ __('analytics.repeat_rate') }}</h6>
                        <h3 class="mb-0">{{ $customerOverview['repeat_rate'] }}%</h3>
                    </div></div></div>
                    <div class="col-md-3"><div class="card h-100"><div class="card-body">
                        <h6 class="text-muted">{{ __('analytics.atc_rate') }}</h6>
                        <h3 class="mb-0">{{ $customerOverview['atc_rate'] }}%</h3>
                    </div></div></div>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-3"><div class="card p-3"><h6>{{ __('analytics.guest_orders') }}</h6><h3>{{ $customerOverview['guest_orders'] }}</h3></div></div>
                    <div class="col-md-3"><div class="card p-3"><h6>{{ __('analytics.registered_orders') }}</h6><h3>{{ $customerOverview['registered_orders'] }}</h3></div></div>
                    <div class="col-md-3"><div class="card p-3"><h6>{{ __('analytics.new_accounts') }}</h6><h3>{{ $customerOverview['new_accounts'] }}</h3></div></div>
                    <div class="col-md-3"><div class="card p-3"><h6>{{ __('analytics.add_to_cart') }}</h6><h3>{{ $customerOverview['add_to_cart'] }}</h3></div></div>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-lg-4">
                        <div class="card h-100"><div class="card-header">{{ __('analytics.devices') }}</div>
                            <div class="card-body"><canvas id="chartDevices" height="140"></canvas></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card h-100"><div class="card-header">{{ __('analytics.traffic_sources') }}</div>
                            <div class="card-body"><canvas id="chartSources" height="140"></canvas></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card h-100"><div class="card-header">{{ __('analytics.sales_by_weekday') }}</div>
                            <div class="card-body"><canvas id="chartWeekday" height="140"></canvas></div>
                        </div>
                    </div>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">{{ __('analytics.top_customers') }}</div>
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead><tr><th>{{ __('analytics.customer') }}</th><th>{{ __('analytics.customer_type') }}</th><th>{{ __('analytics.orders') }}</th><th>{{ __('analytics.revenue') }}</th></tr></thead>
                                    <tbody>
                                        @forelse ($topCustomers as $c)
                                            <tr>
                                                <td>{{ $c->customer_name }}</td>
                                                <td>{{ __('analytics.type_'.$c->customer_type) }}</td>
                                                <td>{{ $c->orders_count }}</td>
                                                <td>{{ number_format($c->revenue, 2) }} EGP</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center text-muted">{{ __('analytics.no_data') }}</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card mb-3">
                            <div class="card-header">{{ __('analytics.top_searches') }}</div>
                            <table class="table mb-0">
                                <thead><tr><th>{{ __('analytics.search_term') }}</th><th>{{ __('analytics.searches') }}</th></tr></thead>
                                <tbody>
                                    @forelse ($topSearchTerms as $s)
                                        <tr><td>{{ $s->term }}</td><td>{{ $s->searches }}</td></tr>
                                    @empty
                                        <tr><td colspan="2" class="text-center text-muted">{{ __('analytics.no_data') }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card">
                            <div class="card-header">{{ __('analytics.payment_methods') }}</div>
                            <table class="table mb-0">
                                <thead><tr><th>{{ __('analytics.payment_method') }}</th><th>{{ __('analytics.orders') }}</th><th>{{ __('analytics.revenue') }}</th></tr></thead>
                                <tbody>
                                    @forelse ($paymentMethods as $p)
                                        <tr><td>{{ $p->payment_method ?: '—' }}</td><td>{{ $p->cnt }}</td><td>{{ number_format($p->amount, 2) }} EGP</td></tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center text-muted">{{ __('analytics.no_data') }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            @if ($tab === 'live')
                <div class="card">
                    <div class="card-body table-responsive">
                        <table class="table mb-0">
                            <thead><tr><th>{{ __('analytics.path') }}</th><th>{{ __('analytics.page') }}</th><th>{{ __('analytics.product') }}</th><th>{{ __('analytics.user') }}</th><th>{{ __('analytics.last_seen') }}</th></tr></thead>
                            <tbody id="live-list">
                                @foreach ($liveVisitors as $v)
                                    <tr>
                                        <td>{{ $v->current_path }}</td>
                                        <td>{{ $v->current_page_key }}</td>
                                        <td>{{ $v->product?->name ?? '—' }}</td>
                                        <td>{{ $v->user?->name ?? __('analytics.guest') }}</td>
                                        <td>{{ $v->last_seen_at?->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    const refreshMs = {{ (int) config('analytics.live_refresh_seconds', 45) * 1000 }};
    function pollLive() {
        fetch('{{ route('admin.analytics.live') }}', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                const el = document.getElementById('live-count');
                if (el) el.textContent = data.count;
            }).catch(() => {});
    }
    setInterval(pollLive, refreshMs);

    @if (in_array($tab, ['overview', 'traffic', 'sales', 'customers', 'categories']))
    const traffic = @json($chartViews);
    const hours = @json($trafficByHour);
    const revenue = @json($chartRevenue);
    const funnel = @json($funnel);
    const categories = @json($categoryPerformance->pluck('views', 'category_name'));
    const devices = @json($deviceBreakdown->pluck('sessions', 'device'));
    const sources = @json($trafficSources->pluck('sessions', 'traffic_source'));
    const weekday = @json(collect($salesByWeekday)->pluck('orders'));
    const weekdayLabels = @json([
        __('analytics.mon'), __('analytics.tue'), __('analytics.wed'), __('analytics.thu'),
        __('analytics.fri'), __('analytics.sat'), __('analytics.sun'),
    ]);

    if (document.getElementById('chartTraffic')) {
        new Chart(document.getElementById('chartTraffic'), {
            type: 'line',
            data: {
                labels: traffic.labels,
                datasets: [
                    { label: '{{ __('analytics.page_views') }}', data: traffic.pageViews, borderColor: '#0d6efd', tension: 0.3 },
                    { label: '{{ __('analytics.product_views') }}', data: traffic.productViews, borderColor: '#198754', tension: 0.3 },
                    { label: '{{ __('analytics.orders') }}', data: traffic.orders, borderColor: '#fd7e14', tension: 0.3 },
                ]
            },
            options: { responsive: true, maintainAspectRatio: true }
        });
    }
    if (document.getElementById('chartHours')) {
        new Chart(document.getElementById('chartHours'), {
            type: 'bar',
            data: {
                labels: Array.from({length:24}, (_,i)=>i+':00'),
                datasets: [{ label: '{{ __('analytics.page_views') }}', data: hours, backgroundColor: '#6c757d' }]
            },
            options: { responsive: true, plugins: { legend: { display: false } } }
        });
    }
    if (document.getElementById('chartRevenue')) {
        new Chart(document.getElementById('chartRevenue'), {
            type: 'bar',
            data: {
                labels: revenue.labels,
                datasets: [{ label: '{{ __('analytics.revenue') }} (EGP)', data: revenue.revenue, backgroundColor: '#0d6efd' }]
            },
            options: { responsive: true }
        });
    }
    if (document.getElementById('chartFunnel')) {
        new Chart(document.getElementById('chartFunnel'), {
            type: 'bar',
            data: {
                labels: Object.keys(funnel),
                datasets: [{ label: '{{ __('analytics.sessions') }}', data: Object.values(funnel), backgroundColor: '#20c997' }]
            },
            options: { indexAxis: 'y', responsive: true }
        });
    }
    if (document.getElementById('chartCategories')) {
        new Chart(document.getElementById('chartCategories'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(categories),
                datasets: [{ data: Object.values(categories), backgroundColor: ['#0d6efd','#6610f2','#6f42c1','#d63384','#fd7e14','#198754'] }]
            }
        });
    }
    if (document.getElementById('chartDevices')) {
        new Chart(document.getElementById('chartDevices'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(devices),
                datasets: [{ data: Object.values(devices), backgroundColor: ['#0d6efd','#198754','#fd7e14','#6c757d'] }]
            }
        });
    }
    if (document.getElementById('chartSources')) {
        new Chart(document.getElementById('chartSources'), {
            type: 'bar',
            data: {
                labels: Object.keys(sources),
                datasets: [{ label: '{{ __('analytics.sessions') }}', data: Object.values(sources), backgroundColor: '#6610f2' }]
            },
            options: { plugins: { legend: { display: false } } }
        });
    }
    if (document.getElementById('chartWeekday')) {
        new Chart(document.getElementById('chartWeekday'), {
            type: 'bar',
            data: {
                labels: weekdayLabels,
                datasets: [{ label: '{{ __('analytics.orders') }}', data: weekday, backgroundColor: '#20c997' }]
            },
            options: { plugins: { legend: { display: false } } }
        });
    }
    @endif
})();
</script>

<x-admin.footer />
