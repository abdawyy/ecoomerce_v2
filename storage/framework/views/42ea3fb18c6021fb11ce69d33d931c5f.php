<div class="promo-bar">
    <?php echo e(__('web.title')); ?> — <?php echo e(__('web.promo_tagline')); ?>

</div>

<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
            <i class="fa-solid fa-bars-staggered"></i>
        </button>

        <a class="navbar-brand d-inline-flex align-items-center py-0" href="<?php echo e(route('home')); ?>">
            <?php if (isset($component)) { $__componentOriginal8641339168305ca7d1df3ab05341c05a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8641339168305ca7d1df3ab05341c05a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.branding.logo','data' => ['link' => false,'style' => 'width: 104px; height: auto; max-height: 44px; object-fit: contain;']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('branding.logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['link' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'style' => 'width: 104px; height: auto; max-height: 44px; object-fit: contain;']); ?>
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

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link px-3" href="/"><?php echo e(__('web.home')); ?></a></li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle px-3" href="#" role="button">
                        <?php echo e(__('web.category')); ?>

                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?php echo e(route('product.List')); ?>"><strong><?php echo e(__('web.all_products')); ?></strong></a></li>
                        <li><hr class="dropdown-divider"></li>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><a class="dropdown-item" href="<?php echo e(route('product.List', ['id' => $category->id])); ?>"><?php echo e($category->name); ?></a></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </li>
            </ul>
        </div>

        <div class="d-flex align-items-center gap-1 gap-md-2 storefront-toolbar">
            <button type="button" class="storefront-icon-btn" data-storefront-theme-toggle
                data-label-light="<?php echo e(__('account.theme_light')); ?>"
                data-label-dark="<?php echo e(__('account.theme_dark')); ?>"
                aria-label="<?php echo e(__('account.theme_dark')); ?>">
                <i class="bi bi-moon-stars-fill"></i>
            </button>

            <a href="#" class="storefront-icon-btn" data-bs-toggle="modal" data-bs-target="#searchModal" aria-label="<?php echo e(__('web.search')); ?>">
                <i class="fa-solid fa-magnifying-glass"></i>
            </a>

            <a href="<?php echo e(route('cart.index')); ?>" class="storefront-icon-btn position-relative" aria-label="<?php echo e(__('web.footer_cart')); ?>">
                <i class="fa-solid fa-bag-shopping"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge-cart cart-count"><?php echo e($cartCount ?? 0); ?></span>
            </a>

            <?php if(auth()->guard()->check()): ?>
                <div class="dropdown">
                    <a href="#" class="storefront-icon-btn" data-bs-toggle="dropdown" aria-expanded="false" aria-label="<?php echo e(__('account.account_menu')); ?>">
                        <i class="fa-solid fa-user"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li class="dropdown-header small"><?php echo e(Auth::user()->name); ?></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('account.dashboard')); ?>"><?php echo e(__('account.dashboard')); ?></a></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('account.orders')); ?>"><?php echo e(__('account.orders')); ?></a></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('account.profile')); ?>"><?php echo e(__('account.profile')); ?></a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="<?php echo e(route('logout')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="dropdown-item"><?php echo e(__('account.logout')); ?></button>
                            </form>
                        </li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="storefront-icon-btn d-none d-md-inline-flex" title="<?php echo e(__('account.login')); ?>">
                    <i class="fa-solid fa-user"></i>
                </a>
            <?php endif; ?>

            <div class="d-none d-md-flex gap-2 ms-2 small fw-bold">
                <a href="<?php echo e(url('/lang/en')); ?>" class="text-decoration-none <?php echo e(app()->getLocale() == 'en' ? 'text-dark' : 'text-muted'); ?>">EN</a>
                <a href="<?php echo e(url('/lang/ar')); ?>" class="text-decoration-none <?php echo e(app()->getLocale() == 'ar' ? 'text-dark' : 'text-muted'); ?>">AR</a>
            </div>
        </div>
    </div>
</nav>

<div class="offcanvas <?php echo e(app()->getLocale() == 'ar' ? 'offcanvas-end' : 'offcanvas-start'); ?>" tabindex="-1" id="mobileMenu">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-bold"><?php echo e(__('web.menu')); ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav flex-column gap-3">
            <li class="nav-item border-bottom pb-2"><a class="nav-link p-0 fs-5" href="/"><?php echo e(__('web.home')); ?></a></li>
            <li class="nav-item">
                <p class="text-muted small mb-2 fw-bold text-uppercase"><?php echo e(__('web.category')); ?></p>
                <div class="list-group list-group-flush ps-2">
                    <a href="<?php echo e(route('product.List')); ?>" class="list-group-item list-group-item-action border-0"><?php echo e(__('web.all_products')); ?></a>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('product.List', ['id' => $category->id])); ?>" class="list-group-item list-group-item-action border-0"><?php echo e($category->name); ?></a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </li>
        </ul>

        <div class="mt-4 pt-3 border-top">
            <?php if(auth()->guard()->check()): ?>
                <p class="text-muted small mb-2 fw-bold text-uppercase"><?php echo e(__('account.account_menu')); ?></p>
                <div class="list-group list-group-flush ps-2 mb-3">
                    <a href="<?php echo e(route('account.dashboard')); ?>" class="list-group-item list-group-item-action border-0"><?php echo e(__('account.dashboard')); ?></a>
                    <a href="<?php echo e(route('account.orders')); ?>" class="list-group-item list-group-item-action border-0"><?php echo e(__('account.orders')); ?></a>
                    <a href="<?php echo e(route('account.profile')); ?>" class="list-group-item list-group-item-action border-0"><?php echo e(__('account.profile')); ?></a>
                </div>
            <?php else: ?>
                <div class="d-flex gap-3 mb-3">
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-dark btn-sm flex-fill"><?php echo e(__('account.login')); ?></a>
                    <?php if(Route::has('register')): ?>
                        <a href="<?php echo e(route('register')); ?>" class="btn btn-outline-dark btn-sm flex-fill"><?php echo e(__('account.register')); ?></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="d-flex justify-content-center gap-3">
                <a href="<?php echo e(url('/lang/en')); ?>" class="text-decoration-none fw-bold <?php echo e(app()->getLocale() == 'en' ? 'text-dark' : 'text-muted'); ?>">EN</a>
                <span class="text-muted">|</span>
                <a href="<?php echo e(url('/lang/ar')); ?>" class="text-decoration-none fw-bold <?php echo e(app()->getLocale() == 'ar' ? 'text-dark' : 'text-muted'); ?>">AR</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-search-overlay" id="searchModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0"><button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button></div>
            <div class="modal-body d-flex align-items-center">
                <form method="POST" action="<?php echo e(route('product.List')); ?>" class="container text-center">
                    <?php echo csrf_field(); ?>
                    <input type="text" name="search" class="search-input-full" placeholder="<?php echo e(__('web.search_placeholder')); ?>" required autofocus>
                    <button type="submit" class="btn btn-outline-light mt-5 px-5 py-3 rounded-pill fw-bold text-uppercase"><?php echo e(__('web.search')); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\hayah\resources\views/components/web/navbar.blade.php ENDPATH**/ ?>