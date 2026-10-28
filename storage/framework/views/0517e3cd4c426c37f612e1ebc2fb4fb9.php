<?php if (isset($component)) { $__componentOriginal9922b99e82ca32bf450dbd7628945e59 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9922b99e82ca32bf450dbd7628945e59 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.account.layout','data' => ['title' => __('account.addresses')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('account.layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('account.addresses'))]); ?>
    <p class="text-muted mb-4"><?php echo e(__('account.addresses_hint')); ?></p>

    <div class="account-card mb-4">
        <div class="card-header py-3 px-4"><?php echo e(__('account.add_address')); ?></div>
        <div class="card-body p-4">
            <form action="<?php echo e(route('account.addresses.store')); ?>" method="POST" class="row g-3">
                <?php echo csrf_field(); ?>
                <div class="col-md-6">
                    <label class="form-label"><?php echo e(__('account.address_line1')); ?></label>
                    <input type="text" name="address_line1" class="form-control" value="<?php echo e(old('address_line1')); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo e(__('account.address_line2')); ?></label>
                    <input type="text" name="address_line2" class="form-control" value="<?php echo e(old('address_line2')); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label"><?php echo e(__('account.city')); ?></label>
                    <input type="text" name="city" class="form-control" value="<?php echo e(old('city')); ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label"><?php echo e(__('account.state')); ?></label>
                    <input type="text" name="state" class="form-control" value="<?php echo e(old('state')); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label"><?php echo e(__('account.postal_code')); ?></label>
                    <input type="text" name="postal_code" class="form-control" value="<?php echo e(old('postal_code')); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo e(__('account.country')); ?></label>
                    <input type="text" name="country" class="form-control" value="<?php echo e(old('country', 'Egypt')); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo e(__('account.phone')); ?></label>
                    <input type="text" name="phone_number" class="form-control" value="<?php echo e(old('phone_number')); ?>" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-dark"><?php echo e(__('account.save_address')); ?></button>
                </div>
            </form>
        </div>
    </div>

    <?php if($addresses->isEmpty()): ?>
        <div class="account-card account-empty">
            <i class="fa-solid fa-location-dot d-block"></i>
            <p class="mb-0"><?php echo e(__('account.no_addresses')); ?></p>
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php $__currentLoopData = $addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6">
                    <div class="account-card h-100">
                        <div class="card-body p-4">
                            <?php if($address->is_default): ?>
                                <span class="account-default-badge"><?php echo e(__('account.default_address')); ?></span>
                            <?php endif; ?>
                            <p class="mb-1 fw-semibold"><?php echo e($address->address_line1); ?></p>
                            <?php if($address->address_line2): ?>
                                <p class="mb-1 text-muted small"><?php echo e($address->address_line2); ?></p>
                            <?php endif; ?>
                            <p class="mb-1 small"><?php echo e($address->city); ?> <?php echo e($address->postal_code); ?></p>
                            <p class="mb-3 small"><?php echo e($address->country); ?></p>
                            <p class="mb-3 small"><i class="fa-solid fa-phone me-1"></i><?php echo e($address->phone_number); ?></p>

                            <div class="d-flex gap-2 flex-wrap">
                                <?php if (! ($address->is_default)): ?>
                                    <form action="<?php echo e(route('account.addresses.default', $address->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <button type="submit" class="btn btn-sm btn-dark"><?php echo e(__('account.set_default')); ?></button>
                                    </form>
                                <?php endif; ?>
                                <button class="btn btn-sm btn-outline-dark" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#edit-address-<?php echo e($address->id); ?>">
                                    <?php echo e(__('account.edit_address')); ?>

                                </button>
                                <form action="<?php echo e(route('account.addresses.destroy', $address->id)); ?>" method="POST"
                                    onsubmit="return confirm('<?php echo e(__('account.delete_address')); ?>?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><?php echo e(__('account.delete_address')); ?></button>
                                </form>
                            </div>

                            <div class="collapse mt-3" id="edit-address-<?php echo e($address->id); ?>">
                                <form action="<?php echo e(route('account.addresses.update', $address->id)); ?>" method="POST" class="row g-2">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    <div class="col-12">
                                        <input type="text" name="address_line1" class="form-control form-control-sm"
                                            value="<?php echo e($address->address_line1); ?>" required>
                                    </div>
                                    <div class="col-12">
                                        <input type="text" name="address_line2" class="form-control form-control-sm"
                                            value="<?php echo e($address->address_line2); ?>">
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="city" class="form-control form-control-sm"
                                            value="<?php echo e($address->city); ?>" required>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="phone_number" class="form-control form-control-sm"
                                            value="<?php echo e($address->phone_number); ?>" required>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="state" class="form-control form-control-sm"
                                            value="<?php echo e($address->state); ?>">
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="postal_code" class="form-control form-control-sm"
                                            value="<?php echo e($address->postal_code); ?>">
                                    </div>
                                    <div class="col-12">
                                        <input type="text" name="country" class="form-control form-control-sm"
                                            value="<?php echo e($address->country); ?>">
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-sm btn-dark"><?php echo e(__('account.save_address')); ?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php /**PATH C:\hayah\resources\views/account/addresses/index.blade.php ENDPATH**/ ?>