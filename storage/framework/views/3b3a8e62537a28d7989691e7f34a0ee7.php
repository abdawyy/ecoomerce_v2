<?php if (isset($component)) { $__componentOriginal628d23ec5db1173b09efbdbd01a50602 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal628d23ec5db1173b09efbdbd01a50602 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.web.layout','data' => ['title' => __('auth.forgot_password_title')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('web.layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('auth.forgot_password_title'))]); ?>
    <?php if (isset($component)) { $__componentOriginal9064f92657a17c2e23670a98bf8c213e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9064f92657a17c2e23670a98bf8c213e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.web.auth-card','data' => ['title' => __('auth.forgot_password'),'subtitle' => __('auth.forgot_password_message')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('web.auth-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('auth.forgot_password')),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('auth.forgot_password_message'))]); ?>
        <form method="POST" action="<?php echo e(route('password.email')); ?>">
            <?php echo csrf_field(); ?>

            <div class="mb-4">
                <label for="email" class="form-label"><?php echo e(__('auth.email')); ?></label>
                <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>"
                       class="form-control form-control-lg <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       required autofocus autocomplete="username">
            </div>

            <button type="submit" class="btn btn-dark btn-lg w-100"><?php echo e(__('auth.send_reset_link')); ?></button>

            <p class="text-center text-muted small mt-4 mb-0">
                <a href="<?php echo e(route('login')); ?>" class="fw-semibold"><?php echo e(__('auth.login')); ?></a>
            </p>
        </form>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9064f92657a17c2e23670a98bf8c213e)): ?>
<?php $attributes = $__attributesOriginal9064f92657a17c2e23670a98bf8c213e; ?>
<?php unset($__attributesOriginal9064f92657a17c2e23670a98bf8c213e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9064f92657a17c2e23670a98bf8c213e)): ?>
<?php $component = $__componentOriginal9064f92657a17c2e23670a98bf8c213e; ?>
<?php unset($__componentOriginal9064f92657a17c2e23670a98bf8c213e); ?>
<?php endif; ?>
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
<?php /**PATH C:\hayah\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>