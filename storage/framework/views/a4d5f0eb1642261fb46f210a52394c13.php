<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'href',
    'label' => null,
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'href',
    'label' => null,
]); ?>
<?php foreach (array_filter(([
    'href',
    'label' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<a href="<?php echo e($href); ?>" <?php echo e($attributes->merge(['class' => 'btn btn-primary btn-sm'])); ?>>
    <?php echo e($label ?? __('table.view')); ?>

</a>
<?php /**PATH C:\hayah\resources\views/components/admin/view-link.blade.php ENDPATH**/ ?>