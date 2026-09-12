<footer class="storefront-footer text-light border-top border-secondary">
    <div class="container py-5">
        <div class="row gy-4">
            <div class="col-6 col-md-3">
                <h6 class="footer-heading"><?php echo e(__('web.footer_shop')); ?></h6>
                <a href="<?php echo e(route('product.List')); ?>" class="footer-link"><?php echo e(__('web.all_products')); ?></a>
                <?php $__currentLoopData = $categories->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('product.List', ['id' => $category->id])); ?>" class="footer-link"><?php echo e($category->name); ?></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="col-6 col-md-3">
                <h6 class="footer-heading"><?php echo e(__('web.footer_help')); ?></h6>
                <a href="<?php echo e(route('contact.show')); ?>" class="footer-link"><?php echo e(__('web.title_contact')); ?></a>
                <a href="<?php echo e(route('guides.index')); ?>" class="footer-link"><?php echo e(__('web.footer_guides')); ?></a>
                <a href="<?php echo e(route('cart.index')); ?>" class="footer-link"><?php echo e(__('web.footer_cart')); ?></a>
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('account.orders')); ?>" class="footer-link"><?php echo e(__('account.orders')); ?></a>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="footer-link"><?php echo e(__('account.login')); ?></a>
                <?php endif; ?>
            </div>

            <div class="col-6 col-md-3">
                <h6 class="footer-heading"><?php echo e(__('web.footer_legal')); ?></h6>
                <a href="<?php echo e(route('legal')); ?>" class="footer-link"><?php echo e(__('web.terms_title')); ?></a>
                <a href="<?php echo e(route('legal')); ?>#privacy" class="footer-link"><?php echo e(__('web.privacy_title')); ?></a>
                <a href="<?php echo e(route('legal')); ?>#cookies" class="footer-link"><?php echo e(__('web.footer_cookies')); ?></a>
            </div>

            <div class="col-12 col-md-3">
                <?php if($branding->tagline()): ?>
                    <p class="small text-white-50 mb-3"><?php echo e($branding->tagline()); ?></p>
                <?php endif; ?>
                <?php if(count($branding->socialLinks()) > 0): ?>
                    <h6 class="footer-heading"><?php echo e(__('web.footer_social')); ?></h6>
                    <?php if (isset($component)) { $__componentOriginal0aa1063d50b4188a0392a8b11a15ace4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0aa1063d50b4188a0392a8b11a15ace4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.web.social-links','data' => ['class' => 'd-flex gap-3 mb-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('web.social-links'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'd-flex gap-3 mb-3']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0aa1063d50b4188a0392a8b11a15ace4)): ?>
<?php $attributes = $__attributesOriginal0aa1063d50b4188a0392a8b11a15ace4; ?>
<?php unset($__attributesOriginal0aa1063d50b4188a0392a8b11a15ace4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0aa1063d50b4188a0392a8b11a15ace4)): ?>
<?php $component = $__componentOriginal0aa1063d50b4188a0392a8b11a15ace4; ?>
<?php unset($__componentOriginal0aa1063d50b4188a0392a8b11a15ace4); ?>
<?php endif; ?>
                <?php endif; ?>
                <p class="mb-0 small text-white-50"><?php echo __('web.footer_rights'); ?></p>
            </div>
        </div>
    </div>
</footer>

<style>
    #toast-container > .toast {
        background-color: #111 !important;
        color: #fff !important;
        opacity: 1 !important;
        box-shadow: 0 10px 24px rgba(0,0,0,0.25) !important;
        border-radius: 10px !important;
        background-image: none !important;
    }

    #toast-container > .toast-success { background-color: #1f9d55 !important; background-image: none !important; }
    #toast-container > .toast-error { background-color: #dc3545 !important; background-image: none !important; }
    #toast-container > .toast-warning { background-color: #f59e0b !important; background-image: none !important; }
    #toast-container > .toast-info { background-color: #0ea5e9 !important; background-image: none !important; }
    #toast-container > .toast .toast-message { color: #fff !important; }
    #toast-container > .toast .toast-close-button { color: #fff !important; opacity: 0.8 !important; }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="<?php echo e(asset('assets/js/bootstrap.bundle.min.js')); ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="<?php echo e(asset('assets/js/main.js')); ?>"></script>

<script>
    $(document).ready(function() {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            newestOnTop: true,
            positionClass: "toast-top-center",
            timeOut: 4000
        };
        window.__flashShown = window.__flashShown || {};
        <?php if(session('success')): ?>
            if (!window.__flashShown.success) {
                toastr.success(<?php echo json_encode(session('success'), 15, 512) ?>);
                window.__flashShown.success = true;
            }
        <?php endif; ?>

        <?php if(session('error')): ?>
            if (!window.__flashShown.error) {
                toastr.error(<?php echo json_encode(session('error'), 15, 512) ?>);
                window.__flashShown.error = true;
            }
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                if (!window.__flashShown['err_<?php echo e(md5($error)); ?>']) {
                    toastr.error(<?php echo json_encode($error, 15, 512) ?>);
                    window.__flashShown['err_<?php echo e(md5($error)); ?>'] = true;
                }
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    });
</script>
<?php /**PATH C:\hayah\resources\views/components/web/footer.blade.php ENDPATH**/ ?>