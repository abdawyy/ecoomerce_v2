<?php if (isset($component)) { $__componentOriginal628d23ec5db1173b09efbdbd01a50602 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal628d23ec5db1173b09efbdbd01a50602 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.web.layout','data' => ['seo' => $seo ?? null,'title' => $product->name ?? null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('web.layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['seo' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($seo ?? null),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($product->name ?? null)]); ?>
<?php $__env->startPush('styles'); ?>
<style>
    .pointer { cursor: pointer; }

    .page-wrap {
        background: var(--brand-white);
        padding: 24px 0 60px;
    }
    .content-card {
        background: var(--brand-white);
        border-radius: 18px;
        box-shadow: var(--shadow-soft);
        border: 1px solid var(--brand-border);
        color: var(--brand-black);
    }
    .section-title {
        font-size: 0.75rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--brand-muted);
        font-weight: 700;
    }

    .img-main-container { border-radius: 14px; overflow: hidden; background: var(--brand-soft); }
    .img-oneProduct { width: 100%; object-fit: cover; aspect-ratio: 4 / 5; }
    .img-main-container img { transition: transform 0.3s ease; }
    .img-main-container:hover img { transform: scale(1.02); }

    .thumb-scroll-container {
        display: flex;
        gap: 10px;
        padding: 15px 0;
        overflow-x: auto;
    }
    .img-oneProduct-sub {
        width: 76px;
        height: 76px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid transparent;
        transition: 0.3s;
        box-shadow: 0 6px 16px rgba(0,0,0,0.08);
    }
    .img-selected { border-color: var(--brand-black); }

    .stock-badge {
        border: 1px solid #28a745;
        color: #28a745;
        border-radius: 50px;
        padding: 2px 12px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        background: rgba(40, 167, 69, 0.12);
    }

    .selector-label {
        border: 1px solid var(--brand-border);
        padding: 8px 18px;
        border-radius: 10px;
        transition: 0.2s;
        cursor: pointer;
        font-weight: 600;
        background: var(--brand-white);
        color: var(--brand-black);
    }
    .btn-check:checked + .selector-label {
        background-color: var(--brand-black);
        color: var(--brand-white);
        border-color: var(--brand-black);
    }
    .btn-check:disabled + .selector-label { opacity: 0.3; text-decoration: line-through; }

    .qty-group .btn { width: 42px; }
    .qty-group .form-control { border-left: 0; border-right: 0; }

    .cta-btn {
        letter-spacing: 0.04em;
        text-transform: uppercase;
        font-size: 0.9rem;
    }

    .review-card {
        border-radius: 14px;
        border: 1px solid var(--brand-border);
        background: var(--brand-white);
    }

    .reviews-list {
        max-height: none;
        overflow: visible;
    }

    @media (max-width: 992px) {
        .thumb-scroll-container {
            flex-wrap: wrap;
            overflow-x: visible;
        }
    }

    .review-form-box {
        background: var(--brand-soft);
        border: 1px solid var(--brand-border);
        border-radius: 14px;
        padding: 22px;
        margin-bottom: 30px;
    }
</style>
<?php $__env->stopPush(); ?>

<section id="oneProduct" class="page-wrap">
    <div class="container">
        <?php if (isset($component)) { $__componentOriginal4fe67e161b5ccdf1be7da2c8e05f80d9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4fe67e161b5ccdf1be7da2c8e05f80d9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.web.breadcrumb','data' => ['items' => [
            ['label' => __('web.home_breadcrumb'), 'url' => route('home')],
            ['label' => $product->category->name ?? __('web.category'), 'url' => route('product.List', ['id' => $product->category_id])],
            ['label' => $product->name, 'url' => ''],
        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('web.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['label' => __('web.home_breadcrumb'), 'url' => route('home')],
            ['label' => $product->category->name ?? __('web.category'), 'url' => route('product.List', ['id' => $product->category_id])],
            ['label' => $product->name, 'url' => ''],
        ])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4fe67e161b5ccdf1be7da2c8e05f80d9)): ?>
