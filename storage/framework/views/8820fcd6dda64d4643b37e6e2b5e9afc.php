<?php if (isset($component)) { $__componentOriginal628d23ec5db1173b09efbdbd01a50602 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal628d23ec5db1173b09efbdbd01a50602 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.web.layout','data' => ['title' => __('confirmation.title')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('web.layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('confirmation.title'))]); ?>

<section class="checkout-page pb-5">
    <div class="container">
        <div class="checkout-hero mb-4">
            <img src="<?php echo e($branding->heroImageUrl()); ?>" alt="<?php echo e($branding->siteName()); ?>">
            <div class="checkout-hero-overlay">
                <p class="checkout-hero-kicker mb-1"><?php echo e($branding->tagline() ?: __('web.promo_tagline')); ?></p>
                <h1 class="checkout-hero-title mb-0"><?php echo e(__('confirmation.heading')); ?></h1>
            </div>
        </div>

        <?php if (isset($component)) { $__componentOriginale2028735fc96043290609e775a32955c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale2028735fc96043290609e775a32955c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.web.checkout-stepper','data' => ['current' => 'confirm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('web.checkout-stepper'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['current' => 'confirm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale2028735fc96043290609e775a32955c)): ?>
<?php $attributes = $__attributesOriginale2028735fc96043290609e775a32955c; ?>
<?php unset($__attributesOriginale2028735fc96043290609e775a32955c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale2028735fc96043290609e775a32955c)): ?>
<?php $component = $__componentOriginale2028735fc96043290609e775a32955c; ?>
<?php unset($__componentOriginale2028735fc96043290609e775a32955c); ?>
<?php endif; ?>

        <div class="receipt-card">
            <div class="receipt-icon"><i class="bi bi-check-lg"></i></div>
            <h2 class="h3 fw-bold mb-2"><?php echo e(__('confirmation.thank_you')); ?></h2>
            <p class="text-muted mb-4"><?php echo e(__('confirmation.description')); ?></p>

            <div class="text-start bg-light rounded-3 p-3 mb-4">
                <p class="mb-2"><?php echo e(__('confirmation.order_id')); ?> <strong>#<?php echo e($orderID); ?></strong></p>
                <p class="mb-2"><?php echo e(__('confirmation.delivery_fees')); ?> <strong><?php echo e($deliveryFees); ?> LE</strong></p>
                <p class="mb-0"><?php echo e(__('confirmation.total_price')); ?> <strong><?php echo e($totalPrice); ?> LE</strong></p>
            </div>

            <?php if(!empty($emailSent)): ?>
                <p class="small text-muted mb-4"><?php echo e(__('confirmation.email_sent')); ?></p>
            <?php elseif(!empty($emailPending)): ?>
                <p class="small text-muted mb-4"><?php echo e(__('confirmation.email_pending')); ?></p>
            <?php endif; ?>

            <div class="d-flex flex-wrap gap-2 justify-content-center">
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('account.orders.show', $orderID)); ?>" class="btn btn-dark"><?php echo e(__('account.view_in_account')); ?></a>
                <?php else: ?>
                    <?php if(!empty($isGuestCheckout)): ?>
                        <p class="w-100 small text-muted mb-2"><?php echo e(__('account.guest_checkout_hint')); ?></p>
                        <a href="<?php echo e(route('register', array_filter(['email' => $guestEmail ?? null, 'name' => $guestName ?? null]))); ?>"
                           class="btn btn-dark"><?php echo e(__('account.create_account_to_track')); ?></a>
                        <a href="<?php echo e(route('login')); ?>" class="btn btn-outline-dark"><?php echo e(__('account.login')); ?></a>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if(!empty($invoiceUrl)): ?>
                    <a href="<?php echo e($invoiceUrl); ?>" class="btn btn-outline-dark" target="_blank">
                        <i class="bi bi-file-earmark-pdf me-1"></i><?php echo e(__('confirmation.download_invoice')); ?>

                    </a>
                <?php endif; ?>

                <a href="<?php echo e(route('product.List')); ?>" class="btn btn-primary"><?php echo e(__('confirmation.back_shop')); ?></a>
            </div>
        </div>
    </div>
</section>

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
<?php /**PATH C:\hayah\resources\views/checkout/receipt.blade.php ENDPATH**/ ?>