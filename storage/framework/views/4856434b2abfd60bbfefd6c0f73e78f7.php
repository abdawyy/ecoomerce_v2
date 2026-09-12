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
?>

<style>
    .filter-card {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 6px;
    }
    .filter-header {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 12px 15px;
    }
    .filter-header h5 {
        margin: 0;
        font-weight: 600;
        font-size: 0.95rem;
    }
    .filter-body {
        padding: 15px;
    }
    .form-control, .form-select {
        border: 1px solid #dee2e6;
        border-radius: 4px;
        font-size: 0.9rem;
        padding: 0.5rem 0.75rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #80bdff;
        box-shadow: none;
    }
    .filter-buttons {
        display: flex;
        gap: 10px;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #dee2e6;
    }
    .btn-apply, .btn-clear {
        flex: 1;
        padding: 0.5rem 1rem;
        font-weight: 500;
        font-size: 0.85rem;
        border-radius: 4px;
        border: none;
        cursor: pointer;
    }
    .btn-apply {
        background: #007bff;
        color: white;
    }
    .btn-apply:hover {
        background: #0056b3;
    }
    .btn-clear {
        background: #e9ecef;
        color: #333;
        border: 1px solid #dee2e6;
    }
    .btn-clear:hover {
        background: #dee2e6;
    }
</style>

<main id="main">
    <div class="container">
        <div class="row pt-4">
            <div class="pagetitle">
                <h1><?php echo e(__('category_list.title')); ?></h1>
                <nav>
                    <ol class="breadcrumb d-flex <?php echo e($isRtl ? 'text-end' : 'text-start'); ?>"
                        dir="<?php echo e($isRtl ? 'rtl' : 'ltr'); ?>">
                        <li class="breadcrumb-item">
                            <a href="#"><?php echo e(__('category_list.breadcrumb_main')); ?></a>
                        </li>
                        <li class="mx-2">-</li>
                        <li class="breadcrumb-item active">
                            <?php echo e(__('category_list.breadcrumb_active')); ?>

                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Filter -->
            <div class="filter-card mb-4">
                <div class="filter-header">
                    <h5><?php echo e(__('web.filter')); ?></h5>
                </div>
                <div class="filter-body">
                    <form method="GET" class="row g-2">
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" name="search" class="form-control" placeholder="Search by name" value="<?php echo e(request('search')); ?>">
                        </div>
                        <div class="col-12">
                            <div class="filter-buttons">
                                <button type="submit" class="btn-apply">Apply</button>
                                <a href="<?php echo e(route('categories.list')); ?>" class="btn-clear">Clear</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <?php if (isset($component)) { $__componentOriginalb539fdd4bceece4a667dd360eb69c7ae = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb539fdd4bceece4a667dd360eb69c7ae = $attributes; } ?>
<?php $component = App\View\Components\DataTable::resolve(['headers' => $headers,'rows' => $rows,'url' => $url] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('data-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\DataTable::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb539fdd4bceece4a667dd360eb69c7ae)): ?>
<?php $attributes = $__attributesOriginalb539fdd4bceece4a667dd360eb69c7ae; ?>
<?php unset($__attributesOriginalb539fdd4bceece4a667dd360eb69c7ae); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb539fdd4bceece4a667dd360eb69c7ae)): ?>
<?php $component = $__componentOriginalb539fdd4bceece4a667dd360eb69c7ae; ?>
<?php unset($__componentOriginalb539fdd4bceece4a667dd360eb69c7ae); ?>
<?php endif; ?>

            <style>
                .btn-danger {
                    display: none;
                }
                    svg {
        width: 5px !important;
        height: 5px !important;

    }
            </style>

            <div class="mt-4">
<?php echo e($data->links('vendor.pagination.bootstrap-5')); ?>

            </div>
        </div>
    </div>
</main>

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
<?php /**PATH C:\hayah\resources\views/admin/categories/list.blade.php ENDPATH**/ ?>