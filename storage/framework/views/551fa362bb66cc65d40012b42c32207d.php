<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'title',
    'subtitle' => null,
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'title',
    'subtitle' => null,
]); ?>
<?php foreach (array_filter(([
    'title',
    'subtitle' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<section class="storefront-auth">
    <div class="container">
        <div class="storefront-auth-card">
            <div class="storefront-auth-form-wrap">
                <div class="text-center mb-4">
                    <?php if (isset($component)) { $__componentOriginal8641339168305ca7d1df3ab05341c05a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8641339168305ca7d1df3ab05341c05a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.branding.logo','data' => ['style' => 'width: 140px; height: auto; max-height: 56px; object-fit: contain;']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('branding.logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['style' => 'width: 140px; height: auto; max-height: 56px; object-fit: contain;']); ?>
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

                <h1 class="storefront-auth-title text-center"><?php echo e($title); ?></h1>
                <?php if($subtitle): ?>
                    <p class="storefront-auth-subtitle text-center"><?php echo e($subtitle); ?></p>
                <?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal7affe82fb798a8796f70e86c66bf6d52 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7affe82fb798a8796f70e86c66bf6d52 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.web.auth-errors','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('web.auth-errors'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7affe82fb798a8796f70e86c66bf6d52)): ?>
<?php $attributes = $__attributesOriginal7affe82fb798a8796f70e86c66bf6d52; ?>
<?php unset($__attributesOriginal7affe82fb798a8796f70e86c66bf6d52); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7affe82fb798a8796f70e86c66bf6d52)): ?>
<?php $component = $__componentOriginal7affe82fb798a8796f70e86c66bf6d52; ?>
<?php unset($__componentOriginal7affe82fb798a8796f70e86c66bf6d52); ?>
<?php endif; ?>

                <?php echo e($slot); ?>

            </div>
        </div>
    </div>
</section>

<?php if (! $__env->hasRenderedOnce('3575ee9a-e7e1-4976-8bf6-f7a4eb341a88')): $__env->markAsRenderedOnce('3575ee9a-e7e1-4976-8bf6-f7a4eb341a88'); ?>
    <?php $__env->startPush('scripts'); ?>
        <script>
            document.querySelectorAll('[data-password-toggle]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const input = document.querySelector(btn.getAttribute('data-password-toggle'));
                    if (!input) return;
                    const hidden = input.type === 'password';
                    input.type = hidden ? 'text' : 'password';
                    const icon = btn.querySelector('i');
                    if (icon) {
                        icon.className = hidden ? 'bi bi-eye-slash' : 'bi bi-eye';
                    }
                });
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH C:\hayah\resources\views/components/web/auth-card.blade.php ENDPATH**/ ?>