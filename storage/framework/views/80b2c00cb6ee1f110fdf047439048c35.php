<?php if (isset($component)) { $__componentOriginal628d23ec5db1173b09efbdbd01a50602 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal628d23ec5db1173b09efbdbd01a50602 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.web.layout','data' => ['title' => $id ? $categoryName->name : __('web.all_products')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('web.layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($id ? $categoryName->name : __('web.all_products'))]); ?>
    <?php $__env->startPush('styles'); ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/css/plp.css')); ?>">
    <?php $__env->stopPush(); ?>

<section class="pb-5 plp-page">
    <div class="container py-5">
        <div class="row">
            <!-- Desktop Sidebar -->
            <aside class="col-lg-3 d-none d-lg-block">
                <div class="card card-aside position-sticky" style="top: 100px;">
                    <div class="filter-sidebar-header">
                        <h6 class="filter-title"><?php echo e(__('web.filter')); ?></h6>
                    </div>
                    
                    <form method="GET" action="<?php echo e(route('product.List', ($id ? ['id' => $id] : []))); ?>" class="filter-form">
                        <?php echo $__env->make('product.partials.filters', ['idPrefix' => 'd', 'linkDismiss' => false], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                        <div class="filter-buttons">
                            <button type="submit" class="btn-filter apply"><?php echo e(__('web.filter')); ?></button>
                            <a href="<?php echo e(route('product.List', ($id ? ['id' => $id] : []))); ?>" class="btn-filter clear"><?php echo e(__('web.clear_filters')); ?></a>
                        </div>
                    </form>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="col-lg-9 col-12">
                <?php
                    $listRouteParams = $id ? ['id' => $id] : [];
                    $breadcrumbItems = [
                        ['label' => __('web.home_breadcrumb'), 'url' => route('home')],
                        ['label' => $id ? $categoryName->name : __('web.all_products'), 'url' => route('product.List', $listRouteParams)],
                    ];
                ?>

                <?php if (isset($component)) { $__componentOriginal4fe67e161b5ccdf1be7da2c8e05f80d9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4fe67e161b5ccdf1be7da2c8e05f80d9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.web.breadcrumb','data' => ['items' => $breadcrumbItems]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('web.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($breadcrumbItems)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4fe67e161b5ccdf1be7da2c8e05f80d9)): ?>
<?php $attributes = $__attributesOriginal4fe67e161b5ccdf1be7da2c8e05f80d9; ?>
<?php unset($__attributesOriginal4fe67e161b5ccdf1be7da2c8e05f80d9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4fe67e161b5ccdf1be7da2c8e05f80d9)): ?>
<?php $component = $__componentOriginal4fe67e161b5ccdf1be7da2c8e05f80d9; ?>
<?php unset($__componentOriginal4fe67e161b5ccdf1be7da2c8e05f80d9); ?>
<?php endif; ?>

                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                    <div>
                        <h1 class="fw-bold h3 mb-1"><?php echo e($id ? $categoryName->name : __('web.all_products')); ?></h1>
                        <p class="text-muted small mb-0"><?php echo e(__('web.products_count', ['count' => $data->total()])); ?></p>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <form method="GET" action="<?php echo e(route('product.List', $listRouteParams)); ?>" class="d-none d-md-flex align-items-center gap-2">
                            <?php $__currentLoopData = request()->except('sort', 'page'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(is_scalar($value) && $value !== ''): ?>
                                    <input type="hidden" name="<?php echo e($key); ?>" value="<?php echo e($value); ?>">
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <label for="sort" class="small text-muted mb-0"><?php echo e(__('web.sort_by')); ?></label>
                            <select name="sort" id="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="newest" <?php if(($sort ?? 'newest') === 'newest'): echo 'selected'; endif; ?>><?php echo e(__('web.sort_newest')); ?></option>
                                <option value="price_asc" <?php if(($sort ?? '') === 'price_asc'): echo 'selected'; endif; ?>><?php echo e(__('web.sort_price_asc')); ?></option>
                                <option value="price_desc" <?php if(($sort ?? '') === 'price_desc'): echo 'selected'; endif; ?>><?php echo e(__('web.sort_price_desc')); ?></option>
                            </select>
                        </form>

                        <?php $filterSide = app()->getLocale() === 'ar' ? 'offcanvas-start' : 'offcanvas-end'; ?>
                        <button class="btn btn-dark btn-sm d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#mobileFilterPanel">
                            <i class="fas fa-sliders-h me-2"></i><?php echo e(__('web.filter')); ?>

                        </button>
                    </div>
                </div>

                <?php
                    $activeFilters = collect([
                        'min_price' => $minPrice ? __('web.min_price') . ': ' . $minPrice : null,
                        'max_price' => $maxPrice ? __('web.max_price') . ': ' . $maxPrice : null,
                        'type_id' => $type_id ? optional($types->firstWhere('id', $type_id))->name : null,
                        'color' => $color ?: null,
                        'size' => $size ?: null,
                        'stock_status' => $stock_status === 'in_stock' ? __('web.in_stock') : ($stock_status === 'out_of_stock' ? __('web.out_of_stock') : null),
                        'on_sale' => ($onSale ?? false) ? __('web.on_sale') : null,
                    ])->filter();
                ?>

                <?php if($activeFilters->isNotEmpty()): ?>
                    <div class="plp-active-filters">
                        <?php $__currentLoopData = $activeFilters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('product.List', array_merge($listRouteParams, request()->except([$key, 'page'])))); ?>"
                               class="plp-filter-chip">
                                <?php echo e($label); ?> <i class="bi bi-x"></i>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('product.List', $listRouteParams)); ?>" class="plp-filter-chip"><?php echo e(__('web.clear_filters')); ?></a>
                    </div>
                <?php endif; ?>

                <!-- Products Grid -->
                <div class="row g-3 g-md-4">
                    <?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="col-6 col-lg-3 mb-3">
                            <?php if (isset($component)) { $__componentOriginal9dbde93c50e49c2830e855fc2dc390af = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9dbde93c50e49c2830e855fc2dc390af = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.web.product-card','data' => ['product' => $product,'imageClass' => 'list-product-img']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('web.product-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['product' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($product),'image-class' => 'list-product-img']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9dbde93c50e49c2830e855fc2dc390af)): ?>
