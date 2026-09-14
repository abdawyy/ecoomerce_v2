<?php
    $locale = app()->getLocale();

    function getProductFromCartItem($item)
    {
        if ($item instanceof \App\Models\shoppingCart) {
            return $item->product;
        } elseif (is_array($item)) {
            return \App\Models\products::find($item['product_id']);
        } elseif (is_object($item)) {
            return \App\Models\products::find($item->product->id ?? null);
        }

        return null;
    }

    function getProductItemSize($item) {
        $sizeId = is_array($item) ? ($item['size_id'] ?? null) : ($item->size_id ?? null);
        $productItem = \App\Models\productItems::find($sizeId);
        return $productItem?->size ?? '-';
    }

    function getCartQuantity($item) {
        return $item instanceof \App\Models\shoppingCart
            ? $item->quantity
            : ($item->quantity ?? 1);
    }

    function getCartItemPrice($product, $quantity) {
        $price = $product->price ?? 0;
        $sale = $product->sale ?? 0;
        $discounted = $price - ($price * $sale / 100);
        return $discounted * $quantity;
    }

    $isGuestCart = ! auth()->check();
?>

<?php if (isset($component)) { $__componentOriginal628d23ec5db1173b09efbdbd01a50602 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal628d23ec5db1173b09efbdbd01a50602 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.web.layout','data' => ['seo' => $seo ?? null,'title' => __('cart.title')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('web.layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['seo' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($seo ?? null),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('cart.title'))]); ?>

