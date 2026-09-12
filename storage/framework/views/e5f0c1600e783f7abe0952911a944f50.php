<?php
    use App\Support\AdminNav;
    $notify = $adminNotifications ?? ['pending_orders' => 0, 'unread_messages' => 0, 'new_users' => 0, 'new_guests' => 0];
    $notifyTotal = AdminNav::notificationTotal($notify);
    $feed = $adminActivityFeed ?? collect();
    $locale = app()->getLocale();
?>

<nav id="navbar" class="navbar py-3 admin-navbar" style="position: sticky; top: 0; z-index: 999;">
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between w-100 gap-3">
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="toggle-sidebar-btn admin-icon-btn border me-2" aria-label="Toggle sidebar">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <a class="navbar-brand mb-0 d-inline-flex align-items-center" href="<?php echo e(route('admin.dashboard')); ?>">
                    <?php if (isset($component)) { $__componentOriginal8641339168305ca7d1df3ab05341c05a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8641339168305ca7d1df3ab05341c05a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.branding.logo','data' => ['link' => false,'style' => 'width: 120px; height: 48px; object-fit: contain;']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('branding.logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['link' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'style' => 'width: 120px; height: 48px; object-fit: contain;']); ?>
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
                </a>
            </div>

            <div class="d-flex align-items-center gap-2 gap-sm-3">
                <a href="<?php echo e(url('/')); ?>" target="_blank" rel="noopener" class="admin-view-store d-none d-md-inline-flex align-items-center gap-1">
                    <i class="bi bi-box-arrow-up-right"></i>
                    <?php echo e(__('admin.view_store')); ?>

                </a>

                <div class="admin-lang-switch d-none d-sm-flex align-items-center gap-1">
                    <a class="admin-lang-link <?php echo e($locale === 'en' ? 'active' : ''); ?>"
                        href="<?php echo e(url('/lang/en')); ?>">EN</a>
                    <span class="text-muted">|</span>
                    <a class="admin-lang-link <?php echo e($locale === 'ar' ? 'active' : ''); ?>"
                        href="<?php echo e(url('/lang/ar')); ?>">العربية</a>
                </div>

                <button type="button"
                    id="admin-theme-toggle"
                    class="admin-icon-btn border admin-theme-toggle"
                    data-label-dark="<?php echo e(__('admin.theme_dark')); ?>"
                    data-label-light="<?php echo e(__('admin.theme_light')); ?>"
                    aria-label="<?php echo e(__('admin.theme_dark')); ?>">
                    <i class="bi bi-moon-stars-fill" id="admin-theme-icon"></i>
                </button>

                <div class="dropdown">
                    <button class="admin-icon-btn border admin-notify-btn position-relative"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                        aria-label="<?php echo e(__('dashboard.notifications')); ?>">
                        <i class="bi bi-bell fs-5"></i>
                        <?php if($notifyTotal > 0): ?>
                            <span id="nav-notify-total"
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?php echo e($notifyTotal > 99 ? '99+' : $notifyTotal); ?>

                            </span>
                        <?php else: ?>
                            <span id="nav-notify-total" class="d-none position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
                        <?php endif; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end admin-notify-menu shadow-sm p-0">
                        <li class="dropdown-header d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                            <span class="fw-semibold"><?php echo e(__('dashboard.notifications')); ?></span>
                            <a href="<?php echo e(route('admin.dashboard')); ?>" class="small text-decoration-none"><?php echo e(__('dashboard.view_all')); ?></a>
                        </li>
                        <?php $__empty_1 = true; $__currentLoopData = $feed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li>
                                <a class="dropdown-item admin-notify-item py-2 px-3"
                                    href="<?php echo e($entry['url']); ?>">
                                    <div class="d-flex gap-2 align-items-start">
                                        <span class="admin-notify-icon bg-<?php echo e($entry['variant']); ?> bg-opacity-10 text-<?php echo e($entry['variant']); ?>">
                                            <i class="bi <?php echo e($entry['icon']); ?>"></i>
                                        </span>
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="fw-semibold small text-truncate"><?php echo e($entry['title']); ?></div>
                                            <div class="text-muted small text-truncate"><?php echo e($entry['subtitle']); ?></div>
                                            <div class="text-muted" style="font-size: 0.7rem;"><?php echo e($entry['at']?->diffForHumans()); ?></div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li class="dropdown-item-text text-muted small px-3 py-3"><?php echo e(__('dashboard.no_activity')); ?></li>
                        <?php endif; ?>
                        <li class="border-top">
                            <div class="d-flex flex-wrap gap-2 p-2 small">
                                <?php if(($notify['pending_orders'] ?? 0) > 0): ?>
                                    <a href="<?php echo e(route('order.list')); ?>?status=Pending" class="badge bg-warning text-dark text-decoration-none">
                                        <?php echo e(__('dashboard.pending_orders')); ?>: <span id="nav-pending-orders"><?php echo e($notify['pending_orders']); ?></span>
                                    </a>
                                <?php else: ?>
                                    <span class="d-none"><span id="nav-pending-orders">0</span></span>
                                <?php endif; ?>
                                <?php if(($notify['unread_messages'] ?? 0) > 0): ?>
                                    <a href="<?php echo e(route('admin.contact.list')); ?>" class="badge bg-danger text-decoration-none">
                                        <?php echo e(__('dashboard.unread_messages')); ?>: <span id="nav-unread-messages"><?php echo e($notify['unread_messages']); ?></span>
                                    </a>
                                <?php else: ?>
                                    <span class="d-none"><span id="nav-unread-messages">0</span></span>
                                <?php endif; ?>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
<?php /**PATH C:\hayah\resources\views/components/admin/navbar.blade.php ENDPATH**/ ?>