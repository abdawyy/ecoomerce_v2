<?php if (isset($component)) { $__componentOriginal45d9cbba1e84739af2366cafaf311004 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal45d9cbba1e84739af2366cafaf311004 = $attributes; } ?>
<?php $component = App\View\Components\Admin\Header::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\Admin\Header::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal45d9cbba1e84739af2366cafaf311004)): ?>
<?php $attributes = $__attributesOriginal45d9cbba1e84739af2366cafaf311004; ?>
<?php unset($__attributesOriginal45d9cbba1e84739af2366cafaf311004); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal45d9cbba1e84739af2366cafaf311004)): ?>
<?php $component = $__componentOriginal45d9cbba1e84739af2366cafaf311004; ?>
<?php unset($__componentOriginal45d9cbba1e84739af2366cafaf311004); ?>
<?php endif; ?>

<div class="admin-auth-page py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-9">
                <div class="card border-0 shadow-lg overflow-hidden rounded-4">
                    <div class="row g-0">
                        <div class="col-12 col-md-5 d-none d-md-flex">
                            <div class="admin-auth-visual w-100 d-flex flex-column justify-content-between p-4 p-xl-5">
                                <div>
                                    <h2 class="mb-3"><?php echo e(__('admin.login_title')); ?></h2>
                                    <p class="mb-0 text-white-50"><?php echo e(__('admin.email')); ?> • <?php echo e(__('admin.password')); ?></p>
                                </div>
                                <div class="small text-white-50">HAYAH Admin Portal</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-7">
                            <div class="p-4 p-lg-5">
                                <div class="text-center mb-4">
                                    <?php if (isset($component)) { $__componentOriginal8641339168305ca7d1df3ab05341c05a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8641339168305ca7d1df3ab05341c05a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.branding.logo','data' => ['href' => route('admin.dashboard'),'style' => 'width: 160px; height: auto; max-height: 80px; object-fit: contain;']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('branding.logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.dashboard')),'style' => 'width: 160px; height: auto; max-height: 80px; object-fit: contain;']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8641339168305ca7d1df3ab05341c05a)): ?>
<?php $attributes = $__attributesOriginal8641339168305ca7d1df3ab05341c05a; ?>
<?php unset($__attributesOriginal8641339168305ca7d1df3ab05341c05a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8641339168305ca7d1df3ab05341c05a)): ?>
<?php $component = $__componentOriginal8641339168305ca7d1df3ab05341c05a; ?>
<?php unset($__componentOriginal8641339168305ca7d1df3ab05341c05a); ?>
<?php endif; ?>
                                </div>
                                <div class="d-flex justify-content-center justify-content-md-end align-items-center gap-2 mb-3 small">
                                    <a class="admin-lang-link text-decoration-none <?php echo e(app()->getLocale() == 'en' ? 'active' : ''); ?>" href="<?php echo e(url('/lang/en')); ?>">EN</a>
                                    <span class="text-muted">|</span>
                                    <a class="admin-lang-link text-decoration-none <?php echo e(app()->getLocale() == 'ar' ? 'active' : ''); ?>" href="<?php echo e(url('/lang/ar')); ?>">العربية</a>
                                    <span class="text-muted mx-1">|</span>
                                    <button type="button"
                                        id="admin-theme-toggle"
                                        class="btn btn-sm admin-icon-btn border admin-theme-toggle"
                                        data-label-dark="<?php echo e(__('admin.theme_dark')); ?>"
                                        data-label-light="<?php echo e(__('admin.theme_light')); ?>"
                                        aria-label="<?php echo e(__('admin.theme_dark')); ?>">
                                        <i class="bi bi-moon-stars-fill" id="admin-theme-icon"></i>
                                    </button>
                                </div>

                                <?php echo csrf_field(); ?>
                                <h3 class="text-center mb-4 d-md-none"><?php echo e(__('admin.login_title')); ?></h3>

                                <form action="<?php echo e(route('admin.login')); ?>" method="POST" class="admin-auth-form">
                                    <?php echo csrf_field(); ?>
                                    <?php if (isset($component)) { $__componentOriginal9b1df53224e42948610ceb30d6d57a7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9b1df53224e42948610ceb30d6d57a7c = $attributes; } ?>
<?php $component = App\View\Components\AlertError::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('alert-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AlertError::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9b1df53224e42948610ceb30d6d57a7c)): ?>
<?php $attributes = $__attributesOriginal9b1df53224e42948610ceb30d6d57a7c; ?>
<?php unset($__attributesOriginal9b1df53224e42948610ceb30d6d57a7c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9b1df53224e42948610ceb30d6d57a7c)): ?>
<?php $component = $__componentOriginal9b1df53224e42948610ceb30d6d57a7c; ?>
<?php unset($__componentOriginal9b1df53224e42948610ceb30d6d57a7c); ?>
<?php endif; ?>

                                    <div class="mb-3">
                                        <label for="email" class="form-label"><?php echo e(__('admin.email')); ?></label>
                                        <input type="email" name="email" id="email" class="form-control form-control-lg" value="<?php echo e(old('email')); ?>" required>
                                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div class="mb-4">
                                        <label for="password" class="form-label"><?php echo e(__('admin.password')); ?></label>
                                        <input type="password" name="password" id="password" class="form-control form-control-lg" required>
                                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <button type="submit" class="btn btn-dark btn-lg w-100"><?php echo e(__('admin.login_button')); ?></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (isset($component)) { $__componentOriginalb8e9be121ac5809d76d4768b3abc0902 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb8e9be121ac5809d76d4768b3abc0902 = $attributes; } ?>
<?php $component = App\View\Components\Admin\Footer::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin.footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\Admin\Footer::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb8e9be121ac5809d76d4768b3abc0902)): ?>
<?php $attributes = $__attributesOriginalb8e9be121ac5809d76d4768b3abc0902; ?>
<?php unset($__attributesOriginalb8e9be121ac5809d76d4768b3abc0902); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb8e9be121ac5809d76d4768b3abc0902)): ?>
<?php $component = $__componentOriginalb8e9be121ac5809d76d4768b3abc0902; ?>
<?php unset($__componentOriginalb8e9be121ac5809d76d4768b3abc0902); ?>
<?php endif; ?>
<?php /**PATH C:\hayah\resources\views/admin/login.blade.php ENDPATH**/ ?>