<section id="cart-page" class="pb-5">
    <div class="container pb-5">
        <?php if (isset($component)) { $__componentOriginale2028735fc96043290609e775a32955c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale2028735fc96043290609e775a32955c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.web.checkout-stepper','data' => ['current' => 'cart']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('web.checkout-stepper'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['current' => 'cart']); ?>
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

        <?php if($isGuestCart && count($cartItems) > 0): ?>
            <div class="alert alert-light border small mb-4">
                <?php echo e(__('cart.guest_cart_hint')); ?>

                <a href="<?php echo e(route('login')); ?>" class="fw-bold"><?php echo e(__('cart.login_to_save')); ?></a>
            </div>
        <?php endif; ?>

        <div class="row pt-2">
            <div class="col-12 col-lg-7">
                <?php if(count($cartItems) > 0): ?>
                    <h1 class="fw-bolder fs-3">
                        <?php echo e(__('cart.your_cart')); ?>

                        <span class="fc-gray fw-normal fs-4">(<?php echo e(count($cartItems)); ?> <?php echo e(__('cart.items')); ?>)</span>
                    </h1>
                <?php else: ?>
                    <h1 class="fw-bolder fs-3"><?php echo e(__('cart.empty')); ?></h1>
                    <p class="text-muted"><?php echo e(__('cart.empty_cta')); ?></p>
                <?php endif; ?>

                <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $isGuestItem = !($item instanceof \App\Models\shoppingCart);
                        $product = getProductFromCartItem($item);
                        $quantity = getCartQuantity($item);
                        $priceTotal = getCartItemPrice($product, $quantity);
                        $size = getProductItemSize($item);
                    ?>
                    <div class="card mb-3 shadow-sm rounded-3 border-0">
                        <div class="card-body p-3">
                            <div class="d-flex gap-3 align-items-start">
                                <div class="cart-img rounded-3 overflow-hidden bg-light d-flex align-items-center justify-content-center"
                                     style="width:110px; height:110px; flex-shrink:0;">
                                    <?php if (isset($component)) { $__componentOriginal3e482043bebb43455e92f4601a40e860 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3e482043bebb43455e92f4601a40e860 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.branding.product-image','data' => ['product' => $product,'class' => 'img-fluid','style' => 'max-height:100%; max-width:100%;']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('branding.product-image'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['product' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($product),'class' => 'img-fluid','style' => 'max-height:100%; max-width:100%;']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3e482043bebb43455e92f4601a40e860)): ?>
<?php $attributes = $__attributesOriginal3e482043bebb43455e92f4601a40e860; ?>
<?php unset($__attributesOriginal3e482043bebb43455e92f4601a40e860); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3e482043bebb43455e92f4601a40e860)): ?>
<?php $component = $__componentOriginal3e482043bebb43455e92f4601a40e860; ?>
<?php unset($__componentOriginal3e482043bebb43455e92f4601a40e860); ?>
<?php endif; ?>
                                </div>

                                <div class="flex-grow-1 w-100">
                                    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                                        <div class="me-2">
                                            <h5 class="card-title mb-1"><?php echo e($product->name ?? 'Product'); ?></h5>
                                            <div class="text-muted small">
                                                <span><?php echo e(__('cart.color')); ?>: <?php echo e($product->color ?? '-'); ?></span>
                                                &middot;
                                                <span><?php echo e(__('cart.size')); ?>: <?php echo e($size); ?></span>
                                            </div>
                                        </div>
                                        <div class="text-end mt-2 mt-md-0">
                                            <h5 class="fw-bolder mb-0">LE <?php echo e(number_format($priceTotal, 2)); ?></h5>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div>
                                            <?php if(!$isGuestItem): ?>
                                                <form action="<?php echo e(route('cart.update', $item->id)); ?>" method="POST" class="d-flex align-items-center gap-1 qty-update-form">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="button" class="btn btn-outline-secondary cart-qty-btn qty-minus" data-target="qty-<?php echo e($item->id); ?>">−</button>
                                                    <input type="number" name="quantity" id="qty-<?php echo e($item->id); ?>" value="<?php echo e($quantity); ?>" min="1" class="form-control form-control-sm text-center" style="width:52px">
                                                    <button type="button" class="btn btn-outline-secondary cart-qty-btn qty-plus" data-target="qty-<?php echo e($item->id); ?>">+</button>
                                                    <button type="submit" class="btn btn-sm btn-outline-dark ms-1"><?php echo e(__('cart.update')); ?></button>
                                                </form>
                                            <?php else: ?>
                                                <form action="<?php echo e(route('cart.guest.update', $item->key)); ?>" method="POST" class="d-flex align-items-center gap-1 qty-update-form">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="button" class="btn btn-outline-secondary cart-qty-btn qty-minus" data-target="qty-g-<?php echo e($loop->index); ?>">−</button>
                                                    <input type="number" name="quantity" id="qty-g-<?php echo e($loop->index); ?>" value="<?php echo e($quantity); ?>" min="1" class="form-control form-control-sm text-center" style="width:52px">
                                                    <button type="button" class="btn btn-outline-secondary cart-qty-btn qty-plus" data-target="qty-g-<?php echo e($loop->index); ?>">+</button>
                                                    <button type="submit" class="btn btn-sm btn-outline-dark ms-1"><?php echo e(__('cart.update')); ?></button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <?php if(!$isGuestItem): ?>
                                                <form action="<?php echo e(route('cart.delete', $item->id)); ?>" method="POST" class="d-inline cart-delete-form">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-link text-danger p-0 border-0" aria-label="<?php echo e(__('cart.delete')); ?>">
                                                        <i class="fa-regular fa-trash-can fs-5"></i>
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <form action="<?php echo e(route('cart.guest.delete', $item->key)); ?>" method="POST" class="d-inline cart-delete-form">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn btn-link text-danger p-0 border-0" aria-label="<?php echo e(__('cart.delete')); ?>">
                                                        <i class="fa-regular fa-trash-can fs-5"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php if(count($cartItems) === 0 && ($featuredProducts ?? collect())->isNotEmpty()): ?>
                    <div class="row g-3 g-md-4 mt-2">
                        <?php $__currentLoopData = $featuredProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-6 col-lg-3">
                                <?php if (isset($component)) { $__componentOriginal9dbde93c50e49c2830e855fc2dc390af = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9dbde93c50e49c2830e855fc2dc390af = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.web.product-card','data' => ['product' => $product]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('web.product-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['product' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($product)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9dbde93c50e49c2830e855fc2dc390af)): ?>
