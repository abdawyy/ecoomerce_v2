<html lang="<?php echo e(app()->getLocale()); ?>" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>" data-bs-theme="light">
<script>
(function () {
    try {
        var stored = localStorage.getItem('hayah-admin-theme');
        var theme = stored === 'dark' || stored === 'light'
            ? stored
            : (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        document.documentElement.setAttribute('data-bs-theme', theme);
    } catch (e) {}
})();
</script>

<head>
    <meta charset="UTF-8">
    <link rel="canonical" href="<?php echo e(url('/')); ?>">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=<?php echo e(asset("admin/assets/css/bootstrap.min.css")); ?>>

    <!-- icon -->
    <link href=<?php echo e(asset("admin/assets/vendors/bootstrap-icons/bootstrap-icons.css")); ?> rel="stylesheet">
    <link href=<?php echo e(asset("admin/assets/vendors/boxicons/css/boxicons.min.css")); ?> rel="stylesheet">
    <!--  -->
    <!-- font 1 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    <!-- font 2 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400..700&display=swap" rel="stylesheet">
    <!--  -->
    <link rel="stylesheet" href=<?php echo e(asset("admin/assets/css/all.min.css")); ?>>
    <link rel="stylesheet" href=<?php echo e(asset("admin/assets/css/style-Dashboard.css")); ?>>
    <link rel="stylesheet" href="<?php echo e(asset('admin/assets/css/admin-theme.css')); ?>?v=<?php echo e(filemtime(public_path('admin/assets/css/admin-theme.css'))); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <?php if($branding->faviconUrl()): ?>
        <link rel="icon" href="<?php echo e($branding->faviconUrl()); ?>" type="image/x-icon">
    <?php endif; ?>
    <title><?php echo e($branding->siteName()); ?> — Admin</title>
</head>
<body class="admin-body">
<style>
    [dir="rtl"] .sidebar {
        right: 0;
        left: auto;
    }
</style>
<?php /**PATH C:\hayah\resources\views/components/admin/header.blade.php ENDPATH**/ ?>