<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['current' => 'details']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['current' => 'details']); ?>
<?php foreach (array_filter((['current' => 'details']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
    $steps = [
        'cart' => ['label' => __('checkout.step_cart'), 'route' => route('cart.index')],
        'details' => ['label' => __('checkout.step_details'), 'route' => route('checkout')],
        'confirm' => ['label' => __('checkout.step_confirm'), 'route' => null],
    ];
    $keys = array_keys($steps);
    $currentIndex = array_search($current, $keys, true);
    if ($currentIndex === false) {
        $currentIndex = 1;
    }
?>

<nav class="checkout-stepper mb-4 mb-md-5" aria-label="<?php echo e(__('checkout.progress')); ?>">
    <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $index = $loop->index;
            $isDone = $index < $currentIndex;
            $isCurrent = $index === $currentIndex;
        ?>
        <div class="checkout-step <?php echo e($isDone ? 'is-done' : ''); ?> <?php echo e($isCurrent ? 'is-current' : ''); ?>">
            <span class="checkout-step-dot" aria-hidden="true"><?php echo e($index + 1); ?></span>
            <?php if($isDone && $step['route']): ?>
                <a href="<?php echo e($step['route']); ?>" class="checkout-step-link"><?php echo e($step['label']); ?></a>
            <?php else: ?>
                <span class="checkout-step-label"><?php echo e($step['label']); ?></span>
            <?php endif; ?>
        </div>
        <?php if(! $loop->last): ?>
            <div class="checkout-step-line <?php echo e($index < $currentIndex ? 'is-done' : ''); ?>"></div>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</nav>
<?php /**PATH C:\hayah\resources\views/components/web/checkout-stepper.blade.php ENDPATH**/ ?>