<?php if (isset($component)) { $__componentOriginal628d23ec5db1173b09efbdbd01a50602 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal628d23ec5db1173b09efbdbd01a50602 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.web.layout','data' => ['seo' => $seo ?? null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('web.layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['seo' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($seo ?? null)]); ?>
<?php $__env->startPush('styles'); ?>
<style>
    .hero {
        min-height: 600px;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        border-radius: var(--radius-lg);
    }

    .hero-img,
    .category-img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        padding: 60px;
        border-radius: var(--radius-lg);
    }

    .section-title h2 {
        letter-spacing: -1px;
        color: var(--brand-black);
    }

    .category-box {
        height: 500px;
        border-radius: var(--radius-lg);
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: flex-end;
        background: var(--brand-soft);
        transition: transform 0.5s ease;
        color: inherit;
    }

    .category-overlay {
        position: relative;
        z-index: 2;
        background: linear-gradient(to top, rgba(0,0,0,0.88) 8%, rgba(0,0,0,0.35) 48%, transparent 100%);
        width: 100%;
        padding: 40px;
        transition: padding 0.4s ease;
    }

    .category-overlay h3 {
        color: #fff !important;
        text-shadow: 0 2px 16px rgba(0, 0, 0, 0.45);
    }

    .category-box:hover .category-overlay {
        padding-bottom: 50px;
    }

    .custom-ctrl { opacity: 1; width: 40px; }
    .custom-ctrl-icon {
        background-color: var(--brand-black) !important;
        border-radius: 50%;
        width: 45px;
        height: 45px;
        background-size: 40%;
    }
</style>
<?php $__env->stopPush(); ?>

<section id="home" class="pt-4 pb-5">
    <div class="container">
        <div class="hero">
            <img src="<?php echo e($branding->heroImageUrl()); ?>" class="hero-img" alt="<?php echo e($branding->siteName()); ?>">
            <div class="hero-content text-white">
                <span class="text-uppercase fw-bold mb-2 d-block" style="letter-spacing: 3px; font-size: 0.8rem;">
                    <?php echo e($branding->tagline() ?: __('web.hero_tagline')); ?>

                </span>
                <h1 class="display-2 fw-bold mb-3"><?php echo e(__('web.brand_collection_title')); ?></h1>
                <p class="lead mb-4 opacity-75 w-75 d-none d-md-block"><?php echo e(__('web.brand_collection_desc')); ?></p>
                <a href="<?php echo e(route('product.List')); ?>" class="btn btn-light btn-lg px-5 py-3 rounded-pill fw-bold shadow-lg">
                    <?php echo e(__('web.discover')); ?>

                </a>
            </div>
        </div>
    </div>
</section>

<section id="newCollection" class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div class="section-title">
                <h2 class="fw-bold display-5 mb-0"><?php echo e(__('web.new_collection')); ?></h2>
                <p class="text-muted mt-2"><?php echo e(__('web.new_collection_desc')); ?></p>
            </div>
            <a href="<?php echo e(route('product.List')); ?>" class="home-show-all fw-bold text-decoration-none d-none d-md-block">
                <?php echo e(__('web.show_all')); ?>

            </a>
        </div>

        <div id="newCollectionCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php $__currentLoopData = $products->chunk(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunkIndex => $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="carousel-item <?php echo e($chunkIndex === 0 ? 'active' : ''); ?>">
                        <div class="row g-3 g-md-4">
                            <?php $__currentLoopData = $chunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-6 col-lg-3">
                                    <?php if (isset($component)) { $__componentOriginal9dbde93c50e49c2830e855fc2dc390af = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9dbde93c50e49c2830e855fc2dc390af = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.web.product-card','data' => ['product' => $product]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('web.product-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['product' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($product)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9dbde93c50e49c2830e855fc2dc390af)): ?>
<?php $attributes = $__attributesOriginal9dbde93c50e49c2830e855fc2dc390af; ?>
<?php unset($__attributesOriginal9dbde93c50e49c2830e855fc2dc390af); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9dbde93c50e49c2830e855fc2dc390af)): ?>
<?php $component = $__componentOriginal9dbde93c50e49c2830e855fc2dc390af; ?>
<?php unset($__componentOriginal9dbde93c50e49c2830e855fc2dc390af); ?>
<?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <button class="carousel-control-prev custom-ctrl" type="button" data-bs-target="#newCollectionCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon custom-ctrl-icon"></span>
            </button>
            <button class="carousel-control-next custom-ctrl" type="button" data-bs-target="#newCollectionCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon custom-ctrl-icon"></span>
            </button>
        </div>
        <p class="carousel-swipe-hint d-md-none"><i class="bi bi-arrow-left-right me-1"></i> Swipe to browse</p>
    </div>
</section>

<section id="category" class="py-5 home-categories">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <?php $__currentLoopData = $branding->homeCategoryTiles(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tile): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-12 col-md-6">
                    <a href="<?php echo e($tile['url']); ?>" class="category-box text-decoration-none d-block">
                        <img src="<?php echo e($tile['image']); ?>" class="category-img" alt="<?php echo e($tile['name']); ?>">
                        <div class="category-overlay">
                            <h3 class="fw-bold text-white display-4 mb-3"><?php echo e($tile['name']); ?></h3>
                            <span class="btn btn-light px-4 py-2 rounded-pill fw-bold">
                                <?php echo e(__('web.see_details')); ?>

                            </span>
                        </div>
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal628d23ec5db1173b09efbdbd01a50602)): ?>
<?php $attributes = $__attributesOriginal628d23ec5db1173b09efbdbd01a50602; ?>
<?php unset($__attributesOriginal628d23ec5db1173b09efbdbd01a50602); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal628d23ec5db1173b09efbdbd01a50602)): ?>
<?php $component = $__componentOriginal628d23ec5db1173b09efbdbd01a50602; ?>
<?php unset($__componentOriginal628d23ec5db1173b09efbdbd01a50602); ?>
<?php endif; ?>
<?php /**PATH C:\hayah\resources\views/index.blade.php ENDPATH**/ ?>