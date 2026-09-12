<!DOCTYPE html>
<html lang="<?php echo e($branding['locale']); ?>" dir="<?php echo e($branding['dir']); ?>">
<head>
    <meta charset="UTF-8">
    <title><?php echo $__env->yieldContent('pdf-title', $branding['siteName']); ?></title>
    <?php echo $__env->make('pdf.partials.styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>
<body>
    <?php echo $__env->make('pdf.partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->yieldContent('content'); ?>
    <?php echo $__env->make('pdf.partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>
</html>
<?php /**PATH C:\hayah\resources\views/pdf/layout.blade.php ENDPATH**/ ?>