<?php $attributes = $__attributesOriginal4fe67e161b5ccdf1be7da2c8e05f80d9; ?>
<?php unset($__attributesOriginal4fe67e161b5ccdf1be7da2c8e05f80d9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4fe67e161b5ccdf1be7da2c8e05f80d9)): ?>
<?php $component = $__componentOriginal4fe67e161b5ccdf1be7da2c8e05f80d9; ?>
<?php unset($__componentOriginal4fe67e161b5ccdf1be7da2c8e05f80d9); ?>
<?php endif; ?>

        <div class="row g-5">
            <div class="col-12 col-lg-6">
                <div class="content-card p-3 p-md-4">
                    <div class="img-main-container mb-2">
                        <img id="mainImage" class="img-oneProduct pointer"
                             src="<?php echo e($product->productImages->first() ? asset('storage/' . $product->productImages->first()->images) : $branding->placeholderProductUrl()); ?>"
                             onclick="openImage(this)">
                    </div>
                    
                    <div class="thumb-scroll-container">
                        <?php $__currentLoopData = $product->productImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $productImage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <img class="img-oneProduct-sub <?php echo e($loop->first ? 'img-selected' : ''); ?>"
                                 src="<?php echo e(asset('storage/' . $productImage->images)); ?>" 
                                 onclick="changeMainImage(this)">
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="content-card p-4 p-md-5">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <?php $totalQuantity = $product->productItems->sum('quantity'); ?>
                        <span class="stock-badge"><?php echo e($totalQuantity > 0 ? 'In Stock' : 'Out of Stock'); ?></span>
                    </div>

                    <h1 class="fw-bold fs-3 mb-1"><?php echo e($product->name); ?></h1>
                    <h2 class="fw-bold fs-2 mb-4">
                        <?php if($product->sale): ?>
                            <span class="text-danger"><?php echo e(number_format($product->price - ($product->price * $product->sale / 100), 2)); ?> LE</span>
                            <span class="text-muted text-decoration-line-through fs-5 fw-normal ms-2"><?php echo e(number_format($product->price, 2)); ?></span>
                        <?php else: ?>
                            <?php echo e(number_format($product->price, 2)); ?> LE
                        <?php endif; ?>
                    </h2>

                    <div class="mb-4">
                        <p class="section-title mb-1">Description</p>
                        <p class="lh-base text-secondary"><?php echo e($product->description); ?></p>
                    </div>

                    <div class="mb-4">
                        <p class="section-title mb-2">Size</p>
                        <div class="d-flex flex-wrap gap-2">
                            <?php $__currentLoopData = $product->productItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $productItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <input type="radio" class="btn-check" name="productItem_id" id="size_<?php echo e($productItem->id); ?>"
                                       value="<?php echo e($productItem->id); ?>" <?php echo e($productItem->quantity <= 0 ? 'disabled' : ''); ?>>
                                <label class="selector-label" for="size_<?php echo e($productItem->id); ?>"><?php echo e($productItem->size); ?></label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <?php if($product->color): ?>
                        <div class="mb-4">
                            <p class="section-title mb-2"><?php echo e(__('web.color')); ?></p>
                            <span class="selector-label d-inline-block"><?php echo e($product->color); ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="mb-4">
                        <p class="section-title mb-2">Quantity</p>
                        <div class="input-group qty-group" style="width: 160px;">
                            <button class="btn btn-outline-dark" type="button" onclick="changeQuantity(-1)">-</button>
                            <input type="text" id="quantity" class="form-control text-center fw-bold" value="1" readonly>
                            <button class="btn btn-outline-dark" type="button" onclick="changeQuantity(1)">+</button>
                        </div>
                    </div>

                    <button class="btn btn-dark w-100 py-3 fw-bold rounded-3 cta-btn" onclick="addToCart(<?php echo e($product->id); ?>)">
                        <i class="bi bi-bag-plus-fill me-2"></i> Add to Cart
                    </button>

                    <?php if($product->guide): ?>
                        <a href="<?php echo e(route('guides.download', $product->guide->slug)); ?>" class="btn btn-outline-dark w-100 mt-2 py-2">
                            <i class="fa-solid fa-download me-1"></i> <?php echo e(__('guides.product_link')); ?>: <?php echo e($product->guide->title()); ?>

                        </a>
                    <?php endif; ?>

                    <hr class="my-5">

                    <div id="reviews-section">
                        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                            <h4 class="fw-bold mb-0">Customer Reviews</h4>
                            <?php if(($reviewCount ?? 0) > 0): ?>
                                <div class="text-warning">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi <?php echo e($i <= round($avgRating) ? 'bi-star-fill' : 'bi-star'); ?>"></i>
                                    <?php endfor; ?>
                                    <span class="text-muted small ms-1"><?php echo e(number_format($avgRating, 1)); ?> · <?php echo e(__('web.reviews_count', ['count' => $reviewCount])); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    
                    <?php if(auth()->guard()->check()): ?>
                        <div class="review-form-box shadow-sm">
                            <label class="fw-bold small mb-2">Rate this product</label>
                            <div id="submitStars" class="text-muted fs-4 mb-3">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <i class="bi bi-star pointer" data-value="<?php echo e($i); ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <input type="hidden" id="ratingInput" value="0">
                            <textarea id="commentInput" class="form-control mb-3" rows="3" placeholder="Write your review..."></textarea>
                            <button class="btn btn-dark px-4" id="ajaxSubmitReview">Submit Review</button>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-secondary border mb-4 text-center">
                            <p class="mb-0 small text-muted">Please <a href="<?php echo e(route('login')); ?>" class="fw-bold">Login</a> to leave a review.</p>
                        </div>
                    <?php endif; ?>

                    <div id="reviewsList" class="review-card p-3 reviews-list">
                        <?php $__empty_1 = true; $__currentLoopData = $product->reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold small"><?php echo e($review->user->name); ?></span>
                                    <div class="text-warning small">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <i class="bi <?php echo e($i <= $review->rating ? 'bi-star-fill' : 'bi-star'); ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <p class="mb-0 small text-muted mt-1"><?php echo e($review->comment); ?></p>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="text-muted small">No reviews yet. Be the first to rate!</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if(($relatedProducts ?? collect())->isNotEmpty()): ?>
            <div class="mt-5 pt-4">
                <h3 class="fw-bold mb-4"><?php echo e(__('web.related_products')); ?></h3>
                <div class="row g-3 g-md-4">
                    <?php $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-6 col-lg-3">
                            <?php if (isset($component)) { $__componentOriginal9dbde93c50e49c2830e855fc2dc390af = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9dbde93c50e49c2830e855fc2dc390af = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.web.product-card','data' => ['product' => $related]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('web.product-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['product' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($related)]); ?>
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
        <?php endif; ?>
    </div>
