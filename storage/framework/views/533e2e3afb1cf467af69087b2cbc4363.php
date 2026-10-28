<?php
    $idPrefix = $idPrefix ?? 'd';
    $linkDismiss = !empty($linkDismiss);
    $dismissAttr = $linkDismiss ? ' data-bs-dismiss="offcanvas"' : '';

    $open = [
        'category' => (bool) $id,
        'price' => request()->filled('min_price') || request()->filled('max_price'),
        'type' => request()->filled('type_id'),
        'color' => request()->filled('color'),
        'size' => request()->filled('size'),
        'stock' => request()->filled('stock_status'),
    ];
?>


<div class="filter-section">
    <button class="filter-section-toggle" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#<?php echo e($idPrefix); ?>-filter-category"
            aria-expanded="<?php echo e($open['category'] ? 'true' : 'false'); ?>"
            aria-controls="<?php echo e($idPrefix); ?>-filter-category">
        <span><?php echo e(__('web.category')); ?></span>
        <i class="bi bi-chevron-down" aria-hidden="true"></i>
    </button>
    <div class="collapse <?php echo e($open['category'] ? 'show' : ''); ?>" id="<?php echo e($idPrefix); ?>-filter-category">
        <nav class="nav flex-column nav-pills">
            <a href="<?php echo e(route('product.List')); ?>"
               class="nav-link <?php echo e(!$id ? 'active' : ''); ?>"<?php echo $dismissAttr; ?>>
                <?php echo e(__('web.all_products')); ?>

            </a>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('product.List', ['id' => $category->id])); ?>"
                   class="nav-link <?php echo e($id == $category->id ? 'active' : ''); ?>"<?php echo $dismissAttr; ?>>
                    <?php echo e($category->name); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </nav>
    </div>
</div>


<div class="filter-section">
    <button class="filter-section-toggle" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#<?php echo e($idPrefix); ?>-filter-price"
            aria-expanded="<?php echo e($open['price'] ? 'true' : 'false'); ?>"
            aria-controls="<?php echo e($idPrefix); ?>-filter-price">
        <span><?php echo e(__('web.price')); ?></span>
        <i class="bi bi-chevron-down" aria-hidden="true"></i>
    </button>
    <div class="collapse <?php echo e($open['price'] ? 'show' : ''); ?>" id="<?php echo e($idPrefix); ?>-filter-price">
        <div class="price-inputs">
            <input type="number" name="min_price" placeholder="<?php echo e(__('web.min_price')); ?>" value="<?php echo e(request('min_price')); ?>" min="0">
            <span class="price-separator">-</span>
            <input type="number" name="max_price" placeholder="<?php echo e(__('web.max_price')); ?>" value="<?php echo e(request('max_price')); ?>" min="0" max="<?php echo e($maxProductPrice); ?>">
        </div>
    </div>
</div>


<?php if((is_array($types) && count($types)) || (is_object($types) && $types->count())): ?>
<div class="filter-section">
    <button class="filter-section-toggle" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#<?php echo e($idPrefix); ?>-filter-type"
            aria-expanded="<?php echo e($open['type'] ? 'true' : 'false'); ?>"
            aria-controls="<?php echo e($idPrefix); ?>-filter-type">
        <span><?php echo e(__('products.type')); ?></span>
        <i class="bi bi-chevron-down" aria-hidden="true"></i>
    </button>
    <div class="collapse <?php echo e($open['type'] ? 'show' : ''); ?>" id="<?php echo e($idPrefix); ?>-filter-type">
        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="type_id" value="<?php echo e($type->id); ?>"
                       id="<?php echo e($idPrefix); ?>-type-<?php echo e($type->id); ?>" <?php echo e(request('type_id') == $type->id ? 'checked' : ''); ?>>
                <label class="form-check-label" for="<?php echo e($idPrefix); ?>-type-<?php echo e($type->id); ?>">
                    <?php echo e($type->name); ?>

                </label>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>