<?php $attributes = $__attributesOriginal9dbde93c50e49c2830e855fc2dc390af; ?>
<?php unset($__attributesOriginal9dbde93c50e49c2830e855fc2dc390af); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9dbde93c50e49c2830e855fc2dc390af)): ?>
<?php $component = $__componentOriginal9dbde93c50e49c2830e855fc2dc390af; ?>
<?php unset($__componentOriginal9dbde93c50e49c2830e855fc2dc390af); ?>
<?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="text-center mt-4">
                        <a href="<?php echo e(route('product.List')); ?>" class="btn btn-dark"><?php echo e(__('web.shop_now')); ?></a>
                    </div>
                <?php endif; ?>
            </div>

            <?php if($total > 0): ?>
                <div class="col-12 col-lg-5 mb-4">
                    <div style="position: sticky; top: 100px;">
                        <h5 class="fw-bolder fs-4 mb-3"><?php echo e(__('cart.summary')); ?></h5>
                        <div class="card shadow-sm rounded-3 border-0">
                            <div class="card-body p-4">
                                <form action="<?php echo e(route('cart.promo')); ?>" method="POST" class="mb-3">
                                    <?php echo csrf_field(); ?>
                                    <label class="form-label small"><?php echo e(__('cart.promo_code')); ?></label>
                                    <div class="input-group">
                                        <input type="text" name="promo_code" class="form-control" value="<?php echo e($appliedPromo ?? ''); ?>" placeholder="<?php echo e(__('checkout.enter_promo')); ?>">
                                        <button class="btn btn-outline-dark" type="submit"><?php echo e(__('cart.apply_promo')); ?></button>
                                    </div>
                                </form>

                                <h6 class="fw-semibold text-muted mb-3"><?php echo e(__('cart.your_order')); ?></h6>
                                <div class="d-flex justify-content-between align-items-center pb-2">
                                    <p class="mb-0 text-muted small"><?php echo e(__('cart.subtotal')); ?></p>
                                    <p class="fw-bolder mb-0">LE <?php echo e(number_format($subtotal, 2)); ?></p>
                                </div>

                                <hr>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bolder mb-0"><?php echo e(__('cart.total')); ?></h6>
                                    <h6 class="fw-bolder mb-0">LE <?php echo e(number_format($total, 2)); ?></h6>
                                </div>

                                <a href="<?php echo e(route('checkout')); ?>" class="btn btn-primary w-100 mt-3 py-2">
                                    <?php echo e(__('cart.checkout')); ?>

                                </a>
                                <p class="cart-trust-line mb-0"><?php echo e(__('cart.trust_line')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php if($branding->hasCartPolicy()): ?>
            <div class="cart-policy-panel mt-4" dir="<?php echo e(app()->getLocale() === 'ar' ? 'rtl' : 'ltr'); ?>">
                <h2 class="cart-policy-title"><?php echo e($branding->cartPolicyTitle()); ?></h2>
                <?php $__currentLoopData = $branding->cartPolicyParagraphs(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paragraph): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <p class="cart-policy-text"><?php echo e($paragraph); ?></p>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php $__env->startPush('scripts'); ?>
<script>
    document.querySelectorAll('.qty-minus, .qty-plus').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.getElementById(btn.dataset.target);
            if (!input) return;
            const val = parseInt(input.value, 10) || 1;
            input.value = Math.max(1, val + (btn.classList.contains('qty-plus') ? 1 : -1));
        });
    });
    document.querySelectorAll('.cart-delete-form').forEach(form => {
        form.addEventListener('submit', e => {
            if (!confirm(<?php echo json_encode(__('cart.remove_confirm'), 15, 512) ?>)) e.preventDefault();
        });
    });
</script>
<?php $__env->stopPush(); ?>

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
<?php /**PATH C:\hayah\resources\views/cart/index.blade.php ENDPATH**/ ?>