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

<?php $isRtl = app()->getLocale() === 'ar'; ?>

<main id="main">
    <div class="container">
        <div class="row pt-4">
            <div class="pagetitle">
                <h1><?php echo e(__('seo.title')); ?></h1>
                <nav>
                    <ol class="breadcrumb d-flex <?php echo e($isRtl ? 'text-end' : 'text-start'); ?>" dir="<?php echo e($isRtl ? 'rtl' : 'ltr'); ?>">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('seo.breadcrumb_main')); ?></a></li>
                        <li class="mx-2">-</li>
                        <li class="breadcrumb-item active"><?php echo e(__('seo.breadcrumb_active')); ?></li>
                    </ol>
                </nav>
            </div>

            <div class="card">
                <div class="card-body p-4">
                    <form action="<?php echo e(route('admin.settings.seo.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <h5 class="mb-3"><?php echo e(__('seo.global')); ?></h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label"><?php echo e(__('seo.meta_title_en')); ?></label>
                                <input type="text" name="meta_title_en" class="form-control" value="<?php echo e(old('meta_title_en', $settings->meta_title_en)); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?php echo e(__('seo.meta_title_ar')); ?></label>
                                <input type="text" name="meta_title_ar" class="form-control" value="<?php echo e(old('meta_title_ar', $settings->meta_title_ar)); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?php echo e(__('seo.meta_description_en')); ?></label>
                                <textarea name="meta_description_en" class="form-control" rows="2"><?php echo e(old('meta_description_en', $settings->meta_description_en)); ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?php echo e(__('seo.meta_description_ar')); ?></label>
                                <textarea name="meta_description_ar" class="form-control" rows="2"><?php echo e(old('meta_description_ar', $settings->meta_description_ar)); ?></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><?php echo e(__('seo.robots')); ?></label>
                                <input type="text" name="robots" class="form-control" value="<?php echo e(old('robots', $settings->robots ?? 'index, follow')); ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><?php echo e(__('seo.canonical_url')); ?></label>
                                <input type="url" name="canonical_url" class="form-control" value="<?php echo e(old('canonical_url', $settings->canonical_url)); ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><?php echo e(__('seo.twitter_card')); ?></label>
                                <select name="twitter_card" class="form-select">
                                    <option value="summary_large_image" <?php if(old('twitter_card', $settings->twitter_card) === 'summary_large_image'): echo 'selected'; endif; ?>>summary_large_image</option>
                                    <option value="summary" <?php if(old('twitter_card', $settings->twitter_card) === 'summary'): echo 'selected'; endif; ?>>summary</option>
                                </select>
                            </div>
                        </div>

                        <?php if($pages->isNotEmpty()): ?>
                            <h5 class="mb-3"><?php echo e(__('seo.per_page')); ?></h5>
                            <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="border rounded p-3 mb-3">
                                    <strong><?php echo e($page->page_key); ?></strong>
                                    <div class="row g-2 mt-2">
                                        <div class="col-md-6">
                                            <input type="text" name="pages[<?php echo e($page->page_key); ?>][meta_title_en]" class="form-control form-control-sm" placeholder="Title EN"
                                                   value="<?php echo e(old('pages.'.$page->page_key.'.meta_title_en', $page->meta_title_en)); ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="pages[<?php echo e($page->page_key); ?>][meta_title_ar]" class="form-control form-control-sm" placeholder="Title AR"
                                                   value="<?php echo e(old('pages.'.$page->page_key.'.meta_title_ar', $page->meta_title_ar)); ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <textarea name="pages[<?php echo e($page->page_key); ?>][meta_description_en]" class="form-control form-control-sm" rows="2" placeholder="Description EN"><?php echo e(old('pages.'.$page->page_key.'.meta_description_en', $page->meta_description_en)); ?></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <textarea name="pages[<?php echo e($page->page_key); ?>][meta_description_ar]" class="form-control form-control-sm" rows="2" placeholder="Description AR"><?php echo e(old('pages.'.$page->page_key.'.meta_description_ar', $page->meta_description_ar)); ?></textarea>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-check">
                                                <input type="checkbox" class="form-check-input" name="pages[<?php echo e($page->page_key); ?>][is_indexable]" value="1" <?php if($page->is_indexable): echo 'checked'; endif; ?>>
                                                <?php echo e(__('seo.indexable')); ?>

                                            </label>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>

                        <button type="submit" class="btn btn-primary"><?php echo e(__('seo.save')); ?></button>
                    </form>
                </div>
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
<?php /**PATH C:\hayah\resources\views/admin/settings/seo.blade.php ENDPATH**/ ?>