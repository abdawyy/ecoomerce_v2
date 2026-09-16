<?php if (isset($component)) { $__componentOriginal9922b99e82ca32bf450dbd7628945e59 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9922b99e82ca32bf450dbd7628945e59 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.account.layout','data' => ['title' => __('account.orders')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('account.layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('account.orders'))]); ?>
    <?php if($orders->isEmpty()): ?>
        <div class="account-card account-empty">
            <i class="fa-solid fa-box-open d-block"></i>
            <h5 class="fw-bold"><?php echo e(__('account.no_orders_yet')); ?></h5>
            <a href="<?php echo e(route('product.List')); ?>" class="btn btn-dark mt-2"><?php echo e(__('account.shop_now')); ?></a>
        </div>
    <?php else: ?>
        <div class="account-card overflow-hidden">
            <div class="table-responsive">
                <table class="table account-table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo e(__('account.order_date')); ?></th>
                            <th><?php echo e(__('account.order_status')); ?></th>
                            <th><?php echo e(__('account.order_total')); ?></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="fw-semibold">#<?php echo e($order->id); ?></td>
                                <td class="text-muted small"><?php echo e($order->created_at?->format('Y-m-d H:i')); ?></td>
                                <td><?php if (isset($component)) { $__componentOriginal6f9becef261eae291af224a6dc244a32 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6f9becef261eae291af224a6dc244a32 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.account.status-pill','data' => ['status' => $order->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('account.status-pill'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($order->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6f9becef261eae291af224a6dc244a32)): ?>
<?php $attributes = $__attributesOriginal6f9becef261eae291af224a6dc244a32; ?>
<?php unset($__attributesOriginal6f9becef261eae291af224a6dc244a32); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6f9becef261eae291af224a6dc244a32)): ?>
<?php $component = $__componentOriginal6f9becef261eae291af224a6dc244a32; ?>
<?php unset($__componentOriginal6f9becef261eae291af224a6dc244a32); ?>
<?php endif; ?></td>
                                <td class="fw-bold"><?php echo e(number_format($order->total_amount, 2)); ?> <?php echo e(__('account.currency')); ?></td>
                                <td class="text-end">
                                    <a href="<?php echo e(route('account.orders.show', $order->id)); ?>" class="btn btn-sm btn-outline-dark">
                                        <?php echo e(__('account.view_order')); ?>

                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            <?php echo e($orders->links()); ?>

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
<?php /**PATH C:\hayah\resources\views/account/orders/index.blade.php ENDPATH**/ ?>