<?php $attributes = $__attributesOriginal9dbde93c50e49c2830e855fc2dc390af; ?>
<?php unset($__attributesOriginal9dbde93c50e49c2830e855fc2dc390af); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9dbde93c50e49c2830e855fc2dc390af)): ?>
<?php $component = $__componentOriginal9dbde93c50e49c2830e855fc2dc390af; ?>
<?php unset($__componentOriginal9dbde93c50e49c2830e855fc2dc390af); ?>
<?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-12">
                            <div class="plp-empty-state">
                                <p class="mb-3"><?php echo e(__('web.no_products_found')); ?></p>
                                <a href="<?php echo e(route('product.List', $listRouteParams)); ?>" class="btn btn-dark"><?php echo e(__('web.clear_filters')); ?></a>
                                <a href="<?php echo e(route('product.List')); ?>" class="btn btn-outline-dark ms-2"><?php echo e(__('web.browse_all_products')); ?></a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-5">
                    <?php echo $data->links('pagination::bootstrap-5'); ?>

                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mobile Filter Modal (Offcanvas) -->
<?php $filterSide = app()->getLocale() === 'ar' ? 'offcanvas-start' : 'offcanvas-end'; ?>
<div class="offcanvas <?php echo e($filterSide); ?> plp-mobile-filter" tabindex="-1" id="mobileFilterPanel" aria-labelledby="mobileFilterLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="mobileFilterLabel"><?php echo e(__('web.filter')); ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <form method="GET" action="<?php echo e(route('product.List', ($id ? ['id' => $id] : []))); ?>" class="filter-form">
            <?php echo $__env->make('product.partials.filters', ['idPrefix' => 'm', 'linkDismiss' => true], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div class="filter-buttons p-3 border-top">
                <button type="submit" class="btn-filter apply"><?php echo e(__('web.filter')); ?></button>
                <a href="<?php echo e(route('product.List', ($id ? ['id' => $id] : []))); ?>" class="btn-filter clear"><?php echo e(__('web.clear_filters')); ?></a>
            </div>
        </form>
    </div>
</div>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal628d23ec5db1173b09efbdbd01a50602)): ?>
<?php $attributes = $__attributesOriginal628d23ec5db1173b09efbdbd01a50602; ?>
<?php unset($__attributesOriginal628d23ec5db1173b09efbdbd01a50602); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal628d23ec5db1173b09efbdbd01a50602)): ?>
<?php $component = $__componentOriginal628d23ec5db1173b09efbdbd01a50602; ?>
<?php unset($__componentOriginal628d23ec5db1173b09efbdbd01a50602); ?>
<?php endif; ?>
<?php /**PATH C:\hayah\resources\views/product/list.blade.php ENDPATH**/ ?>