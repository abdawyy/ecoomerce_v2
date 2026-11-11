<x-admin.header />
<x-admin.aside />
<x-admin.navbar />

@php
    $locale = app()->getLocale();
    $isRtl = $locale === 'ar';
    $changeBadge = function ($pct) {
        if ($pct === null) {
            return '';
        }
        $up = $pct >= 0;
        $cls = $up ? 'text-success' : 'text-danger';
        $icon = $up ? '↑' : '↓';

        return '<small class="'.$cls.'">'.$icon.' '.abs($pct).'%</small>';
    };
@endphp

<main id="main">
    <div class="container-fluid admin-page pt-3 pt-md-4">
            <div class="pagetitle mb-3">
                <h1>{{ __('dashboard.dashboard') }}</h1>
                <nav>
                    <ol class="breadcrumb d-flex {{ $isRtl ? 'text-end' : 'text-start' }} mb-0" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                        <li class="breadcrumb-item active">{{ __('dashboard.home') }}</li>
                    </ol>
                </nav>
            </div>

            {{-- Action strip --}}
            <div class="action-strip admin-card d-flex flex-wrap gap-2 align-items-stretch align-items-md-center p-3 mb-4">
                    <a href="{{ route('order.list') }}?status=Pending" class="action-strip-btn btn btn-sm btn-warning">
                        <i class="bi bi-cart3 me-1"></i>
                        {{ __('dashboard.pending_orders') }}
                        <span class="badge bg-dark ms-1" id="pending-count" data-admin-notify="pending_orders">{{ $pendingOrders }}</span>
                    </a>
                    <a href="{{ route('admin.contact.list') }}" class="action-strip-btn btn btn-sm btn-danger">
                        <i class="bi bi-envelope me-1"></i>
                        {{ __('dashboard.unread_messages') }}
                        <span class="badge bg-light text-dark ms-1" id="unread-messages-count" data-admin-notify="unread_messages">{{ $notify['unread_messages'] ?? 0 }}</span>
                    </a>
                    <a href="{{ \App\Support\AdminListReturn::addUrl('products.edit', 'products') }}" class="action-strip-btn btn btn-sm btn-dark">
                        <i class="bi bi-plus-lg me-1"></i>{{ __('dashboard.quick_add_product') }}
                    </a>
                    <a href="{{ route('admin.analytics') }}" class="action-strip-btn btn btn-sm btn-outline-dark">
                        <i class="bi bi-graph-up me-1"></i>{{ __('dashboard.full_analytics') }}
                    </a>
                    <a href="{{ route('admin.settings.branding') }}#home-tiles" class="action-strip-btn btn btn-sm btn-outline-secondary">
                        <i class="bi bi-palette me-1"></i>{{ __('dashboard.manage_home_images') }}
                    </a>
                    <span class="action-strip-live ms-md-auto badge bg-success fs-6 px-3 py-2">
                        <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>
                        {{ __('dashboard.live_now') }}: <span id="live-count">{{ $liveCount }}</span>
                    </span>
            </div>

            {{-- KPI cards --}}
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="admin-card admin-kpi-card card h-100 border-success border-opacity-25">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div class="min-w-0">
                                    <h6 class="text-muted mb-1">{{ __('dashboard.live_now') }}</h6>
                                    <h3 class="mb-0 text-success text-break" id="live-count-card">{{ $liveCount }}</h3>
                                </div>
                                <span class="admin-kpi-icon text-success bg-success bg-opacity-10"><i class="bi bi-broadcast"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="admin-card admin-kpi-card card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div class="min-w-0">
                                    <h6 class="text-muted mb-1">{{ __('dashboard.today_revenue') }}</h6>
                                    <h3 class="mb-0 text-break" id="today-revenue">{{ number_format($todaySales['revenue'], 0) }}</h3>
                                    <small class="text-muted">{{ __('dashboard.currency') }}</small>
                                    {!! $changeBadge($todayKpis['change']['revenue'] ?? null) !!}
                                </div>
                                <span class="admin-kpi-icon text-primary bg-primary bg-opacity-10"><i class="bi bi-currency-dollar"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="admin-card admin-kpi-card card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div class="min-w-0">
                                    <h6 class="text-muted mb-1">{{ __('dashboard.today_orders') }}</h6>
                                    <h3 class="mb-0 text-break" id="today-orders">{{ $todaySales['orders'] }}</h3>
                                    {!! $changeBadge($todayKpis['change']['orders'] ?? null) !!}
                                </div>
                                <span class="admin-kpi-icon text-info bg-info bg-opacity-10"><i class="bi bi-bag-check"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="admin-card admin-kpi-card card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div class="min-w-0">
                                    <h6 class="text-muted mb-1">{{ __('dashboard.pending_orders') }}</h6>
                                    <h3 class="mb-0 text-warning text-break" id="pending-count-card" data-admin-notify="pending_orders">{{ $pendingOrders }}</h3>
                                </div>
                                <span class="admin-kpi-icon text-warning bg-warning bg-opacity-10"><i class="bi bi-hourglass-split"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Charts --}}
            <div class="row g-3 mb-4">
                <div class="col-12 col-lg-8 min-w-0">
                    <div class="admin-card card h-100">
                        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <span>{{ __('dashboard.last_7_days') }} — {{ __('dashboard.revenue_chart') }}</span>
                            <a href="{{ route('admin.analytics') }}" class="btn btn-sm btn-outline-primary">{{ __('dashboard.full_analytics') }}</a>
                        </div>
                        <div class="card-body">
                            <div class="admin-chart-wrap">
                                <canvas id="chartRevenue"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 min-w-0">
                    <div class="admin-card card h-100">
                        <div class="card-header bg-white border-bottom">{{ __('dashboard.traffic_chart') }}</div>
                        <div class="card-body">
                            <div class="admin-chart-wrap admin-chart-wrap--sm">
                                <canvas id="chartTraffic"></canvas>
                            </div>
                            <div class="mt-3 small text-muted">
                                <div>{{ __('dashboard.page_views_today') }}: <strong>{{ number_format($todayKpis['current']['page_views']) }}</strong></div>
                                <div>{{ __('dashboard.num_orders') }} (7d): <strong>{{ $weekSales['orders'] }}</strong></div>
                                <div>{{ __('dashboard.users') }}: <strong>{{ number_format($totalUsers) }}</strong></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Activity feed + quick actions --}}
            <div class="row g-3">
                <div class="col-12 col-lg-8 min-w-0">
                    <div class="admin-card card">
                        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <span><i class="bi bi-activity me-1"></i>{{ __('dashboard.activity_feed') }}</span>
                            <a href="{{ route('order.list') }}" class="btn btn-sm btn-outline-secondary">{{ __('dashboard.view_all_orders') }}</a>
                        </div>
                        <ul class="list-group list-group-flush activity-feed" id="activity-feed">
                            @forelse ($activityFeed as $entry)
                                <li class="list-group-item activity-feed-item">
                                    <a href="{{ $entry['url'] }}" class="d-flex gap-3 align-items-start text-decoration-none text-body">
                                        <span class="activity-feed-icon bg-{{ $entry['variant'] }} bg-opacity-10 text-{{ $entry['variant'] }}">
                                            <i class="bi {{ $entry['icon'] }}"></i>
                                        </span>
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="fw-semibold">{{ $entry['title'] }}</div>
                                            <div class="small text-muted text-truncate">{{ $entry['subtitle'] }}</div>
                                        </div>
                                        <span class="activity-feed-time small text-muted text-nowrap">{{ $entry['at']?->diffForHumans() }}</span>
                                    </a>
                                </li>
                            @empty
                                <li class="list-group-item text-muted text-center py-4">{{ __('dashboard.no_activity') }}</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <div class="col-12 col-lg-4 min-w-0">
                    <div class="admin-card card mb-3">
                        <div class="card-header bg-white border-bottom">{{ __('dashboard.quick_actions') }}</div>
                        <div class="card-body d-grid gap-2">
                            <a href="{{ route('order.list') }}?status=Pending" class="btn btn-outline-warning text-start">
                                <i class="bi bi-cart3 me-2"></i>{{ __('dashboard.manage_pending') }}
                            </a>
                            <a href="{{ route('admin.contact.list') }}" class="btn btn-outline-danger text-start">
                                <i class="bi bi-envelope me-2"></i>{{ __('dashboard.view_messages') }}
                            </a>
                            <a href="{{ \App\Support\AdminListReturn::addUrl('products.edit', 'products') }}" class="btn btn-outline-dark text-start">
                                <i class="bi bi-box-seam me-2"></i>{{ __('dashboard.quick_add_product') }}
                            </a>
                            <a href="{{ \App\Support\AdminListReturn::addUrl('discountCodes.edit', 'discountCodes') }}" class="btn btn-outline-secondary text-start">
                                <i class="bi bi-percent me-2"></i>{{ __('dashboard.quick_add_discount') }}
                            </a>
                            <a href="{{ route('users.list') }}?new=1" class="btn btn-outline-info text-start">
                                <i class="bi bi-people me-2"></i>{{ __('dashboard.view_users') }}
                                <span class="badge bg-info ms-1" id="new-users-count" data-admin-notify="new_users">{{ $notify['new_users'] ?? 0 }}</span>
                            </a>
                            <a href="{{ route('admin.guest.list') }}?new=1" class="btn btn-outline-info text-start">
                                <i class="bi bi-person-badge me-2"></i>{{ __('dashboard.view_guests') }}
                                <span class="badge bg-info ms-1" id="new-guests-count" data-admin-notify="new_guests">{{ $notify['new_guests'] ?? 0 }}</span>
                            </a>
                        </div>
                    </div>
                    <div class="admin-card card">
                        <div class="card-body">
                            <h6 class="mb-2">{{ __('branding.home_images') }}</h6>
                            <p class="small text-muted mb-3">{{ __('branding.home_images_hint') }}</p>
                            <a href="{{ route('admin.settings.branding') }}#home-tiles" class="btn btn-outline-dark w-100">{{ __('dashboard.manage_home_images') }}</a>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    const revenue = @json($chartRevenue);
    const traffic = @json($chartViews);

    let revenueChart = null;
    let trafficChart = null;

    function chartScaleColors() {
        const c = window.AdminTheme ? window.AdminTheme.chartColors() : {
            grid: 'rgba(0,0,0,0.08)', text: '#6b7280',
            revenueLine: '#198754', revenueFill: 'rgba(25,135,84,0.1)',
            barPrimary: '#0d6efd', barSecondary: '#6c757d',
        };
        return c;
    }

    function buildCharts() {
        const c = chartScaleColors();

        if (document.getElementById('chartRevenue')) {
            if (revenueChart) revenueChart.destroy();
            revenueChart = new Chart(document.getElementById('chartRevenue'), {
                type: 'line',
                data: {
                    labels: revenue.labels,
                    datasets: [{
                        label: @json(__('dashboard.revenue_chart')),
                        data: revenue.revenue,
                        borderColor: c.revenueLine,
                        backgroundColor: c.revenueFill,
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { ticks: { color: c.text, maxRotation: 0, autoSkip: true }, grid: { color: c.grid } },
                        y: { beginAtZero: true, ticks: { color: c.text }, grid: { color: c.grid } }
                    }
                }
            });
        }

        if (document.getElementById('chartTraffic')) {
            if (trafficChart) trafficChart.destroy();
            trafficChart = new Chart(document.getElementById('chartTraffic'), {
                type: 'bar',
                data: {
                    labels: traffic.labels,
                    datasets: [
                        { label: 'Pages', data: traffic.pageViews, backgroundColor: c.barPrimary },
                        { label: 'Products', data: traffic.productViews, backgroundColor: c.barSecondary }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom', labels: { color: c.text, boxWidth: 12 } } },
                    scales: {
                        x: { ticks: { color: c.text, maxRotation: 0, autoSkip: true }, grid: { color: c.grid } },
                        y: { beginAtZero: true, ticks: { color: c.text }, grid: { color: c.grid } }
                    }
                }
            });
        }
    }

    buildCharts();
    window.addEventListener('admin-theme-changed', buildCharts);
})();
</script>

<x-admin.footer />