</section>

<div class="pdp-sticky-bar d-lg-none">
    <div class="container d-flex align-items-center justify-content-between gap-3">
        <div>
            <?php if($product->sale): ?>
                <span class="fw-bold text-danger"><?php echo e(number_format($product->price - ($product->price * $product->sale / 100), 2)); ?> LE</span>
            <?php else: ?>
                <span class="fw-bold"><?php echo e(number_format($product->price, 2)); ?> LE</span>
            <?php endif; ?>
        </div>
        <button class="btn btn-dark flex-grow-1 py-2 fw-bold" onclick="addToCart(<?php echo e($product->id); ?>)">
            <i class="bi bi-bag-plus-fill me-1"></i> <?php echo e(__('web.add_to_cart')); ?>

        </button>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    /* Image Gallery Logic */
    function changeMainImage(img) {
        document.getElementById('mainImage').src = img.src;
        $('.img-oneProduct-sub').removeClass('img-selected');
        $(img).addClass('img-selected');
    }

    function openImage(img) {
        const overlay = document.createElement('div');
        overlay.style = "position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.9); display:flex; align-items:center; justify-content:center; z-index:9999; cursor:zoom-out;";
        const largeImg = document.createElement('img');
        largeImg.src = img.src;
        largeImg.style = "max-width:90%; max-height:90%; border-radius:10px;";
        overlay.appendChild(largeImg);
        document.body.appendChild(overlay);
        overlay.onclick = () => document.body.removeChild(overlay);
    }

    /* Quantity Logic */
    function changeQuantity(val) {
        let qty = parseInt($('#quantity').val());
        if (qty + val >= 1 && qty + val <= 10) $('#quantity').val(qty + val);
    }

    /* Stars Rating Logic */
    $('#submitStars i').on('click', function() {
        let rating = $(this).data('value');
        $('#ratingInput').val(rating);
        $('#submitStars i').removeClass('bi-star-fill text-warning').addClass('bi-star');
        $('#submitStars i').each(function() {
            if ($(this).data('value') <= rating) {
                $(this).removeClass('bi-star').addClass('bi-star-fill text-warning');
            }
        });
    });

    /* Add to Cart AJAX */
    function addToCart(productId) {
        let selectedSize = $('input[name="productItem_id"]:checked').val();
        if (!selectedSize) {
            if (window.toastr) {
                toastr.error("Please select a size first");
            }
            return;
        }

        fetch('/cart/add', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>" },
            body: JSON.stringify({
                product_id: productId,
                size_id: selectedSize,
                quantity: parseInt($('#quantity').val(), 10)
            })
        })
            .then(async (res) => {
                const data = await res.json().catch(() => ({}));
                if (!res.ok) {
                    throw new Error(data.message || 'Unable to add to cart.');
                }
                return data;
            })
            .then(data => {
                if (data.success) {
                    $('.cart-Notify, .cart-count').text(data.cartCount);
                    if (window.toastr) {
                        toastr.success(data.message || "Added to cart.");
                    }
                } else if (data.redirect) {
                    window.location.href = data.redirect;
                } else if (window.toastr) {
                    toastr.error(data.message || "Unable to add to cart.");
                }
            })
            .catch(err => {
                if (window.toastr) {
                    toastr.error(err.message || "Unable to add to cart.");
                }
            });
    }

    /* Submit Review AJAX */
    $('#ajaxSubmitReview').on('click', function() {
        let rating = $('#ratingInput').val();
        if (rating == 0) {
            if (window.toastr) {
                toastr.error("Please select a star rating");
            }
            return;
        }

        $.ajax({
            url: "/reviews",
            method: "POST",
            data: {
                _token: "<?php echo e(csrf_token()); ?>",
                product_id: "<?php echo e($product->id); ?>",
                rating: rating,
                comment: $('#commentInput').val()
            },
            success: function() { location.reload(); }
        });
    });
</script>
<?php $__env->stopPush(); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal628d23ec5db1173b09efbdbd01a50602)): ?>
<?php $attributes = $__attributesOriginal628d23ec5db1173b09efbdbd01a50602; ?>
<?php unset($__attributesOriginal628d23ec5db1173b09efbdbd01a50602); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal628d23ec5db1173b09efbdbd01a50602)): ?>
<?php $component = $__componentOriginal628d23ec5db1173b09efbdbd01a50602; ?>
<?php unset($__componentOriginal628d23ec5db1173b09efbdbd01a50602); ?>
<?php endif; ?><?php /**PATH C:\hayah\resources\views/product/show.blade.php ENDPATH**/ ?>