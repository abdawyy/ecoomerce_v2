<?php
    $isArabic = app()->getLocale() === 'ar';
    $languageLabel = __('table.language');
    if ($languageLabel === 'table.language') {
        $languageLabel = 'Language';
    }
?>

<div class="container-fluid px-0 <?php echo e($isArabic ? 'text-end' : ''); ?>" dir="<?php echo e($isArabic ? 'rtl' : 'ltr'); ?>">
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-3">
        
        

        
        
    </div>

    
    <div class="table-responsive shadow-sm rounded-4 admin-table-wrap">
        <table class="table table-modern align-middle mb-0">
            <thead>
                <tr>
                    <?php $__currentLoopData = $headers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $header): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $headerKey = 'table.headers.' . strtolower($header);
                            $headerLabel = __($headerKey);
                            $headerLabel = $headerLabel === $headerKey ? $header : $headerLabel;
                        ?>
                        <th scope="col"><?php echo e($headerLabel); ?></th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            </thead>
            <tbody>
                <?php if($rows->isEmpty()): ?>
                    <tr>
                        <td colspan="<?php echo e(count($headers)); ?>" class="text-center">
                            <?php echo e(__('table.no_results')); ?>

                        </td>
                    </tr>
                <?php else: ?>
                    <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <?php $__currentLoopData = $headers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $header): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $headerKey = 'table.headers.' . strtolower($header);
                                    $headerLabel = __($headerKey);
                                    $headerLabel = $headerLabel === $headerKey ? $header : $headerLabel;
                                ?>
                                <td data-label="<?php echo e($headerLabel); ?>">
                                    <?php if($header === 'Action'): ?>
                                        <?php
                                            $actionsAlign = $isArabic ? 'justify-content-start' : 'justify-content-end';
                                            $actionCount = 1
                                                + (isset($row['is_active']) ? 1 : 0)
                                                + (isset($row['is_highest']) ? 1 : 0);
                                        ?>
                                        <div class="table-actions <?php echo e($actionsAlign); ?> <?php echo e($actionCount >= 3 ? 'table-actions--multi' : ''); ?>">
                                            <?php if (isset($component)) { $__componentOriginalaf51672efffafdc303569af32ff5b424 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalaf51672efffafdc303569af32ff5b424 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.view-link','data' => ['href' => $url . '/edit/' . $row['ID']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin.view-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($url . '/edit/' . $row['ID'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalaf51672efffafdc303569af32ff5b424)): ?>
<?php $attributes = $__attributesOriginalaf51672efffafdc303569af32ff5b424; ?>
<?php unset($__attributesOriginalaf51672efffafdc303569af32ff5b424); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalaf51672efffafdc303569af32ff5b424)): ?>
<?php $component = $__componentOriginalaf51672efffafdc303569af32ff5b424; ?>
<?php unset($__componentOriginalaf51672efffafdc303569af32ff5b424); ?>
<?php endif; ?>

                                            <?php if(isset($row['is_active'])): ?>
                                                <form method="POST" action="<?php echo e($url); ?>/status/<?php echo e($row['ID']); ?>" class="table-actions-form">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn btn-sm <?php echo e($row['is_active'] == 1 ? 'btn-success' : 'btn-warning'); ?>">
                                                        <?php echo e($row['is_active'] == 1 ? __('table.status_active') : __('table.status_inactive')); ?>

                                                    </button>
                                                </form>
                                            <?php endif; ?>

                                            <?php if(isset($row['is_highest'])): ?>
                                                <form method="POST" action="<?php echo e(route('admin.products.toggleHighestStatus', $row['ID'])); ?>" class="table-actions-form">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn btn-sm <?php echo e($row['is_highest'] == 1 ? 'btn-primary' : 'btn-secondary'); ?>">
                                                        <?php echo e($row['is_highest'] == 1 ? __('table.status_highest') : __('table.status_normal')); ?>

                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    <?php elseif($header === 'ID'): ?>
                                        <a href="<?php echo e($url); ?>/edit/<?php echo e($row['ID']); ?>" class="text-decoration-none">#<?php echo e($row['ID']); ?></a>
                                    <?php elseif($header === 'Status'): ?>
                                        <?php
                                            $status = $row['Status'] ?? '';
                                            $statusClass = 'bg-secondary text-white';
                                            if (strtolower($status) === 'completed') $statusClass = 'bg-success text-white';
                                            if (strtolower($status) === 'pending') $statusClass = 'bg-warning text-dark';
                                            if (strtolower($status) === 'processing') $statusClass = 'bg-info text-white';
                                            if (strtolower($status) === 'cancelled') $statusClass = 'bg-danger text-white';
                                        ?>
                                        <span class="status-badge <?php echo e($statusClass); ?>"><?php echo e($row[$header] ?? $status); ?></span>
                                    <?php else: ?>
                                        <?php echo e($row[$header] ?? ''); ?>

                                    <?php endif; ?>
                                </td>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php /**PATH C:\hayah\resources\views/components/data-table.blade.php ENDPATH**/ ?>