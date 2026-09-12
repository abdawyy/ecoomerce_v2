<?php $locale = app()->getLocale(); ?>

<?php if (isset($component)) { $__componentOriginal628d23ec5db1173b09efbdbd01a50602 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal628d23ec5db1173b09efbdbd01a50602 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.web.layout','data' => ['seo' => $seo ?? null,'title' => __('checkout.shipping_address')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('web.layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['seo' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($seo ?? null),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('checkout.shipping_address'))]); ?>

<section id="cart-page" class="checkout-page pb-5 mb-5">
    <div class="container pb-5">
        <div class="checkout-hero mb-4">
            <img src="<?php echo e($branding->heroImageUrl()); ?>" alt="<?php echo e($branding->siteName()); ?>">
            <div class="checkout-hero-overlay">
                <p class="checkout-hero-kicker mb-1"><?php echo e($branding->tagline() ?: __('web.promo_tagline')); ?></p>
                <h1 class="checkout-hero-title mb-0"><?php echo e(__('checkout.shipping_address')); ?></h1>
            </div>
        </div>

        <?php if (isset($component)) { $__componentOriginale2028735fc96043290609e775a32955c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale2028735fc96043290609e775a32955c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.web.checkout-stepper','data' => ['current' => 'details']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('web.checkout-stepper'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['current' => 'details']); ?>
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

        <div class="row pt-2">
            <div class="col-12">
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                <?php endif; ?>
            </div>

            <div class="col-12">
                <form action="<?php echo e(route('checkout.order')); ?>" method="POST" id="checkout-order-form">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="checkout_token" value="<?php echo e($checkoutToken); ?>">
                    <?php if(session('promo_code')): ?>
                        <input type="hidden" name="promo_code" value="<?php echo e(session('promo_code')); ?>">
                    <?php endif; ?>

                    <div class="row g-4">
                        <div class="col-12 col-lg-7">
                            <div class="card p-4 shadow-sm rounded-3 border-0">
                                <?php if(auth()->guard()->check()): ?>
                                    <?php if(($savedAddresses ?? collect())->isNotEmpty()): ?>
                                        <div class="mb-4">
                                            <label for="saved_address" class="form-label"><?php echo e(__('checkout.select_saved_address')); ?></label>
                                            <select id="saved_address" class="form-select">
                                                <option value=""><?php echo e(__('checkout.saved_address')); ?>…</option>
                                                <?php $__currentLoopData = $savedAddresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($address->id); ?>"
                                                        data-line1="<?php echo e($address->address_line1); ?>"
                                                        data-line2="<?php echo e($address->address_line2); ?>"
                                                        data-city="<?php echo e($address->city); ?>"
                                                        data-country="<?php echo e($address->country); ?>"
                                                        data-phone="<?php echo e($address->phone_number); ?>">
                                                        <?php echo e($address->address_line1); ?><?php if($address->is_default): ?> ★<?php endif; ?>
                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <div class="row g-3">
                                    <?php if(auth()->guard()->guest()): ?>
                                        <div class="col-12 col-md-6">
                                            <label for="email" class="form-label"><?php echo e(__('checkout.email')); ?></label>
                                            <input type="email" name="email" id="email"
                                                   class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                   value="<?php echo e(old('email')); ?>" required>
                                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="full_name" class="form-label"><?php echo e(__('checkout.full_name')); ?></label>
                                            <input type="text" name="full_name" id="full_name"
                                                   class="form-control <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                   value="<?php echo e(old('full_name')); ?>" required>
                                            <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="col-12">
                                            <label for="full_name" class="form-label"><?php echo e(__('checkout.full_name')); ?></label>
                                            <input type="text" name="full_name" id="full_name"
                                                   class="form-control <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                   value="<?php echo e(old('full_name', Auth::user()->name ?? '')); ?>" required>
                                            <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="col-12">
                                        <label for="address_line_1" class="form-label"><?php echo e(__('checkout.address_line1')); ?></label>
                                        <input type="text" name="address_line1" id="address_line_1"
                                               class="form-control <?php $__errorArgs = ['address_line1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               value="<?php echo e(old('address_line1')); ?>" required>
                                        <?php $__errorArgs = ['address_line1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div class="col-12">
                                        <label for="address_line_2" class="form-label"><?php echo e(__('checkout.address_line2')); ?></label>
                                        <input type="text" name="address_line2" id="address_line_2"
                                               class="form-control <?php $__errorArgs = ['address_line2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               value="<?php echo e(old('address_line2')); ?>">
                                        <?php $__errorArgs = ['address_line2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="city" class="form-label"><?php echo e(__('checkout.city')); ?></label>
                                        <select name="city" id="city" class="form-select <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                            <option value="" disabled <?php echo e(old('city') ? '' : 'selected'); ?>><?php echo e(__('checkout.select_city')); ?></option>
                                            <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cityOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($cityOption->id); ?>" data-fee="<?php echo e($cityOption->price); ?>"
                                                    <?php echo e(old('city') == $cityOption->id ? 'selected' : ''); ?>>
                                                    <?php echo e($cityOption->name . ' - ' . $cityOption->price . ' LE ' . __('checkout.delivery_fees')); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="country" class="form-label"><?php echo e(__('checkout.country')); ?></label>
                                        <input type="text" name="country" id="country"
                                               class="form-control <?php $__errorArgs = ['country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               value="<?php echo e(old('country')); ?>" required>
                                        <?php $__errorArgs = ['country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="phone_number" class="form-label"><?php echo e(__('checkout.phone_number')); ?></label>
                                        <input type="text" name="phone_number" id="phone_number"
                                               class="form-control <?php $__errorArgs = ['phone_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               value="<?php echo e(old('phone_number')); ?>" required>
                                        <?php $__errorArgs = ['phone_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="promo_code" class="form-label"><?php echo e(__('checkout.promo_code')); ?></label>
                                        <input type="text" name="promo_code" id="promo_code"
                                               class="form-control <?php $__errorArgs = ['promo_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               value="<?php echo e(old('promo_code', session('promo_code'))); ?>" placeholder="<?php echo e(__('checkout.enter_promo')); ?>" />
                                        <?php $__errorArgs = ['promo_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <?php if(auth()->guard()->guest()): ?>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="create_account" value="1" id="create_account" <?php echo e(old('create_account') ? 'checked' : ''); ?>>
                                                <label class="form-check-label" for="create_account"><?php echo e(__('checkout.create_account_after')); ?></label>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-5">
                            <div class="checkout-summary-card">
                                <h5 class="fw-bolder fs-4 mb-3"><?php echo e(__('checkout.summary')); ?></h5>
                                <div class="card shadow-sm rounded-3 border-0">
                                    <div class="card-body p-4">
                                        <h6 class="fw-semibold text-muted mb-3"><?php echo e(__('checkout.your_order')); ?></h6>

                                        <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $product = $item->product ?? null;
                                                $qty = $item->quantity ?? 1;
                                                $unit = $product
                                                    ? ($product->price - ($product->price * ($product->sale ?? 0) / 100))
                                                    : 0;
                                            ?>
                                            <?php if($product): ?>
                                                <div class="d-flex justify-content-between small mb-2">
                                                    <span><?php echo e($product->name); ?> × <?php echo e($qty); ?></span>
                                                    <span>LE <?php echo e(number_format($unit * $qty, 2)); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        <hr>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><?php echo e(__('checkout.subtotal')); ?></span>
                                            <span id="checkout-subtotal">LE <?php echo e(number_format($subtotal, 2)); ?></span>
                                        </div>
                                        <?php if(($discountPercent ?? 0) > 0): ?>
                                            <div class="d-flex justify-content-between mb-2 text-success">
                                                <span><?php echo e(__('checkout.discount')); ?> (<?php echo e($discountPercent); ?>%)</span>
                                                <span id="checkout-discount">- LE <?php echo e(number_format($discountAmount ?? 0, 2)); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><?php echo e(__('checkout.delivery_fees')); ?></span>
                                            <span id="checkout-delivery">LE <?php echo e(number_format($deliveryFee ?? 0, 2)); ?></span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="fw-bolder mb-0"><?php echo e(__('checkout.total')); ?></h6>
                                            <h6 class="fw-bolder mb-0" id="checkout-total">LE <?php echo e(number_format($total, 2)); ?></h6>
                                        </div>
                                        <button class="btn btn-dark w-100 mt-3 py-3 fw-bold" type="submit" id="checkout-submit-btn">
                                            <?php echo e(__('checkout.confirm')); ?>

                                        </button>
                                        <p class="small text-muted text-center mt-2 mb-0"><?php echo e(__('checkout.placing_hint')); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    (function () {
        const citySelect = document.getElementById('city');
        const subtotal = <?php echo e((float) $subtotal); ?>;
        const discountPercent = <?php echo e((float) ($discountPercent ?? 0)); ?>;
        const subtotalEl = document.getElementById('checkout-subtotal');
        const deliveryEl = document.getElementById('checkout-delivery');
        const totalEl = document.getElementById('checkout-total');
        const form = document.getElementById('checkout-order-form');
        const submitBtn = document.getElementById('checkout-submit-btn');

        function format(n) {
            return 'LE ' + n.toFixed(2);
        }

        function recalc() {
            const fee = parseFloat(citySelect?.selectedOptions[0]?.dataset.fee || 0);
            const discount = subtotal * (discountPercent / 100);
            const total = subtotal - discount + fee;
            if (deliveryEl) deliveryEl.textContent = format(fee);
            if (totalEl) totalEl.textContent = format(total);
        }

        citySelect?.addEventListener('change', recalc);

        const savedSelect = document.getElementById('saved_address');
        savedSelect?.addEventListener('change', () => {
            const opt = savedSelect.selectedOptions[0];
            if (!opt || !opt.value) return;
            document.getElementById('address_line_1').value = opt.dataset.line1 || '';
            document.getElementById('address_line_2').value = opt.dataset.line2 || '';
            document.getElementById('country').value = opt.dataset.country || '';
            document.getElementById('phone_number').value = opt.dataset.phone || '';
            if (opt.dataset.city && citySelect) {
                citySelect.value = opt.dataset.city;
                recalc();
            }
        });

        form?.addEventListener('submit', function () {
            if (!submitBtn) return;
            submitBtn.disabled = true;
            submitBtn.textContent = <?php echo json_encode(__('checkout.placing_order'), 15, 512) ?>;
        });
    })();
</script>

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
<?php /**PATH C:\hayah\resources\views/checkout/address.blade.php ENDPATH**/ ?>