<?php if (isset($component)) { $__componentOriginal9922b99e82ca32bf450dbd7628945e59 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9922b99e82ca32bf450dbd7628945e59 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.account.layout','data' => ['title' => __('account.reviews')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('account.layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('account.reviews'))]); ?>
    <?php if($reviews->isEmpty()): ?>
        <div class="account-card account-empty">
            <i class="fa-solid fa-star d-block"></i>
            <p class="mb-0"><?php echo e(__('account.no_reviews')); ?></p>
        </div>
    <?php else: ?>
        <div class="vstack gap-3">
            <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="account-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start gap-2 flex-wrap mb-2">
                            <div>
                                <div class="small text-muted"><?php echo e(__('account.review_for')); ?></div>
                                <?php if($review->product): ?>
                                    <a href="<?php echo e(route('product.show', $review->product_id)); ?>" class="fw-bold text-dark text-decoration-none">
                                        <?php echo e($review->product->name); ?>

                                    </a>
                                <?php else: ?>
                                    <span class="fw-bold">—</span>
                                <?php endif; ?>
                            </div>
                            <div class="text-warning">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <i class="fa-<?php echo e($i <= $review->rating ? 'solid' : 'regular'); ?> fa-star"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <p class="mb-2"><?php echo e($review->comment); ?></p>
                        <div class="small text-muted"><?php echo e($review->created_at?->diffForHumans()); ?></div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="mt-3">
            <?php echo e($reviews->links()); ?>

        </div>
    <?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9922b99e82ca32bf450dbd7628945e59)): ?>
<?php $attributes = $__attributesOriginal9922b99e82ca32bf450dbd7628945e59; ?>
<?php unset($__attributesOriginal9922b99e82ca32bf450dbd7628945e59); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9922b99e82ca32bf450dbd7628945e59)): ?>
<?php $component = $__componentOriginal9922b99e82ca32bf450dbd7628945e59; ?>
<?php unset($__componentOriginal9922b99e82ca32bf450dbd7628945e59); ?>
<?php endif; ?>
<?php /**PATH C:\hayah\resources\views/account/reviews.blade.php ENDPATH**/ ?>