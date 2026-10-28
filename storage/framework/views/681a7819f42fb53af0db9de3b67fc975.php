<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'title' => null,
    'seo' => null,
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'title' => null,
    'seo' => null,
]); ?>
<?php foreach (array_filter(([
    'title' => null,
    'seo' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
    $isRtl = app()->getLocale() === 'ar';
?>
<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>" dir="<?php echo e($isRtl ? 'rtl' : 'ltr'); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Language" content="ar, en">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <?php if (isset($component)) { $__componentOriginal5d0a24dd43287eafaf3e24ec153646b3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5d0a24dd43287eafaf3e24ec153646b3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seo.meta','data' => ['seo' => $seo ?? null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('seo.meta'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['seo' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($seo ?? null)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5d0a24dd43287eafaf3e24ec153646b3)): ?>
<?php $attributes = $__attributesOriginal5d0a24dd43287eafaf3e24ec153646b3; ?>
<?php unset($__attributesOriginal5d0a24dd43287eafaf3e24ec153646b3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5d0a24dd43287eafaf3e24ec153646b3)): ?>
<?php $component = $__componentOriginal5d0a24dd43287eafaf3e24ec153646b3; ?>
<?php unset($__componentOriginal5d0a24dd43287eafaf3e24ec153646b3); ?>
<?php endif; ?>

    <?php if($title): ?>
        <title><?php echo e($title); ?> | <?php echo e($branding->siteName()); ?></title>
    <?php endif; ?>

    <?php if($branding->faviconUrl()): ?>
        <link rel="icon" href="<?php echo e($branding->faviconUrl()); ?>" type="image/x-icon">
    <?php endif; ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset($isRtl ? 'assets/css/bootstrap.rtl.min.css' : 'assets/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/all.min.css')); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/storefront-shell.css')); ?>?v=<?php echo e(filemtime(public_path('assets/css/storefront-shell.css'))); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/style.css')); ?>?v=<?php echo e(filemtime(public_path('assets/css/style.css'))); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/storefront-theme.css')); ?>?v=<?php echo e(filemtime(public_path('assets/css/storefront-theme.css'))); ?>">
    <script src="<?php echo e(asset('assets/js/storefront-theme.js')); ?>?v=<?php echo e(filemtime(public_path('assets/js/storefront-theme.js'))); ?>"></script>
</head>
<body class="storefront-body">
    <a class="skip-to-content" href="#storefront-main"><?php echo e(__('web.skip_to_content')); ?></a>
    <?php if (isset($component)) { $__componentOriginal498fc9e12fb124779aef755b8ec9c48b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal498fc9e12fb124779aef755b8ec9c48b = $attributes; } ?>
<?php $component = App\View\Components\Web\Navbar::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('web.navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\Web\Navbar::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal498fc9e12fb124779aef755b8ec9c48b)): ?>
<?php $attributes = $__attributesOriginal498fc9e12fb124779aef755b8ec9c48b; ?>
<?php unset($__attributesOriginal498fc9e12fb124779aef755b8ec9c48b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal498fc9e12fb124779aef755b8ec9c48b)): ?>
<?php $component = $__componentOriginal498fc9e12fb124779aef755b8ec9c48b; ?>
<?php unset($__componentOriginal498fc9e12fb124779aef755b8ec9c48b); ?>
<?php endif; ?>

    <div class="storefront-main" id="storefront-main">
        <?php echo e($slot); ?>

    </div>

    <?php echo $__env->yieldPushContent('styles'); ?>

    <?php if (isset($component)) { $__componentOriginalcb202c51f5688fc06368e1ddd94a426e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcb202c51f5688fc06368e1ddd94a426e = $attributes; } ?>
<?php $component = App\View\Components\Web\Footer::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('web.footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\Web\Footer::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcb202c51f5688fc06368e1ddd94a426e)): ?>
<?php $attributes = $__attributesOriginalcb202c51f5688fc06368e1ddd94a426e; ?>
<?php unset($__attributesOriginalcb202c51f5688fc06368e1ddd94a426e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcb202c51f5688fc06368e1ddd94a426e)): ?>
<?php $component = $__componentOriginalcb202c51f5688fc06368e1ddd94a426e; ?>
<?php unset($__componentOriginalcb202c51f5688fc06368e1ddd94a426e); ?>
<?php endif; ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\hayah\resources\views/components/web/layout.blade.php ENDPATH**/ ?>