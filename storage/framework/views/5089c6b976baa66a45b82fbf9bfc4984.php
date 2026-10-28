<?php
    use App\Support\AdminNav;
    $notify = $adminNotifications ?? ['pending_orders' => 0, 'unread_messages' => 0, 'new_users' => 0, 'new_guests' => 0];
    $sections = AdminNav::sections($notify);
    $chevron = app()->getLocale() === 'ar' ? 'left' : 'right';
    $chevronAuto = app()->getLocale() === 'ar' ? 'me-auto' : 'ms-auto';
?>

<aside id="sidebar"
    class="sidebar pt-4 d-flex flex-column"
    dir="<?php echo e(app()->getLocale() === 'ar' ? 'rtl' : 'ltr'); ?>">

    <ul class="sidebar-nav flex-grow-1" id="sidebar-nav">
        <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(! empty($section['heading'])): ?>
                <li class="nav-heading"><?php echo e(__($section['heading'])); ?></li>
            <?php endif; ?>

            <?php $__currentLoopData = $section['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($item['type'] === 'link'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e($item['active'] ? 'active' : 'collapsed'); ?>"
                            href="<?php echo e($item['url']); ?>">
                            <i class="bi <?php echo e($item['icon']); ?> mx-2"></i>
                            <span><?php echo e(__($item['label'])); ?></span>
                            <?php if (isset($component)) { $__componentOriginal53bb066317c045cb66e655f0f4033324 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53bb066317c045cb66e655f0f4033324 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.notification-badge','data' => ['count' => $item['badge'],'variant' => $item['badge_variant']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin.notification-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['count' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['badge']),'variant' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['badge_variant'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53bb066317c045cb66e655f0f4033324)): ?>
<?php $attributes = $__attributesOriginal53bb066317c045cb66e655f0f4033324; ?>
<?php unset($__attributesOriginal53bb066317c045cb66e655f0f4033324); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53bb066317c045cb66e655f0f4033324)): ?>
<?php $component = $__componentOriginal53bb066317c045cb66e655f0f4033324; ?>
<?php unset($__componentOriginal53bb066317c045cb66e655f0f4033324); ?>
<?php endif; ?>
                        </a>
                    </li>
                <?php elseif($item['type'] === 'collapse'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e($item['open'] ? '' : 'collapsed'); ?>"
                            data-bs-target="#<?php echo e($item['id']); ?>"
                            data-bs-toggle="collapse"
                            href="#">
                            <i class="bi <?php echo e($item['icon']); ?> mx-2"></i>
                            <span><?php echo e(__($item['label'])); ?></span>
                            <i class="bi bi-chevron-<?php echo e($chevron); ?> <?php echo e($chevronAuto); ?>"></i>
                        </a>
                        <ul id="<?php echo e($item['id']); ?>"
                            class="nav-content collapse <?php echo e($item['open'] ? 'show' : ''); ?>"
                            data-bs-parent="#sidebar-nav">
                            <?php $__currentLoopData = $item['children']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <a class="<?php echo e($child['active'] ? 'active' : ''); ?>"
                                        href="<?php echo e(route($child['route'])); ?>">
                                        <?php echo e(__($child['label'])); ?>

                                        <?php if (isset($component)) { $__componentOriginal53bb066317c045cb66e655f0f4033324 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53bb066317c045cb66e655f0f4033324 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.notification-badge','data' => ['count' => $child['badge'],'variant' => $child['badge_variant']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin.notification-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['count' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($child['badge']),'variant' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($child['badge_variant'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53bb066317c045cb66e655f0f4033324)): ?>
<?php $attributes = $__attributesOriginal53bb066317c045cb66e655f0f4033324; ?>
<?php unset($__attributesOriginal53bb066317c045cb66e655f0f4033324); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53bb066317c045cb66e655f0f4033324)): ?>
<?php $component = $__componentOriginal53bb066317c045cb66e655f0f4033324; ?>
<?php unset($__componentOriginal53bb066317c045cb66e655f0f4033324); ?>
<?php endif; ?>
                                    </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </li>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>

    <div class="sidebar-footer pb-2">
        <form action="<?php echo e(route('admin.logout')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn admin-sidebar-logout w-100">
                <i class="bi bi-box-arrow-right me-2"></i><?php echo e(__('admin_sidebar.logout')); ?>

            </button>
        </form>
    </div>
</aside>
<?php /**PATH C:\hayah\resources\views/components/admin/aside.blade.php ENDPATH**/ ?>