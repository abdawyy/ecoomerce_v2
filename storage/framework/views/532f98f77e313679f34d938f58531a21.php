<?php
    $changeBadge = function ($pct) {
        if ($pct === null) return '';
        $up = $pct >= 0;
        $cls = $up ? 'text-success' : 'text-danger';
        $icon = $up ? '↑' : '↓';
        return '<small class="'.$cls.'">'.$icon.' '.abs($pct).'%</small>';
    };
    $query = request()->query();
    $tabUrl = fn ($t) => '?' . http_build_query(array_merge($query, ['tab' => $t]));
?>

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

<main id="main">
    <div class="container-fluid">
        <div class="row pt-4">
            <div class="pagetitle d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div>
                    <h1><?php echo e(__('analytics.title')); ?></h1>
                    <p class="text-muted mb-0"><?php echo e($start->format('Y-m-d')); ?> — <?php echo e($end->format('Y-m-d')); ?></p>
                </div>
                <div id="live-badge" class="badge bg-success fs-6 px-3 py-2">
                    <?php echo e(__('analytics.live_now')); ?>: <span id="live-count"><?php echo e($liveCount); ?></span>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" class="row g-2 align-items-end">
                        <input type="hidden" name="tab" value="<?php echo e($tab); ?>">
                        <div class="col-md-3">
                            <label class="form-label"><?php echo e(__('analytics.range')); ?></label>
                            <select name="range" class="form-select" onchange="this.form.submit()">
                                <?php $__currentLoopData = ['today','yesterday','last_7','last_30','this_month','last_month','this_year','last_year','all','custom']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $preset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($preset); ?>" <?php if($range === $preset): echo 'selected'; endif; ?>><?php echo e(__('analytics.preset_'.$preset)); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <?php if($range === 'custom'): ?>
                            <div class="col-md-2"><input type="date" name="from" class="form-control" value="<?php echo e($from); ?>"></div>
                            <div class="col-md-2"><input type="date" name="to" class="form-control" value="<?php echo e($to); ?>"></div>
                        <?php endif; ?>
                        <div class="col-md-2">
                            <a href="<?php echo e(route('admin.analytics.export', array_merge(request()->query(), ['type' => $tab === 'customers' ? 'customers' : 'products']))); ?>" class="btn btn-outline-secondary w-100"><?php echo e(__('analytics.export')); ?></a>
                        </div>
                    </form>
                </div>
            </div>

            <ul class="nav nav-tabs mb-3 flex-wrap">
                <?php $__currentLoopData = ['overview','products','traffic','categories','sales','customers','live']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="nav-item"><a class="nav-link <?php echo e($tab === $t ? 'active' : ''); ?>" href="<?php echo e($tabUrl($t)); ?>"><?php echo e(__('analytics.tab_'.$t)); ?></a></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>

            <?php if(in_array($tab, ['overview', 'products', 'traffic', 'categories', 'sales', 'customers'])): ?>
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card h-100"><div class="card-body">
                            <h6 class="text-muted"><?php echo e(__('analytics.page_views')); ?></h6>
                            <h3 class="mb-0"><?php echo e(number_format($pageViews)); ?></h3>
                            <?php echo $changeBadge($change['page_views'] ?? 0); ?>

                        </div></div>
                    </div>
                    <div class="col-md-3">
                        <div class="card h-100"><div class="card-body">
                            <h6 class="text-muted"><?php echo e(__('analytics.product_views')); ?></h6>
                            <h3 class="mb-0"><?php echo e(number_format($productViews)); ?></h3>
                            <?php echo $changeBadge($change['product_views'] ?? 0); ?>

                        </div></div>
                    </div>
                    <div class="col-md-3">
                        <div class="card h-100"><div class="card-body">
                            <h6 class="text-muted"><?php echo e(__('analytics.uniques')); ?></h6>
                            <h3 class="mb-0"><?php echo e(number_format($uniqueVisitors)); ?></h3>
                            <?php echo $changeBadge($change['unique_visitors'] ?? 0); ?>

                        </div></div>
                    </div>
                    <div class="col-md-3">
                        <div class="card h-100"><div class="card-body">
                            <h6 class="text-muted"><?php echo e(__('analytics.revenue')); ?></h6>
                            <h3 class="mb-0"><?php echo e(number_format($sales['revenue'], 2)); ?> EGP</h3>
                            <?php echo $changeBadge($change['revenue'] ?? 0); ?>

                        </div></div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($tab === 'overview' || $tab === 'traffic'): ?>
                <div class="row g-3 mb-4">
                    <div class="col-lg-8">
                        <div class="card h-100"><div class="card-header"><?php echo e(__('analytics.chart_traffic')); ?></div>
                            <div class="card-body"><canvas id="chartTraffic" height="120"></canvas></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card h-100"><div class="card-header"><?php echo e(__('analytics.chart_hours')); ?></div>
                            <div class="card-body"><canvas id="chartHours" height="120"></canvas></div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($tab === 'overview' || $tab === 'sales'): ?>
                <div class="row g-3 mb-4">
                    <div class="col-lg-6">
                        <div class="card"><div class="card-header"><?php echo e(__('analytics.chart_revenue')); ?></div>
                            <div class="card-body"><canvas id="chartRevenue" height="120"></canvas></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card"><div class="card-header"><?php echo e(__('analytics.funnel')); ?></div>
                            <div class="card-body"><canvas id="chartFunnel" height="120"></canvas></div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($tab === 'overview' || $tab === 'products'): ?>
                <div class="card mb-4">
                    <div class="card-header"><?php echo e(__('analytics.top_products')); ?></div>
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead><tr><th>#</th><th><?php echo e(__('analytics.product')); ?></th><th><?php echo e(__('analytics.views')); ?></th><th><?php echo e(__('analytics.uniques')); ?></th></tr></thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($i + 1); ?></td>
                                        <td><?php if($row->product): ?><a href="<?php echo e(route('admin.analytics.product', $row->product_id)); ?>?range=<?php echo e($range); ?>"><?php echo e($row->product->name); ?></a><?php else: ?> #<?php echo e($row->product_id); ?> <?php endif; ?></td>
                                        <td><?php echo e($row->views); ?></td>
                                        <td><?php echo e($row->uniques); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr><td colspan="4" class="text-center text-muted"><?php echo e(__('analytics.no_data')); ?></td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php if($windowShoppers->isNotEmpty()): ?>
                    <div class="card mb-4 border-warning">
                        <div class="card-header"><?php echo e(__('analytics.window_shoppers')); ?></div>
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead><tr><th><?php echo e(__('analytics.product')); ?></th><th><?php echo e(__('analytics.views')); ?></th><th><?php echo e(__('analytics.units_sold')); ?></th></tr></thead>
                                <tbody>
                                    <?php $__currentLoopData = $windowShoppers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($row->product?->name ?? '#'.$row->product_id); ?></td>
                                            <td><?php echo e($row->views); ?></td>
                                            <td><?php echo e($row->orders_qty); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if($tab === 'traffic'): ?>
                <div class="row g-3">
                    <div class="col-lg-6">
                        <div class="card"><div class="card-header"><?php echo e(__('analytics.top_pages')); ?></div>
                            <table class="table mb-0">
                                <thead><tr><th><?php echo e(__('analytics.page')); ?></th><th><?php echo e(__('analytics.views')); ?></th><th><?php echo e(__('analytics.uniques')); ?></th></tr></thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $topPages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr><td><?php echo e($p->page_key); ?></td><td><?php echo e($p->views); ?></td><td><?php echo e($p->uniques); ?></td></tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr><td colspan="3" class="text-muted text-center"><?php echo e(__('analytics.no_data')); ?></td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card"><div class="card-header"><?php echo e(__('analytics.locales')); ?></div>
                            <table class="table mb-0">
                                <thead><tr><th><?php echo e(__('analytics.locale')); ?></th><th><?php echo e(__('analytics.views')); ?></th></tr></thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $localeBreakdown; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr><td><?php echo e(strtoupper($l->locale ?? '?')); ?></td><td><?php echo e($l->views); ?></td></tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr><td colspan="2" class="text-muted text-center"><?php echo e(__('analytics.no_data')); ?></td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($tab === 'categories'): ?>
                <div class="card mb-4">
                    <div class="card-header"><?php echo e(__('analytics.category_performance')); ?></div>
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead><tr><th><?php echo e(__('analytics.category')); ?></th><th><?php echo e(__('analytics.views')); ?></th><th><?php echo e(__('analytics.uniques')); ?></th></tr></thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $categoryPerformance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr><td><?php echo e($c->category_name); ?></td><td><?php echo e($c->views); ?></td><td><?php echo e($c->uniques); ?></td></tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr><td colspan="3" class="text-center text-muted"><?php echo e(__('analytics.no_data')); ?></td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card"><div class="card-body"><canvas id="chartCategories" height="100"></canvas></div></div>
            <?php endif; ?>

            <?php if($tab === 'sales'): ?>
                <div class="row g-3 mb-4">
                    <div class="col-md-4"><div class="card p-3"><h6><?php echo e(__('analytics.orders')); ?></h6><h3><?php echo e($sales['orders']); ?> <?php echo $changeBadge($change['orders'] ?? 0); ?></h3></div></div>
                    <div class="col-md-4"><div class="card p-3"><h6><?php echo e(__('analytics.completed')); ?></h6><h3><?php echo e($sales['completed_orders']); ?></h3></div></div>
                    <div class="col-md-4"><div class="card p-3"><h6><?php echo e(__('analytics.aov')); ?></h6><h3><?php echo e(number_format($sales['aov'], 2)); ?> EGP</h3></div></div>
                </div>
                <div class="card">
                    <div class="card-header"><?php echo e(__('analytics.sales_by_city')); ?></div>
                    <table class="table mb-0">
                        <thead><tr><th><?php echo e(__('analytics.city')); ?></th><th><?php echo e(__('analytics.orders')); ?></th><th><?php echo e(__('analytics.revenue')); ?></th></tr></thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $salesByCity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr><td><?php echo e($c->city_name); ?></td><td><?php echo e($c->orders_count); ?></td><td><?php echo e(number_format($c->revenue, 2)); ?> EGP</td></tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="3" class="text-center text-muted"><?php echo e(__('analytics.no_data')); ?></td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <?php if($tab === 'customers'): ?>
                <div class="row g-3 mb-4">
                    <div class="col-md-3"><div class="card h-100"><div class="card-body">
                        <h6 class="text-muted"><?php echo e(__('analytics.new_customers')); ?></h6>
                        <h3 class="mb-0"><?php echo e(number_format($customerOverview['new_customers'])); ?></h3>
                    </div></div></div>
                    <div class="col-md-3"><div class="card h-100"><div class="card-body">
                        <h6 class="text-muted"><?php echo e(__('analytics.returning_customers')); ?></h6>
                        <h3 class="mb-0"><?php echo e(number_format($customerOverview['returning_customers'])); ?></h3>
                    </div></div></div>
                    <div class="col-md-3"><div class="card h-100"><div class="card-body">
                        <h6 class="text-muted"><?php echo e(__('analytics.repeat_rate')); ?></h6>
                        <h3 class="mb-0"><?php echo e($customerOverview['repeat_rate']); ?>%</h3>
                    </div></div></div>
                    <div class="col-md-3"><div class="card h-100"><div class="card-body">
                        <h6 class="text-muted"><?php echo e(__('analytics.atc_rate')); ?></h6>
                        <h3 class="mb-0"><?php echo e($customerOverview['atc_rate']); ?>%</h3>
                    </div></div></div>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-3"><div class="card p-3"><h6><?php echo e(__('analytics.guest_orders')); ?></h6><h3><?php echo e($customerOverview['guest_orders']); ?></h3></div></div>
                    <div class="col-md-3"><div class="card p-3"><h6><?php echo e(__('analytics.registered_orders')); ?></h6><h3><?php echo e($customerOverview['registered_orders']); ?></h3></div></div>
                    <div class="col-md-3"><div class="card p-3"><h6><?php echo e(__('analytics.new_accounts')); ?></h6><h3><?php echo e($customerOverview['new_accounts']); ?></h3></div></div>
                    <div class="col-md-3"><div class="card p-3"><h6><?php echo e(__('analytics.add_to_cart')); ?></h6><h3><?php echo e($customerOverview['add_to_cart']); ?></h3></div></div>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-lg-4">
                        <div class="card h-100"><div class="card-header"><?php echo e(__('analytics.devices')); ?></div>
                            <div class="card-body"><canvas id="chartDevices" height="140"></canvas></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card h-100"><div class="card-header"><?php echo e(__('analytics.traffic_sources')); ?></div>
                            <div class="card-body"><canvas id="chartSources" height="140"></canvas></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card h-100"><div class="card-header"><?php echo e(__('analytics.sales_by_weekday')); ?></div>
                            <div class="card-body"><canvas id="chartWeekday" height="140"></canvas></div>
                        </div>
                    </div>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header"><?php echo e(__('analytics.top_customers')); ?></div>
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead><tr><th><?php echo e(__('analytics.customer')); ?></th><th><?php echo e(__('analytics.customer_type')); ?></th><th><?php echo e(__('analytics.orders')); ?></th><th><?php echo e(__('analytics.revenue')); ?></th></tr></thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $topCustomers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td><?php echo e($c->customer_name); ?></td>
                                                <td><?php echo e(__('analytics.type_'.$c->customer_type)); ?></td>
                                                <td><?php echo e($c->orders_count); ?></td>
                                                <td><?php echo e(number_format($c->revenue, 2)); ?> EGP</td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr><td colspan="4" class="text-center text-muted"><?php echo e(__('analytics.no_data')); ?></td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card mb-3">
                            <div class="card-header"><?php echo e(__('analytics.top_searches')); ?></div>
                            <table class="table mb-0">
                                <thead><tr><th><?php echo e(__('analytics.search_term')); ?></th><th><?php echo e(__('analytics.searches')); ?></th></tr></thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $topSearchTerms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr><td><?php echo e($s->term); ?></td><td><?php echo e($s->searches); ?></td></tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr><td colspan="2" class="text-center text-muted"><?php echo e(__('analytics.no_data')); ?></td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="card">
                            <div class="card-header"><?php echo e(__('analytics.payment_methods')); ?></div>
                            <table class="table mb-0">
                                <thead><tr><th><?php echo e(__('analytics.payment_method')); ?></th><th><?php echo e(__('analytics.orders')); ?></th><th><?php echo e(__('analytics.revenue')); ?></th></tr></thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr><td><?php echo e($p->payment_method ?: '—'); ?></td><td><?php echo e($p->cnt); ?></td><td><?php echo e(number_format($p->amount, 2)); ?> EGP</td></tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr><td colspan="3" class="text-center text-muted"><?php echo e(__('analytics.no_data')); ?></td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($tab === 'live'): ?>
                <div class="card">
                    <div class="card-body table-responsive">
                        <table class="table mb-0">
                            <thead><tr><th><?php echo e(__('analytics.path')); ?></th><th><?php echo e(__('analytics.page')); ?></th><th><?php echo e(__('analytics.product')); ?></th><th><?php echo e(__('analytics.user')); ?></th><th><?php echo e(__('analytics.last_seen')); ?></th></tr></thead>
                            <tbody id="live-list">
                                <?php $__currentLoopData = $liveVisitors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($v->current_path); ?></td>
                                        <td><?php echo e($v->current_page_key); ?></td>
                                        <td><?php echo e($v->product?->name ?? '—'); ?></td>
                                        <td><?php echo e($v->user?->name ?? __('analytics.guest')); ?></td>
                                        <td><?php echo e($v->last_seen_at?->diffForHumans()); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    const refreshMs = <?php echo e((int) config('analytics.live_refresh_seconds', 45) * 1000); ?>;
    function pollLive() {
        fetch('<?php echo e(route('admin.analytics.live')); ?>', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                const el = document.getElementById('live-count');
                if (el) el.textContent = data.count;
            }).catch(() => {});
    }
    setInterval(pollLive, refreshMs);

    <?php if(in_array($tab, ['overview', 'traffic', 'sales', 'customers', 'categories'])): ?>
    const traffic = <?php echo json_encode($chartViews, 15, 512) ?>;
    const hours = <?php echo json_encode($trafficByHour, 15, 512) ?>;
    const revenue = <?php echo json_encode($chartRevenue, 15, 512) ?>;
    const funnel = <?php echo json_encode($funnel, 15, 512) ?>;
    const categories = <?php echo json_encode($categoryPerformance->pluck('views', 'category_name'), 512) ?>;
    const devices = <?php echo json_encode($deviceBreakdown->pluck('sessions', 'device'), 512) ?>;
    const sources = <?php echo json_encode($trafficSources->pluck('sessions', 'traffic_source'), 512) ?>;
    const weekday = <?php echo json_encode(collect($salesByWeekday)->pluck('orders'), 15, 512) ?>;
    const weekdayLabels = <?php echo json_encode([
        __('analytics.mon'), __('analytics.tue'), __('analytics.wed')) ?>;

    if (document.getElementById('chartTraffic')) {
        new Chart(document.getElementById('chartTraffic'), {
            type: 'line',
            data: {
                labels: traffic.labels,
                datasets: [
                    { label: '<?php echo e(__('analytics.page_views')); ?>', data: traffic.pageViews, borderColor: '#0d6efd', tension: 0.3 },
                    { label: '<?php echo e(__('analytics.product_views')); ?>', data: traffic.productViews, borderColor: '#198754', tension: 0.3 },
                    { label: '<?php echo e(__('analytics.orders')); ?>', data: traffic.orders, borderColor: '#fd7e14', tension: 0.3 },
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
                datasets: [{ label: '<?php echo e(__('analytics.page_views')); ?>', data: hours, backgroundColor: '#6c757d' }]
            },
            options: { responsive: true, plugins: { legend: { display: false } } }
        });
    }
    if (document.getElementById('chartRevenue')) {
        new Chart(document.getElementById('chartRevenue'), {
            type: 'bar',
            data: {
                labels: revenue.labels,
                datasets: [{ label: '<?php echo e(__('analytics.revenue')); ?> (EGP)', data: revenue.revenue, backgroundColor: '#0d6efd' }]
            },
            options: { responsive: true }
        });
    }
    if (document.getElementById('chartFunnel')) {
        new Chart(document.getElementById('chartFunnel'), {
            type: 'bar',
            data: {
                labels: Object.keys(funnel),
                datasets: [{ label: '<?php echo e(__('analytics.sessions')); ?>', data: Object.values(funnel), backgroundColor: '#20c997' }]
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
                datasets: [{ label: '<?php echo e(__('analytics.sessions')); ?>', data: Object.values(sources), backgroundColor: '#6610f2' }]
            },
            options: { plugins: { legend: { display: false } } }
        });
    }
    if (document.getElementById('chartWeekday')) {
        new Chart(document.getElementById('chartWeekday'), {
            type: 'bar',
            data: {
                labels: weekdayLabels,
                datasets: [{ label: '<?php echo e(__('analytics.orders')); ?>', data: weekday, backgroundColor: '#20c997' }]
            },
            options: { plugins: { legend: { display: false } } }
        });
    }
    <?php endif; ?>
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
<?php /**PATH C:\hayah\resources\views/admin/analytics/index.blade.php ENDPATH**/ ?>