<?php
    $seo = $seo ?? app(\App\Services\SeoService::class)->resolve();
?>

<title><?php echo e($seo['title']); ?></title>
<meta name="description" content="<?php echo e($seo['description']); ?>">
<?php if(!empty($seo['keywords'])): ?>
    <meta name="keywords" content="<?php echo e($seo['keywords']); ?>">
<?php endif; ?>
<meta name="robots" content="<?php echo e($seo['robots']); ?>">
<meta name="author" content="<?php echo e($branding->siteName()); ?>">

<meta property="og:title" content="<?php echo e($seo['og_title']); ?>">
<meta property="og:description" content="<?php echo e($seo['og_description']); ?>">
<meta property="og:image" content="<?php echo e($seo['og_image']); ?>">
<meta property="og:url" content="<?php echo e($seo['canonical']); ?>">
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?php echo e($branding->siteName()); ?>">
<meta property="og:locale" content="<?php echo e(app()->getLocale() === 'ar' ? 'ar_AR' : 'en_US'); ?>">

<meta name="twitter:card" content="<?php echo e($seo['twitter_card']); ?>">
<meta name="twitter:title" content="<?php echo e($seo['og_title']); ?>">
<meta name="twitter:description" content="<?php echo e($seo['og_description']); ?>">
<meta name="twitter:image" content="<?php echo e($seo['og_image']); ?>">

<link rel="canonical" href="<?php echo e($seo['canonical']); ?>">
<link rel="alternate" hreflang="ar" href="<?php echo e($seo['canonical']); ?>">
<link rel="alternate" hreflang="en" href="<?php echo e($seo['canonical']); ?>">
<link rel="alternate" hreflang="x-default" href="<?php echo e($seo['canonical']); ?>">

<?php $__currentLoopData = $seo['json_ld'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $block): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <script type="application/ld+json"><?php echo json_encode($block, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH C:\hayah\resources\views/components/seo/meta.blade.php ENDPATH**/ ?>