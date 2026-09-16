<?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <ul class="mb-0 ps-3">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<?php if(session('status')): ?>
    <div class="alert alert-success mb-3"><?php echo e(session('status')); ?></div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger mb-3"><?php echo e(session('error')); ?></div>
<?php endif; ?>
<?php /**PATH C:\hayah\resources\views/components/web/auth-errors.blade.php ENDPATH**/ ?>