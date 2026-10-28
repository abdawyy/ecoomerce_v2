<?php
    $isRtl = app()->getLocale() === 'ar';
    $navItems = [
        ['route' => 'account.dashboard', 'label' => 'account.dashboard', 'icon' => 'fa-gauge-high', 'active' => request()->routeIs('account.dashboard')],
        ['route' => 'account.orders', 'label' => 'account.orders', 'icon' => 'fa-box', 'active' => request()->routeIs('account.orders*')],
        ['route' => 'account.addresses', 'label' => 'account.addresses', 'icon' => 'fa-location-dot', 'active' => request()->routeIs('account.addresses*')],
        ['route' => 'account.profile', 'label' => 'account.profile', 'icon' => 'fa-user', 'active' => request()->routeIs('account.profile*')],
        ['route' => 'account.reviews', 'label' => 'account.reviews', 'icon' => 'fa-star', 'active' => request()->routeIs('account.reviews')],
    ];
?>

<aside class="account-sidebar">
    <div class="account-sidebar-user mb-4">
        <div class="account-avatar"><?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?></div>
        <div>
            <div class="fw-bold"><?php echo e(Auth::user()->name); ?></div>
            <div class="small text-muted text-truncate"><?php echo e(Auth::user()->email); ?></div>
        </div>
    </div>

    <nav class="account-nav">
        <?php $__currentLoopData = $navItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route($item['route'])); ?>"
                class="account-nav-link <?php echo e($item['active'] ? 'active' : ''); ?>">
                <i class="fa-solid <?php echo e($item['icon']); ?>"></i>
                <span><?php echo e(__($item['label'])); ?></span>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <form action="<?php echo e(route('logout')); ?>" method="POST" class="mt-3">
            <?php echo csrf_field(); ?>
            <button type="submit" class="account-nav-link account-nav-logout w-100 border-0 bg-transparent">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span><?php echo e(__('account.logout')); ?></span>
            </button>
        </form>

    </nav>
</aside>
<?php /**PATH C:\hayah\resources\views/components/account/sidebar.blade.php ENDPATH**/ ?>