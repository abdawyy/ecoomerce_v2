<div class="pdf-header">
    <table>
        <tr>
            <td style="width: 40%; text-align: <?php echo e($branding['align']); ?>;">
                <?php if(!empty($branding['logoSrc'] ?? $branding['logoPath'] ?? null)): ?>
                    <img src="<?php echo e($branding['logoSrc'] ?? $branding['logoPath']); ?>" class="pdf-logo" alt="<?php echo e($branding['siteName']); ?>">
                <?php else: ?>
                    <p class="site-name"><?php echo e($branding['siteName']); ?></p>
                <?php endif; ?>
            </td>
            <td style="text-align: <?php echo e($branding['isRtl'] ? 'left' : 'right'); ?>;">
                <p class="site-name"><?php echo e($branding['siteName']); ?></p>
                <p class="site-meta"><?php echo e($branding['tagline']); ?></p>
                <?php if($branding['supportEmail']): ?>
                    <p class="site-meta"><?php echo e($branding['supportEmail']); ?></p>
                <?php endif; ?>
                <?php if($branding['supportPhone']): ?>
                    <p class="site-meta"><?php echo e($branding['supportPhone']); ?></p>
                <?php endif; ?>
                <p class="site-meta"><?php echo e($branding['websiteUrl']); ?></p>
            </td>
        </tr>
    </table>
</div>
<?php /**PATH C:\hayah\resources\views/pdf/partials/header.blade.php ENDPATH**/ ?>