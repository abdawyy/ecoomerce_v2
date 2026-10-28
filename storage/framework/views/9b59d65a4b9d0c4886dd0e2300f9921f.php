<?php if(($count ?? 0) > 0): ?>
    <?php
        $variant = $variant ?? 'warning';
        $textClass = match ($variant) {
            'warning' => 'text-dark',
            'danger', 'info', 'primary', 'success' => 'text-white',
            default => '',
        };
    ?>
    <span class="badge bg-<?php echo e($variant); ?> <?php echo e($textClass); ?> <?php echo e(app()->getLocale() === 'ar' ? 'me-auto' : 'ms-auto'); ?>"><?php echo e($count); ?></span>
<?php endif; ?>
<?php /**PATH C:\hayah\resources\views/components/admin/notification-badge.blade.php ENDPATH**/ ?>