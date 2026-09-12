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
                <h1><?php echo e(__('branding.title')); ?></h1>
                <nav>
                    <ol class="breadcrumb d-flex <?php echo e($isRtl ? 'text-end' : 'text-start'); ?>" dir="<?php echo e($isRtl ? 'rtl' : 'ltr'); ?>">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('branding.breadcrumb_main')); ?></a></li>
                        <li class="mx-2">-</li>
                        <li class="breadcrumb-item active"><?php echo e(__('branding.breadcrumb_active')); ?></li>
                    </ol>
                </nav>
            </div>

            <div class="card">
                <div class="card-body p-4">
                    <form action="<?php echo e(route('admin.settings.branding.update')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label"><?php echo e(__('branding.site_name')); ?></label>
                                <input type="text" name="site_name" class="form-control"
                                       value="<?php echo e(old('site_name', $settings->site_name ?? $branding->siteName())); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?php echo e(__('branding.support_email')); ?></label>
                                <input type="email" name="support_email" class="form-control"
                                       value="<?php echo e(old('support_email', $settings->support_email)); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?php echo e(__('branding.support_phone')); ?></label>
                                <input type="text" name="support_phone" class="form-control"
                                       value="<?php echo e(old('support_phone', $settings->support_phone)); ?>">
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5><?php echo e(__('branding.social_links')); ?></h5>
                        <p class="text-muted small"><?php echo e(__('branding.social_links_hint')); ?></p>
                        <div class="row g-3">
                            <?php $__currentLoopData = config('branding.social_platforms', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform => $meta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $labelKey = $meta['label_key'] ?? "branding.social_{$platform}";
                                    $label = __($labelKey);
                                    if ($label === $labelKey) {
                                        $label = ucfirst(str_replace('_', ' ', $platform));
                                    }
                                    $placeholder = $platform === 'whatsapp'
                                        ? 'https://wa.me/201234567890'
                                        : 'https://';
                                ?>
                                <div class="col-md-6">
                                    <label class="form-label d-flex align-items-center gap-2">
                                        <i class="bi <?php echo e($meta['icon'] ?? 'bi-link-45deg'); ?>"></i>
                                        <?php echo e($label); ?>

                                    </label>
                                    <input type="text" name="<?php echo e($platform); ?>_url" class="form-control"
                                           placeholder="<?php echo e($placeholder); ?>"
                                           value="<?php echo e(old($platform.'_url', $settings->{$platform.'_url'})); ?>">
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <hr class="my-4">
                        <h5><?php echo e(__('branding.logos_media')); ?></h5>
                        <p class="text-muted small mb-3"><?php echo e(__('branding.logo_usage_hint')); ?></p>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label"><?php echo e(__('branding.logo')); ?></label>
                                <?php
                                    $currentPath = $settings->logo_path ?? null;
                                    $previewUrl = $currentPath
                                        ? $branding->assetUrl($currentPath)
                                        : $branding->logoUrl();
                                ?>
                                <?php if($previewUrl): ?>
                                    <div class="mb-2">
                                        <img src="<?php echo e($previewUrl); ?>" alt="" class="img-thumbnail" style="max-height:80px; object-fit: contain;">
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="logo" class="form-control" accept="image/*">
                                <div class="form-text"><?php echo e(__('branding.logo_main_hint')); ?></div>
                            </div>
                        </div>

                        <hr class="my-4" id="home-tiles">
                        <h5><?php echo e(__('branding.home_tiles')); ?></h5>
                        <p class="text-muted"><?php echo e(__('branding.home_tiles_hint')); ?></p>
                        <?php
                            $categories = $categories ?? collect();
                            $liveTiles = $branding->homeCategoryTiles();
                        ?>
                        <?php if($categories->isEmpty()): ?>
                            <div class="alert alert-warning"><?php echo e(__('branding.home_tiles_no_categories')); ?></div>
                        <?php endif; ?>
                        <div class="row g-4">
                            <?php $__currentLoopData = [
                                1 => ['en' => __('web.top'), 'ar' => __('web.top', [], 'ar')],
                                2 => ['en' => trim(__('web.Long Sleeve')), 'ar' => trim(__('web.Long Sleeve', [], 'ar'))],
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot => $defaults): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-6">
                                    <div class="admin-nested-panel border rounded-3 p-3 h-100">
                                        <h6 class="fw-bold mb-3"><?php echo e(__('branding.home_tile_heading', ['n' => $slot, 'name' => $defaults['en']])); ?></h6>
                                        <label class="form-label fw-semibold"><?php echo e(__('branding.home_tile_name_en')); ?></label>
                                        <input type="text" name="home_tile_<?php echo e($slot); ?>_title_en" class="form-control mb-2"
                                               value="<?php echo e(old("home_tile_{$slot}_title_en", $settings->{"home_tile_{$slot}_title_en"} ?: $defaults['en'])); ?>">
                                        <label class="form-label fw-semibold"><?php echo e(__('branding.home_tile_name_ar')); ?></label>
                                        <input type="text" name="home_tile_<?php echo e($slot); ?>_title_ar" class="form-control mb-3" dir="rtl"
                                               value="<?php echo e(old("home_tile_{$slot}_title_ar", $settings->{"home_tile_{$slot}_title_ar"} ?: $defaults['ar'])); ?>">
                                        <label class="form-label fw-semibold"><?php echo e(__('branding.home_tile_redirect')); ?></label>
                                        <select name="home_category_<?php echo e($slot); ?>_id" class="form-select">
                                            <option value=""><?php echo e(__('branding.home_tile_all_products')); ?></option>
                                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($category->id); ?>" <?php if((string) old("home_category_{$slot}_id", $settings->{"home_category_{$slot}_id"}) === (string) $category->id): echo 'selected'; endif; ?>>
                                                    <?php echo e($category->name); ?><?php if(! $category->is_active): ?> (<?php echo e(__('branding.inactive')); ?>)<?php endif; ?>
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="form-text"><?php echo e(__('branding.home_tile_redirect_hint')); ?></div>
                                        <?php $live = $liveTiles[$slot - 1] ?? null; ?>
                                        <?php if($live): ?>
                                            <p class="admin-nested-panel-live small mb-0 mt-3 text-break">
                                                <?php echo e(__('branding.home_tile_live')); ?>:
                                                <strong><?php echo e($live['name']); ?></strong>
                                                → <a href="<?php echo e($live['url']); ?>" target="_blank" rel="noopener"><?php echo e($live['url']); ?></a>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <hr class="my-4">
                        <h5><?php echo e(__('branding.home_images')); ?></h5>
                        <p class="text-muted small"><?php echo e(__('branding.home_images_hint')); ?></p>
                        <div class="row g-4">
                            <?php $__currentLoopData = [
                                'hero_image' => __('branding.hero_image'),
                                'category_image_1' => __('branding.category_image_1'),
                                'category_image_2' => __('branding.category_image_2'),
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-4">
                                    <label class="form-label"><?php echo e($label); ?></label>
                                    <?php
                                        $pathColumn = match($field) {
                                            'hero_image' => 'hero_image_path',
                                            'category_image_1' => 'category_image_1_path',
                                            'category_image_2' => 'category_image_2_path',
                                            default => $field.'_path',
                                        };
                                        $currentPath = $settings->{$pathColumn} ?? null;
                                        $previewUrl = $currentPath
                                            ? $branding->assetUrl($currentPath)
                                            : match($field) {
                                                'hero_image' => $branding->heroImageUrl(),
                                                'category_image_1' => $branding->categoryImage1Url(),
                                                'category_image_2' => $branding->categoryImage2Url(),
                                                default => '',
                                            };
                                    ?>
                                    <?php if($previewUrl): ?>
                                        <div class="admin-image-preview mb-2 rounded overflow-hidden">
                                            <img src="<?php echo e($previewUrl); ?>" alt="">
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" name="<?php echo e($field); ?>" class="form-control" accept="image/*">
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <hr class="my-4">
                        <h5><?php echo e(__('branding.invoice_pdf')); ?></h5>
                        <p class="text-muted small"><?php echo e(__('branding.invoice_notes_hint')); ?></p>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label"><?php echo e(__('branding.accent_color')); ?></label>
                                <input type="color" name="accent_color" class="form-control form-control-color"
                                       value="<?php echo e(old('accent_color', $settings->accent_color ?? $branding->primaryColor())); ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><?php echo e(__('branding.pdf_footer_en')); ?></label>
                                <input type="text" name="pdf_footer_en" class="form-control" value="<?php echo e(old('pdf_footer_en', $settings->pdf_footer_en)); ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><?php echo e(__('branding.pdf_footer_ar')); ?></label>
                                <input type="text" name="pdf_footer_ar" class="form-control" value="<?php echo e(old('pdf_footer_ar', $settings->pdf_footer_ar)); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?php echo e(__('branding.pdf_thank_you_en')); ?></label>
                                <input type="text" name="pdf_thank_you_en" class="form-control" value="<?php echo e(old('pdf_thank_you_en', $settings->pdf_thank_you_en)); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?php echo e(__('branding.pdf_thank_you_ar')); ?></label>
                                <input type="text" name="pdf_thank_you_ar" class="form-control" value="<?php echo e(old('pdf_thank_you_ar', $settings->pdf_thank_you_ar)); ?>">
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label mb-0"><?php echo e(__('branding.invoice_notes_en')); ?></label>
                                    <button type="button" class="btn btn-link btn-sm p-0" id="load-invoice-notes-en"><?php echo e(__('branding.load_invoice_sample_notes')); ?> (EN)</button>
                                </div>
                                <textarea name="invoice_notes_en" id="invoice_notes_en" class="form-control" rows="5" placeholder="<?php echo e(__('branding.invoice_notes_placeholder')); ?>"><?php echo e(old('invoice_notes_en', $settings->invoice_notes_en ? strip_tags(str_replace(['</p>', '<br>', '<br/>', '<br />', '</li>'], ["\n", "\n", "\n", "\n", "\n"], $settings->invoice_notes_en)) : '')); ?></textarea>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label mb-0"><?php echo e(__('branding.invoice_notes_ar')); ?></label>
                                    <button type="button" class="btn btn-link btn-sm p-0" id="load-invoice-notes-ar"><?php echo e(__('branding.load_invoice_sample_notes')); ?> (AR)</button>
                                </div>
                                <textarea name="invoice_notes_ar" id="invoice_notes_ar" class="form-control" rows="5" dir="rtl" placeholder="<?php echo e(__('branding.invoice_notes_placeholder')); ?>"><?php echo e(old('invoice_notes_ar', $settings->invoice_notes_ar ? strip_tags(str_replace(['</p>', '<br>', '<br/>', '<br />', '</li>'], ["\n", "\n", "\n", "\n", "\n"], $settings->invoice_notes_ar)) : '')); ?></textarea>
                            </div>
                        </div>
                        <div class="mt-3 d-flex flex-wrap gap-2">
                            <a href="<?php echo e(route('admin.settings.pdf.preview', ['lang' => 'en'])); ?>" class="btn btn-primary btn-sm" target="_blank"><?php echo e(__('pdf.preview_sample_invoice_en')); ?></a>
                            <a href="<?php echo e(route('admin.settings.pdf.preview', ['lang' => 'ar'])); ?>" class="btn btn-primary btn-sm" target="_blank"><?php echo e(__('pdf.preview_sample_invoice_ar')); ?></a>
                        </div>

                        <hr class="my-4">
                        <h5><?php echo e(__('branding.cart_policy')); ?></h5>
                        <p class="text-muted small"><?php echo e(__('branding.cart_policy_hint')); ?></p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label"><?php echo e(__('branding.cart_policy_title_en')); ?></label>
                                <input type="text" name="cart_policy_title_en" class="form-control"
                                       value="<?php echo e(old('cart_policy_title_en', $settings->cart_policy_title_en)); ?>"
                                       placeholder="<?php echo e(__('cart.policy_title_default', [], 'en')); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?php echo e(__('branding.cart_policy_title_ar')); ?></label>
                                <input type="text" name="cart_policy_title_ar" class="form-control" dir="rtl"
                                       value="<?php echo e(old('cart_policy_title_ar', $settings->cart_policy_title_ar)); ?>"
                                       placeholder="<?php echo e(__('cart.policy_title_default', [], 'ar')); ?>">
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label mb-0"><?php echo e(__('branding.cart_policy_body_en')); ?></label>
                                    <button type="button" class="btn btn-link btn-sm p-0" id="load-cart-policy-en"><?php echo e(__('branding.load_cart_policy_sample')); ?> (EN)</button>
                                </div>
                                <textarea name="cart_policy_body_en" id="cart_policy_body_en" class="form-control" rows="5"
                                          placeholder="<?php echo e(__('branding.cart_policy_placeholder')); ?>"><?php echo e(old('cart_policy_body_en', $settings->cart_policy_body_en)); ?></textarea>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label mb-0"><?php echo e(__('branding.cart_policy_body_ar')); ?></label>
                                    <button type="button" class="btn btn-link btn-sm p-0" id="load-cart-policy-ar"><?php echo e(__('branding.load_cart_policy_sample')); ?> (AR)</button>
                                </div>
                                <textarea name="cart_policy_body_ar" id="cart_policy_body_ar" class="form-control" rows="5" dir="rtl"
                                          placeholder="<?php echo e(__('branding.cart_policy_placeholder')); ?>"><?php echo e(old('cart_policy_body_ar', $settings->cart_policy_body_ar)); ?></textarea>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary"><?php echo e(__('branding.save')); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
(function () {
    const notesEn = <?php echo json_encode(__('invoice_sample.notes_plain', [], 'en')) ?>;
    const notesAr = <?php echo json_encode(__('invoice_sample.notes_plain', [], 'ar')) ?>;
    const policyTitleEn = <?php echo json_encode(__('cart.policy_title_default', [], 'en')) ?>;
    const policyTitleAr = <?php echo json_encode(__('cart.policy_title_default', [], 'ar')) ?>;
    const policyBodyEn = <?php echo json_encode(__('cart.policy_body_default', [], 'en')) ?>;
    const policyBodyAr = <?php echo json_encode(__('cart.policy_body_default', [], 'ar')) ?>;

    document.getElementById('load-invoice-notes-en')?.addEventListener('click', () => {
        if (confirm('Replace English invoice notes with the sample?')) {
            document.getElementById('invoice_notes_en').value = notesEn;
        }
    });
    document.getElementById('load-invoice-notes-ar')?.addEventListener('click', () => {
        if (confirm('Replace Arabic invoice notes with the sample?')) {
            document.getElementById('invoice_notes_ar').value = notesAr;
        }
    });
    document.getElementById('load-cart-policy-en')?.addEventListener('click', () => {
        if (confirm('Replace English cart policy with the sample?')) {
            document.querySelector('input[name="cart_policy_title_en"]').value = policyTitleEn;
            document.getElementById('cart_policy_body_en').value = policyBodyEn;
        }
    });
    document.getElementById('load-cart-policy-ar')?.addEventListener('click', () => {
        if (confirm('Replace Arabic cart policy with the sample?')) {
            document.querySelector('input[name="cart_policy_title_ar"]').value = policyTitleAr;
            document.getElementById('cart_policy_body_ar').value = policyBodyAr;
        }
    });
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
<?php /**PATH C:\hayah\resources\views/admin/settings/branding.blade.php ENDPATH**/ ?>