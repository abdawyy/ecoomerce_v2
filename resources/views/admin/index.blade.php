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
    <div class="container-fluid">
        <div class="row pt-4">
            <div class="pagetitle mb-3">
                <h1>{{ __('dashboard.dashboard') }}</h1>
                <nav>
                    <ol class="breadcrumb d-flex {{ $isRtl ? 'text-end' : 'text-start' }} mb-0" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                        <li class="breadcrumb-item active">{{ __('dashboard.home') }}</li>
                    </ol>
                </nav>
            </div>

            {{-- Action strip --}}
            <div class="col-12 mb-4">
                <div class="action-strip admin-card d-flex flex-wrap gap-2 align-items-center p-3">
                    <a href="{{ route('order.list') }}?status=Pending" class="action-strip-btn btn btn-sm btn-warning">
                        <i class="bi bi-cart3 me-1"></i>
                        {{ __('dashboard.pending_orders') }}
                        <span class="badge bg-dark ms-1" id="pending-count">{{ $pendingOrders }}</span>
                    </a>
                    <a href="{{ route('admin.contact.list') }}" class="action-strip-btn btn btn-sm btn-danger">
                        <i class="bi bi-envelope me-1"></i>
                        {{ __('dashboard.unread_messages') }}
                        <span class="badge bg-light text-dark ms-1" id="unread-messages-count">{{ $notify['unread_messages'] ?? 0 }}</span>
                    </a>
                    <a href="{{ route('products.edit') }}" class="action-strip-btn btn btn-sm btn-dark">
                        <i class="bi bi-plus-lg me-1"></i>{{ __('dashboard.quick_add_product') }}
                    </a>
                    <a href="{{ route('admin.analytics') }}" class="action-strip-btn btn btn-sm btn-outline-dark">
                        <i class="bi bi-graph-up me-1"></i>{{ __('dashboard.full_analytics') }}
                    </a>
                    <a href="{{ route('admin.settings.branding') }}" class="action-strip-btn btn btn-sm btn-outline-secondary">
                        <i class="bi bi-palette me-1"></i>{{ __('dashboard.manage_home_images') }}
                    </a>
                    <span class="action-strip-live ms-auto badge bg-success fs-6 px-3 py-2">
                        <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>
                        {{ __('dashboard.live_now') }}: <span id="live-count">{{ $liveCount }}</span>
                    </span>
                </div>
            </div>

            {{-- KPI cards --}}
            <div class="row g-3 mb-4">
                <div class="col-6 col-lg-3">
                    <div class="admin-card card h-100 border-success border-opacity-25">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="text-muted mb-1">{{ __('dashboard.live_now') }}</h6>
                                    <h3 class="mb-0 text-success" id="live-count-card">{{ $liveCount }}</h3>
                                </div>
                                <span class="admin-kpi-icon text-success bg-success bg-opacity-10"><i class="bi bi-broadcast"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="admin-card card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="text-muted mb-1">{{ __('dashboard.today_revenue') }}</h6>
                                    <h3 class="mb-0" id="today-revenue">{{ number_format($todaySales['revenue'], 0) }}</h3>
                                    <small class="text-muted">{{ __('dashboard.currency') }}</small>
                                    {!! $changeBadge($todayKpis['change']['revenue'] ?? null) !!}
                                </div>
                                <span class="admin-kpi-icon text-primary bg-primary bg-opacity-10"><i class="bi bi-currency-dollar"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="admin-card card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="text-muted mb-1">{{ __('dashboard.today_orders') }}</h6>
                                    <h3 class="mb-0" id="today-orders">{{ $todaySales['orders'] }}</h3>
                                    {!! $changeBadge($todayKpis['change']['orders'] ?? null) !!}
                                </div>
                                <span class="admin-kpi-icon text-info bg-info bg-opacity-10"><i class="bi bi-bag-check"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="admin-card card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="text-muted mb-1">{{ __('dashboard.pending_orders') }}</h6>
                                    <h3 class="mb-0 text-warning" id="pending-count-card">{{ $pendingOrders }}</h3>
                                </div>
                                <span class="admin-kpi-icon text-warning bg-warning bg-opacity-10"><i class="bi bi-hourglass-split"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Charts --}}
            <div class="row g-3 mb-4">
                <div class="col-lg-8">
                    <div class="admin-card card h-100">
                        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                            <span>{{ __('dashboard.last_7_days') }} — {{ __('dashboard.revenue_chart') }}</span>
                            <a href="{{ route('admin.analytics') }}" class="btn btn-sm btn-outline-primary">{{ __('dashboard.full_analytics') }}</a>
                        </div>
                        <div class="card-body">
                            <canvas id="chartRevenue" height="100"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="admin-card card h-100">
                        <div class="card-header bg-white border-bottom">{{ __('dashboard.traffic_chart') }}</div>
                        <div class="card-body">
                            <canvas id="chartTraffic" height="160"></canvas>
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
                <div class="col-lg-8">
                    <div class="admin-card card">
                        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
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
                                        <span class="small text-muted text-nowrap">{{ $entry['at']?->diffForHumans() }}</span>
                                    </a>
                                </li>
                            @empty
                                <li class="list-group-item text-muted text-center py-4">{{ __('dashboard.no_activity') }}</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="admin-card card mb-3">
                        <div class="card-header bg-white border-bottom">{{ __('dashboard.quick_actions') }}</div>
                        <div class="card-body d-grid gap-2">
                            <a href="{{ route('order.list') }}?status=Pending" class="btn btn-outline-warning text-start">
                                <i class="bi bi-cart3 me-2"></i>{{ __('dashboard.manage_pending') }}
                            </a>
                            <a href="{{ route('admin.contact.list') }}" class="btn btn-outline-danger text-start">
                                <i class="bi bi-envelope me-2"></i>{{ __('dashboard.view_messages') }}
                            </a>
                            <a href="{{ route('products.edit') }}" class="btn btn-outline-dark text-start">
                                <i class="bi bi-box-seam me-2"></i>{{ __('dashboard.quick_add_product') }}
                            </a>
                            <a href="{{ route('discountCodes.edit') }}" class="btn btn-outline-secondary text-start">
                                <i class="bi bi-percent me-2"></i>{{ __('dashboard.quick_add_discount') }}
                            </a>
                            <a href="{{ route('users.list') }}" class="btn btn-outline-info text-start">
                                <i class="bi bi-people me-2"></i>{{ __('dashboard.view_users') }}
                                <span class="badge bg-info ms-1" id="new-users-count">{{ $notify['new_users'] ?? 0 }}</span>
                            </a>
                            <a href="{{ route('admin.guest.list') }}" class="btn btn-outline-info text-start">
                                <i class="bi bi-person-badge me-2"></i>{{ __('dashboard.view_guests') }}
                                <span class="badge bg-info ms-1" id="new-guests-count">{{ $notify['new_guests'] ?? 0 }}</span>
                            </a>
                        </div>
                    </div>
                    <div class="admin-card card">
                        <div class="card-body">
                            <h6 class="mb-2">{{ __('branding.home_images') }}</h6>
                            <p class="small text-muted mb-3">{{ __('branding.home_images_hint') }}</p>
                            <a href="{{ route('admin.settings.branding') }}" class="btn btn-outline-dark w-100">{{ __('dashboard.manage_home_images') }}</a>
                        </div>
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
    let lastOrderId = {{ $latestOrderId }};
    let lastUserId = {{ $latestUserId }};
    let lastGuestId = {{ $latestGuestId }};
    let lastMessageId = {{ $latestMessageId }};
    const statsUrl = @json(route('admin.dashboard.stats'));
    const toastOrder = @json(__('dashboard.new_order_toast'));
    const toastUser = @json(__('dashboard.new_user_toast'));
    const toastGuest = @json(__('dashboard.new_guest_toast'));
    const toastMessage = @json(__('dashboard.new_message_toast'));

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
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { ticks: { color: c.text }, grid: { color: c.grid } },
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
                    plugins: { legend: { position: 'bottom', labels: { color: c.text } } },
                    scales: {
                        x: { ticks: { color: c.text }, grid: { color: c.grid } },
                        y: { beginAtZero: true, ticks: { color: c.text }, grid: { color: c.grid } }
                    }
                }
            });
        }
    }

    buildCharts();
    window.addEventListener('admin-theme-changed', buildCharts);

    function setText(id, value) {
        document.querySelectorAll('#' + id).forEach(el => { el.textContent = value; });
    }

    function updateNavNotifyTotal(data) {
        const total = (data.pending_orders || 0) + (data.unread_messages || 0) + (data.new_users || 0) + (data.new_guests || 0);
        const badge = document.getElementById('nav-notify-total');
        if (!badge) return;
        if (total > 0) {
            badge.textContent = total > 99 ? '99+' : total;
            badge.classList.remove('d-none');
        } else {
            badge.classList.add('d-none');
        }
    }

    async function pollStats() {
        try {
            const qs = new URLSearchParams({
                since_order_id: lastOrderId,
                since_user_id: lastUserId,
                since_guest_id: lastGuestId,
                since_message_id: lastMessageId,
            });
            const res = await fetch(statsUrl + '?' + qs.toString(), { headers: { 'Accept': 'application/json' } });
            if (!res.ok) return;
            const data = await res.json();

            setText('live-count', data.live_count);
            setText('live-count-card', data.live_count);
            setText('pending-count', data.pending_orders);
            setText('pending-count-card', data.pending_orders);
            setText('unread-messages-count', data.unread_messages);
            setText('new-users-count', data.new_users);
            setText('new-guests-count', data.new_guests);
            setText('today-revenue', new Intl.NumberFormat().format(data.today_revenue));
            setText('today-orders', data.today_orders);
            setText('nav-pending-orders', data.pending_orders);
            setText('nav-unread-messages', data.unread_messages);
            updateNavNotifyTotal(data);

            if (Array.isArray(data.new_orders) && typeof toastr !== 'undefined') {
                data.new_orders.forEach(order => {
                    if (order.id > lastOrderId) {
                        toastr.info(toastOrder.replace(':id', order.id).replace(':amount', order.total));
                    }
                });
            }
            if (Array.isArray(data.new_users) && typeof toastr !== 'undefined') {
                data.new_users.forEach(user => {
                    if (user.id > lastUserId) {
                        toastr.success(toastUser.replace(':name', user.name));
                    }
                });
            }
            if (Array.isArray(data.new_guests) && typeof toastr !== 'undefined') {
                data.new_guests.forEach(guest => {
                    if (guest.id > lastGuestId) {
                        toastr.success(toastGuest.replace(':name', guest.name));
                    }
                });
            }
            if (Array.isArray(data.new_messages) && typeof toastr !== 'undefined') {
                data.new_messages.forEach(msg => {
                    if (msg.id > lastMessageId) {
                        toastr.warning(toastMessage.replace(':name', msg.name));
                    }
                });
            }

            if (data.latest_order_id > lastOrderId) lastOrderId = data.latest_order_id;
            if (data.latest_user_id > lastUserId) lastUserId = data.latest_user_id;
            if (data.latest_guest_id > lastGuestId) lastGuestId = data.latest_guest_id;
            if (data.latest_message_id > lastMessageId) lastMessageId = data.latest_message_id;
        } catch (e) {}
    }

    pollStats();
    setInterval(pollStats, 30000);
})();
</script>

<x-admin.footer />
