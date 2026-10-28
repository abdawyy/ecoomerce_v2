<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'dark' => false,
    'href' => null,
    'link' => true,
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'dark' => false,
    'href' => null,
    'link' => true,
]); ?>
<?php foreach (array_filter(([
    'dark' => false,
    'href' => null,
    'link' => true,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
    $url = $branding->logoUrl($dark);
    $alt = $branding->logoAlt();
    $href = $href ?? url('/');
?>

<?php if($link): ?>
    <a href="<?php echo e($href); ?>" class="branding-logo-link d-inline-block">
        <img src="<?php echo e($url); ?>"
             alt="<?php echo e($alt); ?>"
             <?php echo e($attributes->merge(['class' => 'branding-logo-img', 'style' => 'max-width: 160px; height: auto; object-fit: contain;'])); ?>>
    </a>
<?php else: ?>
    <img src="<?php echo e($url); ?>"
         alt="<?php echo e($alt); ?>"
         <?php echo e($attributes->merge(['class' => 'branding-logo-img', 'style' => 'max-width: 160px; height: auto; object-fit: contain;'])); ?>>
<?php endif; ?>
<?php /**PATH C:\hayah\resources\views/components/branding/logo.blade.php ENDPATH**/ ?>