<?php if((is_array($colors) && count($colors)) || (is_object($colors) && $colors->count())): ?>
<div class="filter-section">
    <button class="filter-section-toggle" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#<?php echo e($idPrefix); ?>-filter-color"
            aria-expanded="<?php echo e($open['color'] ? 'true' : 'false'); ?>"
            aria-controls="<?php echo e($idPrefix); ?>-filter-color">
        <span><?php echo e(__('web.color')); ?></span>
        <i class="bi bi-chevron-down" aria-hidden="true"></i>
    </button>
    <div class="collapse <?php echo e($open['color'] ? 'show' : ''); ?>" id="<?php echo e($idPrefix); ?>-filter-color">
        <?php $__currentLoopData = $colors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="color" value="<?php echo e($c); ?>"
                       id="<?php echo e($idPrefix); ?>-color-<?php echo e($c); ?>" <?php echo e(request('color') == $c ? 'checked' : ''); ?>>
                <label class="form-check-label" for="<?php echo e($idPrefix); ?>-color-<?php echo e($c); ?>">
                    <?php echo e($c); ?>

                </label>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>


<?php if((is_array($sizes) && count($sizes)) || (is_object($sizes) && $sizes->count())): ?>
<div class="filter-section">
    <button class="filter-section-toggle" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#<?php echo e($idPrefix); ?>-filter-size"
            aria-expanded="<?php echo e($open['size'] ? 'true' : 'false'); ?>"
            aria-controls="<?php echo e($idPrefix); ?>-filter-size">
        <span><?php echo e(__('web.size')); ?></span>
        <i class="bi bi-chevron-down" aria-hidden="true"></i>
    </button>
    <div class="collapse <?php echo e($open['size'] ? 'show' : ''); ?>" id="<?php echo e($idPrefix); ?>-filter-size">
        <?php $__currentLoopData = $sizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="size" value="<?php echo e($s); ?>"
                       id="<?php echo e($idPrefix); ?>-size-<?php echo e($s); ?>" <?php echo e(request('size') == $s ? 'checked' : ''); ?>>
                <label class="form-check-label" for="<?php echo e($idPrefix); ?>-size-<?php echo e($s); ?>">
                    <?php echo e($s); ?>

                </label>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>


<div class="filter-section">
    <button class="filter-section-toggle" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#<?php echo e($idPrefix); ?>-filter-stock"
            aria-expanded="<?php echo e($open['stock'] ? 'true' : 'false'); ?>"
            aria-controls="<?php echo e($idPrefix); ?>-filter-stock">
        <span><?php echo e(__('web.stock')); ?></span>
        <i class="bi bi-chevron-down" aria-hidden="true"></i>
    </button>
    <div class="collapse <?php echo e($open['stock'] ? 'show' : ''); ?>" id="<?php echo e($idPrefix); ?>-filter-stock">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="stock_status" value="in_stock"
                   id="<?php echo e($idPrefix); ?>-in-stock" <?php echo e(request('stock_status') == 'in_stock' ? 'checked' : ''); ?>>
            <label class="form-check-label" for="<?php echo e($idPrefix); ?>-in-stock">
                <?php echo e(__('web.in_stock')); ?>

            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="stock_status" value="out_of_stock"
                   id="<?php echo e($idPrefix); ?>-out-stock" <?php echo e(request('stock_status') == 'out_of_stock' ? 'checked' : ''); ?>>
            <label class="form-check-label" for="<?php echo e($idPrefix); ?>-out-stock">
                <?php echo e(__('web.out_of_stock')); ?>

            </label>
        </div>
    </div>
</div>


<div class="filter-section">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="on_sale" value="1" id="<?php echo e($idPrefix); ?>-on-sale"
               <?php echo e(($onSale ?? false) ? 'checked' : ''); ?>>
        <label class="form-check-label" for="<?php echo e($idPrefix); ?>-on-sale"><?php echo e(__('web.on_sale')); ?></label>
    </div>
</div>
<?php /**PATH C:\hayah\resources\views/product/partials/filters.blade.php ENDPATH**/ ?>