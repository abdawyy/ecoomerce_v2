<?php if (isset($component)) { $__componentOriginal45d9cbba1e84739af2366cafaf311004 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal45d9cbba1e84739af2366cafaf311004 = $attributes; } ?>
<?php $component = App\View\Components\Admin\Header::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\Admin\Header::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal45d9cbba1e84739af2366cafaf311004)): ?>
<?php $attributes = $__attributesOriginal45d9cbba1e84739af2366cafaf311004; ?>
<?php unset($__attributesOriginal45d9cbba1e84739af2366cafaf311004); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal45d9cbba1e84739af2366cafaf311004)): ?>
<?php $component = $__componentOriginal45d9cbba1e84739af2366cafaf311004; ?>
<?php unset($__componentOriginal45d9cbba1e84739af2366cafaf311004); ?>
<?php endif; ?>
<?php if (isset($component)) { $__componentOriginald417e0638ea790d8a6d32bd501701baa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald417e0638ea790d8a6d32bd501701baa = $attributes; } ?>
<?php $component = App\View\Components\Admin\Aside::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin.aside'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\Admin\Aside::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald417e0638ea790d8a6d32bd501701baa)): ?>
<?php $attributes = $__attributesOriginald417e0638ea790d8a6d32bd501701baa; ?>
<?php unset($__attributesOriginald417e0638ea790d8a6d32bd501701baa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald417e0638ea790d8a6d32bd501701baa)): ?>
<?php $component = $__componentOriginald417e0638ea790d8a6d32bd501701baa; ?>
<?php unset($__componentOriginald417e0638ea790d8a6d32bd501701baa); ?>
<?php endif; ?>
<?php if (isset($component)) { $__componentOriginal64e2265cfb81aa59d135283195bf883b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal64e2265cfb81aa59d135283195bf883b = $attributes; } ?>
<?php $component = App\View\Components\Admin\Navbar::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin.navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\Admin\Navbar::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal64e2265cfb81aa59d135283195bf883b)): ?>
<?php $attributes = $__attributesOriginal64e2265cfb81aa59d135283195bf883b; ?>
<?php unset($__attributesOriginal64e2265cfb81aa59d135283195bf883b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal64e2265cfb81aa59d135283195bf883b)): ?>
<?php $component = $__componentOriginal64e2265cfb81aa59d135283195bf883b; ?>
<?php unset($__componentOriginal64e2265cfb81aa59d135283195bf883b); ?>
<?php endif; ?>

<?php
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
?>

