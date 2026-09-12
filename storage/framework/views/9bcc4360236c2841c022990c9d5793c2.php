<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'product',
    'showCartOverlay' => true,
    'imageClass' => 'product-img',
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'product',
    'showCartOverlay' => true,
    'imageClass' => 'product-img',
]); ?>
<?php foreach (array_filter(([
    'product',
    'showCartOverlay' => true,
    'imageClass' => 'product-img',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
    $image = $product->productImages->isNotEmpty()
        ? asset('storage/' . $product->productImages->first()->images)
        : $branding->placeholderProductUrl();
    $salePrice = $product->sale
        ? $product->price - ($product->price * $product->sale / 100)
        : null;
?>

<div <?php echo e($attributes->merge(['class' => 'product-card h-100'])); ?>>
    <div class="image-wrapper">
        <?php if($product->sale): ?>
            <div class="sale-badge">-<?php echo e($product->sale); ?>%</div>
        <?php endif; ?>

        <a href="<?php echo e(route('product.show', $product->id)); ?>">
            <img src="<?php echo e($image); ?>" class="<?php echo e($imageClass); ?>" alt="<?php echo e($product->name); ?>">
        </a>

        <?php if($showCartOverlay): ?>
            <a href="<?php echo e(route('product.show', $product->id)); ?>" class="btn-cart-overlay" aria-label="<?php echo e(__('web.add_to_cart')); ?>">
                <i class="bi bi-bag-plus-fill"></i>
            </a>
        <?php endif; ?>
    </div>

    <div class="pt-3 product-card-meta">
        <h6 class="fw-bold mb-1 product-card-title">
            <a href="<?php echo e(route('product.show', $product->id)); ?>" class="product-card-name"><?php echo e($product->name); ?></a>
        </h6>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <?php if($salePrice !== null): ?>
                <span class="fw-bold text-danger"><?php echo e(number_format($salePrice, 2)); ?> LE</span>
                <span class="text-muted text-decoration-line-through small"><?php echo e(number_format($product->price, 2)); ?></span>
            <?php else: ?>
                <span class="fw-bold"><?php echo e(number_format($product->price, 2)); ?> LE</span>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH C:\hayah\resources\views/components/web/product-card.blade.php ENDPATH**/ ?>