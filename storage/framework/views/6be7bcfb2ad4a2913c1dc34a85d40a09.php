<?php if (isset($component)) { $__componentOriginal9922b99e82ca32bf450dbd7628945e59 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9922b99e82ca32bf450dbd7628945e59 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.account.layout','data' => ['title' => __('account.dashboard')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('account.layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('account.dashboard'))]); ?>
    <p class="text-muted mb-4"><?php echo e(__('account.welcome', ['name' => $user->name])); ?></p>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="account-card account-kpi h-100">
                <div class="small text-muted text-uppercase fw-semibold mb-1"><?php echo e(__('account.total_orders')); ?></div>
                <div class="account-kpi-value"><?php echo e($orderCount); ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="account-card account-kpi h-100">
                <div class="small text-muted text-uppercase fw-semibold mb-1"><?php echo e(__('account.latest_order')); ?></div>
                <?php if($latestOrder): ?>
                    <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap mt-2">
                        <div>
                            <div class="fw-bold"><?php echo e(__('account.order_number', ['id' => $latestOrder->id])); ?></div>
                            <?php if (isset($component)) { $__componentOriginal6f9becef261eae291af224a6dc244a32 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6f9becef261eae291af224a6dc244a32 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.account.status-pill','data' => ['status' => $latestOrder->status,'class' => 'mt-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('account.status-pill'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($latestOrder->status),'class' => 'mt-1']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6f9becef261eae291af224a6dc244a32)): ?>
<?php $attributes = $__attributesOriginal6f9becef261eae291af224a6dc244a32; ?>
<?php unset($__attributesOriginal6f9becef261eae291af224a6dc244a32); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6f9becef261eae291af224a6dc244a32)): ?>
<?php $component = $__componentOriginal6f9becef261eae291af224a6dc244a32; ?>
<?php unset($__componentOriginal6f9becef261eae291af224a6dc244a32); ?>
<?php endif; ?>
                        </div>
                        <a href="<?php echo e(route('account.orders.show', $latestOrder->id)); ?>" class="btn btn-dark btn-sm">
                            <?php echo e(__('account.view_order')); ?>

                        </a>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-0 mt-2"><?php echo e(__('account.no_orders_yet')); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if($orderCount === 0): ?>
        <div class="account-card account-empty">
            <i class="fa-solid fa-bag-shopping d-block"></i>
            <h5 class="fw-bold"><?php echo e(__('account.no_orders_yet')); ?></h5>
            <a href="<?php echo e(route('product.List')); ?>" class="btn btn-dark mt-2"><?php echo e(__('account.shop_now')); ?></a>
        </div>
    <?php else: ?>
        <div class="d-flex justify-content-end">
            <a href="<?php echo e(route('account.orders')); ?>" class="btn btn-outline-dark"><?php echo e(__('account.view_all_orders')); ?></a>
        </div>
    <?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9922b99e82ca32bf450dbd7628945e59)): ?>
<?php $attributes = $__attributesOriginal9922b99e82ca32bf450dbd7628945e59; ?>
<?php unset($__attributesOriginal9922b99e82ca32bf450dbd7628945e59); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9922b99e82ca32bf450dbd7628945e59)): ?>
<?php $component = $__componentOriginal9922b99e82ca32bf450dbd7628945e59; ?>
<?php unset($__componentOriginal9922b99e82ca32bf450dbd7628945e59); ?>
<?php endif; ?>
<?php /**PATH C:\hayah\resources\views/account/dashboard.blade.php ENDPATH**/ ?>