<main id="main">
    <div class="container-fluid">
        <div class="row pt-4">
            <div class="pagetitle mb-3">
                <h1><?php echo e(__('dashboard.dashboard')); ?></h1>
                <nav>
                    <ol class="breadcrumb d-flex <?php echo e($isRtl ? 'text-end' : 'text-start'); ?> mb-0" dir="<?php echo e($isRtl ? 'rtl' : 'ltr'); ?>">
                        <li class="breadcrumb-item active"><?php echo e(__('dashboard.home')); ?></li>
                    </ol>
                </nav>
            </div>

            
            <div class="col-12 mb-4">
                <div class="action-strip admin-card d-flex flex-wrap gap-2 align-items-center p-3">
                    <a href="<?php echo e(route('order.list')); ?>?status=Pending" class="action-strip-btn btn btn-sm btn-warning">
                        <i class="bi bi-cart3 me-1"></i>
                        <?php echo e(__('dashboard.pending_orders')); ?>

                        <span class="badge bg-dark ms-1" id="pending-count"><?php echo e($pendingOrders); ?></span>
                    </a>
                    <a href="<?php echo e(route('admin.contact.list')); ?>" class="action-strip-btn btn btn-sm btn-danger">
                        <i class="bi bi-envelope me-1"></i>
                        <?php echo e(__('dashboard.unread_messages')); ?>

                        <span class="badge bg-light text-dark ms-1" id="unread-messages-count"><?php echo e($notify['unread_messages'] ?? 0); ?></span>
                    </a>
                    <a href="<?php echo e(route('products.edit')); ?>" class="action-strip-btn btn btn-sm btn-dark">
                        <i class="bi bi-plus-lg me-1"></i><?php echo e(__('dashboard.quick_add_product')); ?>

                    </a>
                    <a href="<?php echo e(route('admin.analytics')); ?>" class="action-strip-btn btn btn-sm btn-outline-dark">
                        <i class="bi bi-graph-up me-1"></i><?php echo e(__('dashboard.full_analytics')); ?>

                    </a>
                    <a href="<?php echo e(route('admin.settings.branding')); ?>#home-tiles" class="action-strip-btn btn btn-sm btn-outline-secondary">
                        <i class="bi bi-palette me-1"></i><?php echo e(__('dashboard.manage_home_images')); ?>

                    </a>
                    <span class="action-strip-live ms-auto badge bg-success fs-6 px-3 py-2">
                        <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>
                        <?php echo e(__('dashboard.live_now')); ?>: <span id="live-count"><?php echo e($liveCount); ?></span>
                    </span>
                </div>
            </div>

            
            <div class="row g-3 mb-4">
                <div class="col-6 col-lg-3">
                    <div class="admin-card card h-100 border-success border-opacity-25">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="text-muted mb-1"><?php echo e(__('dashboard.live_now')); ?></h6>
                                    <h3 class="mb-0 text-success" id="live-count-card"><?php echo e($liveCount); ?></h3>
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
                                    <h6 class="text-muted mb-1"><?php echo e(__('dashboard.today_revenue')); ?></h6>
                                    <h3 class="mb-0" id="today-revenue"><?php echo e(number_format($todaySales['revenue'], 0)); ?></h3>
                                    <small class="text-muted"><?php echo e(__('dashboard.currency')); ?></small>
                                    <?php echo $changeBadge($todayKpis['change']['revenue'] ?? null); ?>

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
                                    <h6 class="text-muted mb-1"><?php echo e(__('dashboard.today_orders')); ?></h6>
                                    <h3 class="mb-0" id="today-orders"><?php echo e($todaySales['orders']); ?></h3>
                                    <?php echo $changeBadge($todayKpis['change']['orders'] ?? null); ?>

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
                                    <h6 class="text-muted mb-1"><?php echo e(__('dashboard.pending_orders')); ?></h6>
                                    <h3 class="mb-0 text-warning" id="pending-count-card"><?php echo e($pendingOrders); ?></h3>
                                </div>
                                <span class="admin-kpi-icon text-warning bg-warning bg-opacity-10"><i class="bi bi-hourglass-split"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="row g-3 mb-4">
                <div class="col-lg-8">
                    <div class="admin-card card h-100">
                        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                            <span><?php echo e(__('dashboard.last_7_days')); ?> — <?php echo e(__('dashboard.revenue_chart')); ?></span>
                            <a href="<?php echo e(route('admin.analytics')); ?>" class="btn btn-sm btn-outline-primary"><?php echo e(__('dashboard.full_analytics')); ?></a>
                        </div>
                        <div class="card-body">
                            <canvas id="chartRevenue" height="100"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="admin-card card h-100">
                        <div class="card-header bg-white border-bottom"><?php echo e(__('dashboard.traffic_chart')); ?></div>
                        <div class="card-body">
                            <canvas id="chartTraffic" height="160"></canvas>
                            <div class="mt-3 small text-muted">
                                <div><?php echo e(__('dashboard.page_views_today')); ?>: <strong><?php echo e(number_format($todayKpis['current']['page_views'])); ?></strong></div>
                                <div><?php echo e(__('dashboard.num_orders')); ?> (7d): <strong><?php echo e($weekSales['orders']); ?></strong></div>
                                <div><?php echo e(__('dashboard.users')); ?>: <strong><?php echo e(number_format($totalUsers)); ?></strong></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="row g-3">
                <div class="col-lg-8">
                    <div class="admin-card card">
                        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-activity me-1"></i><?php echo e(__('dashboard.activity_feed')); ?></span>
                            <a href="<?php echo e(route('order.list')); ?>" class="btn btn-sm btn-outline-secondary"><?php echo e(__('dashboard.view_all_orders')); ?></a>
                        </div>
                        <ul class="list-group list-group-flush activity-feed" id="activity-feed">
                            <?php $__empty_1 = true; $__currentLoopData = $activityFeed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <li class="list-group-item activity-feed-item">
                                    <a href="<?php echo e($entry['url']); ?>" class="d-flex gap-3 align-items-start text-decoration-none text-body">
                                        <span class="activity-feed-icon bg-<?php echo e($entry['variant']); ?> bg-opacity-10 text-<?php echo e($entry['variant']); ?>">
                                            <i class="bi <?php echo e($entry['icon']); ?>"></i>
                                        </span>
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="fw-semibold"><?php echo e($entry['title']); ?></div>
                                            <div class="small text-muted text-truncate"><?php echo e($entry['subtitle']); ?></div>
                                        </div>
                                        <span class="small text-muted text-nowrap"><?php echo e($entry['at']?->diffForHumans()); ?></span>
                                    </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <li class="list-group-item text-muted text-center py-4"><?php echo e(__('dashboard.no_activity')); ?></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="admin-card card mb-3">
                        <div class="card-header bg-white border-bottom"><?php echo e(__('dashboard.quick_actions')); ?></div>
                        <div class="card-body d-grid gap-2">
                            <a href="<?php echo e(route('order.list')); ?>?status=Pending" class="btn btn-outline-warning text-start">
                                <i class="bi bi-cart3 me-2"></i><?php echo e(__('dashboard.manage_pending')); ?>

                            </a>
                            <a href="<?php echo e(route('admin.contact.list')); ?>" class="btn btn-outline-danger text-start">
                                <i class="bi bi-envelope me-2"></i><?php echo e(__('dashboard.view_messages')); ?>

                            </a>
                            <a href="<?php echo e(route('products.edit')); ?>" class="btn btn-outline-dark text-start">
                                <i class="bi bi-box-seam me-2"></i><?php echo e(__('dashboard.quick_add_product')); ?>

                            </a>
                            <a href="<?php echo e(route('discountCodes.edit')); ?>" class="btn btn-outline-secondary text-start">
                                <i class="bi bi-percent me-2"></i><?php echo e(__('dashboard.quick_add_discount')); ?>

                            </a>
                            <a href="<?php echo e(route('users.list')); ?>" class="btn btn-outline-info text-start">
                                <i class="bi bi-people me-2"></i><?php echo e(__('dashboard.view_users')); ?>

                                <span class="badge bg-info ms-1" id="new-users-count"><?php echo e($notify['new_users'] ?? 0); ?></span>
                            </a>
                            <a href="<?php echo e(route('admin.guest.list')); ?>" class="btn btn-outline-info text-start">
                                <i class="bi bi-person-badge me-2"></i><?php echo e(__('dashboard.view_guests')); ?>

                                <span class="badge bg-info ms-1" id="new-guests-count"><?php echo e($notify['new_guests'] ?? 0); ?></span>
                            </a>
                        </div>
                    </div>
                    <div class="admin-card card">
                        <div class="card-body">
                            <h6 class="mb-2"><?php echo e(__('branding.home_images')); ?></h6>
                            <p class="small text-muted mb-3"><?php echo e(__('branding.home_images_hint')); ?></p>
                            <a href="<?php echo e(route('admin.settings.branding')); ?>#home-tiles" class="btn btn-outline-dark w-100"><?php echo e(__('dashboard.manage_home_images')); ?></a>
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
    const revenue = <?php echo json_encode($chartRevenue, 15, 512) ?>;
    const traffic = <?php echo json_encode($chartViews, 15, 512) ?>;
    let lastOrderId = <?php echo e($latestOrderId); ?>;
    let lastUserId = <?php echo e($latestUserId); ?>;
    let lastGuestId = <?php echo e($latestGuestId); ?>;
    let lastMessageId = <?php echo e($latestMessageId); ?>;
    const statsUrl = <?php echo json_encode(route('admin.dashboard.stats'), 15, 512) ?>;
    const toastOrder = <?php echo json_encode(__('dashboard.new_order_toast'), 15, 512) ?>;
    const toastUser = <?php echo json_encode(__('dashboard.new_user_toast'), 15, 512) ?>;
    const toastGuest = <?php echo json_encode(__('dashboard.new_guest_toast'), 15, 512) ?>;
    const toastMessage = <?php echo json_encode(__('dashboard.new_message_toast'), 15, 512) ?>;

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
                        label: <?php echo json_encode(__('dashboard.revenue_chart'), 15, 512) ?>,
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

<?php if (isset($component)) { $__componentOriginalb8e9be121ac5809d76d4768b3abc0902 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb8e9be121ac5809d76d4768b3abc0902 = $attributes; } ?>
<?php $component = App\View\Components\Admin\Footer::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin.footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\Admin\Footer::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb8e9be121ac5809d76d4768b3abc0902)): ?>
<?php $attributes = $__attributesOriginalb8e9be121ac5809d76d4768b3abc0902; ?>
<?php unset($__attributesOriginalb8e9be121ac5809d76d4768b3abc0902); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb8e9be121ac5809d76d4768b3abc0902)): ?>
<?php $component = $__componentOriginalb8e9be121ac5809d76d4768b3abc0902; ?>
<?php unset($__componentOriginalb8e9be121ac5809d76d4768b3abc0902); ?>
<?php endif; ?>
<?php /**PATH C:\hayah\resources\views/admin/index.blade.php ENDPATH**/ ?>