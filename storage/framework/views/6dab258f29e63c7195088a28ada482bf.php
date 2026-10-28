<?php if($paginator->hasPages()): ?>
    <nav>
        <ul class="pagination justify-content-center">
            
            <?php if($paginator->onFirstPage()): ?>
                <li class="page-item disabled">
                    <span class="page-link text-black" style="color: black;">&laquo;</span>
                </li>
            <?php else: ?>
                <li class="page-item">
                    <a class="page-link text-black" href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev" style="color: black;">&laquo;</a>
                </li>
            <?php endif; ?>

            
            <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                
                <?php if(is_string($element)): ?>
                    <li class="page-item disabled d-none d-md-inline-flex">
                        <span class="page-link text-black" style="color: black;"><?php echo e($element); ?></span>
                    </li>
                <?php endif; ?>

                
                <?php if(is_array($element)): ?>
                    <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($page == $paginator->currentPage()): ?>
                            <li class="page-item active d-inline-flex">
                                <span class="page-link text-black" style="color: black; background-color: #e9ecef; border-color: #dee2e6;"><?php echo e($page); ?></span>
                            </li>
                        <?php else: ?>
                            <li class="page-item d-none d-md-inline-flex">
                                <a class="page-link text-black" href="<?php echo e($url); ?>" style="color: black;"><?php echo e($page); ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php if($paginator->hasMorePages()): ?>
                <li class="page-item">
                    <a class="page-link text-black" href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next" style="color: black;">&raquo;</a>
                </li>
            <?php else: ?>
                <li class="page-item disabled">
                    <span class="page-link text-black" style="color: black;">&raquo;</span>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>
<?php /**PATH C:\hayah\resources\views/vendor/pagination/bootstrap-5.blade.php ENDPATH**/ ?>