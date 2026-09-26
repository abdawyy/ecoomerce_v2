<x-web.layout :seo="$seo ?? null" :title="$product->name ?? null">
<x-slot:styles>
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
</x-slot:styles>

<section id="oneProduct" class="page-wrap">
    <div class="container">
        <x-web.breadcrumb :items="[
            ['label' => __('web.home_breadcrumb'), 'url' => route('home')],
            ['label' => $product->category->name ?? __('web.category'), 'url' => route('product.List', ['id' => $product->category_id])],
            ['label' => $product->name, 'url' => ''],
        ]" />

        <div class="row g-5">
            <div class="col-12 col-lg-6">
                <div class="content-card p-3 p-md-4">
                    <div class="img-main-container mb-2">
                        <img id="mainImage" class="img-oneProduct pointer"
                             src="{{ $product->productImages->first() ? asset('storage/' . $product->productImages->first()->images) : $branding->placeholderProductUrl() }}"
                             onclick="openImage(this)">
                    </div>
                    
                    <div class="thumb-scroll-container">
                        @foreach ($product->productImages as $productImage)
                            <img class="img-oneProduct-sub {{ $loop->first ? 'img-selected' : '' }}"
                                 src="{{ asset('storage/' . $productImage->images) }}" 
                                 onclick="changeMainImage(this)">
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="content-card p-4 p-md-5">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        @php $totalQuantity = $product->productItems->sum('quantity'); @endphp
                        <span class="stock-badge">{{ $totalQuantity > 0 ? 'In Stock' : 'Out of Stock' }}</span>
                    </div>

                    <h1 class="fw-bold fs-3 mb-1">{{ $product->name }}</h1>
                    <h2 class="fw-bold fs-2 mb-4">
                        @if ($product->sale)
                            <span class="text-danger">{{ number_format($product->price - ($product->price * $product->sale / 100), 2) }} LE</span>
                            <span class="text-muted text-decoration-line-through fs-5 fw-normal ms-2">{{ number_format($product->price, 2) }}</span>
                        @else
                            {{ number_format($product->price, 2) }} LE
                        @endif
                    </h2>

                    <div class="mb-4">
                        <p class="section-title mb-1">Description</p>
                        <p class="lh-base text-secondary">{{ $product->description }}</p>
                    </div>

                    <div class="mb-4">
                        <p class="section-title mb-2">Size</p>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($product->productItems as $productItem)
                                <input type="radio" class="btn-check" name="productItem_id" id="size_{{ $productItem->id }}"
                                       value="{{ $productItem->id }}" {{ $productItem->quantity <= 0 ? 'disabled' : '' }}>
                                <label class="selector-label" for="size_{{ $productItem->id }}">{{ $productItem->size }}</label>
                            @endforeach
                        </div>
                    </div>

                    @if ($product->color)
                        <div class="mb-4">
                            <p class="section-title mb-2">{{ __('web.color') }}</p>
                            <span class="selector-label d-inline-block">{{ $product->color }}</span>
                        </div>
                    @endif

                    <div class="mb-4">
                        <p class="section-title mb-2">Quantity</p>
                        <div class="input-group qty-group" style="width: 160px;">
                            <button class="btn btn-outline-dark" type="button" onclick="changeQuantity(-1)">-</button>
                            <input type="text" id="quantity" class="form-control text-center fw-bold" value="1" readonly>
                            <button class="btn btn-outline-dark" type="button" onclick="changeQuantity(1)">+</button>
                        </div>
                    </div>

                    <button class="btn btn-dark w-100 py-3 fw-bold rounded-3 cta-btn" onclick="addToCart({{ $product->id }})">
                        <i class="bi bi-bag-plus-fill me-2"></i> Add to Cart
                    </button>

                    @if ($product->guide)
                        <a href="{{ route('guides.download', $product->guide->slug) }}" class="btn btn-outline-dark w-100 mt-2 py-2">
                            <i class="fa-solid fa-download me-1"></i> {{ __('guides.product_link') }}: {{ $product->guide->title() }}
                        </a>
                    @endif

                    <hr class="my-5">

                    <div id="reviews-section">
                        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                            <h4 class="fw-bold mb-0">Customer Reviews</h4>
                            @if (($reviewCount ?? 0) > 0)
                                <div class="text-warning">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="bi {{ $i <= round($avgRating) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                    @endfor
                                    <span class="text-muted small ms-1">{{ number_format($avgRating, 1) }} · {{ __('web.reviews_count', ['count' => $reviewCount]) }}</span>
                                </div>
                            @endif
                        </div>
                    
                    @auth
                        <div class="review-form-box shadow-sm">
                            <label class="fw-bold small mb-2">Rate this product</label>
                            <div id="submitStars" class="text-muted fs-4 mb-3">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star pointer" data-value="{{ $i }}"></i>
                                @endfor
                            </div>
                            <input type="hidden" id="ratingInput" value="0">
                            <textarea id="commentInput" class="form-control mb-3" rows="3" placeholder="Write your review..."></textarea>
                            <button class="btn btn-dark px-4" id="ajaxSubmitReview">Submit Review</button>
                        </div>
                    @else
                        <div class="alert alert-secondary border mb-4 text-center">
                            <p class="mb-0 small text-muted">Please <a href="{{ route('login') }}" class="fw-bold">Login</a> to leave a review.</p>
                        </div>
                    @endauth

                    <div id="reviewsList" class="review-card p-3 reviews-list">
                        @forelse ($product->reviews as $review)
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold small">{{ $review->user->name }}</span>
                                    <div class="text-warning small">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="mb-0 small text-muted mt-1">{{ $review->comment }}</p>
                            </div>
                        @empty
                            <p class="text-muted small">No reviews yet. Be the first to rate!</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        @if (($relatedProducts ?? collect())->isNotEmpty())
            <div class="mt-5 pt-4">
                <h3 class="fw-bold mb-4">{{ __('web.related_products') }}</h3>
                <div class="row g-3 g-md-4">
                    @foreach ($relatedProducts as $related)
                        <div class="col-6 col-lg-3">
                            <x-web.product-card :product="$related" />
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

<div class="pdp-sticky-bar d-lg-none">
    <div class="container d-flex align-items-center justify-content-between gap-3">
        <div>
            @if ($product->sale)
                <span class="fw-bold text-danger">{{ number_format($product->price - ($product->price * $product->sale / 100), 2) }} LE</span>
            @else
                <span class="fw-bold">{{ number_format($product->price, 2) }} LE</span>
            @endif
        </div>
        <button class="btn btn-dark flex-grow-1 py-2 fw-bold" onclick="addToCart({{ $product->id }})">
            <i class="bi bi-bag-plus-fill me-1"></i> {{ __('web.add_to_cart') }}
        </button>
    </div>
</div>

<x-slot:scripts>
<script>
    @php
        $pixelUnitPrice = (float) ($product->sale
            ? ($product->price - ($product->price * $product->sale / 100))
            : $product->price);
    @endphp
    if (window.HayahPixel) {
        window.HayahPixel.track('ViewContent', {
            content_ids: [@json((string) $product->id)],
            content_type: 'product',
            content_name: @json($product->name),
            value: {{ number_format($pixelUnitPrice, 2, '.', '') }},
            currency: 'EGP'
        });
    }

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

        const quantity = parseInt($('#quantity').val(), 10) || 1;
        const unitPrice = {{ (float) ($product->sale ? ($product->price - ($product->price * $product->sale / 100)) : $product->price) }};

        fetch('/cart/add', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
            body: JSON.stringify({
                product_id: productId,
                size_id: selectedSize,
                quantity: quantity
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
                    if (window.HayahPixel) {
                        window.HayahPixel.track('AddToCart', {
                            content_ids: [String(productId)],
                            content_type: 'product',
                            content_name: @json($product->name),
                            value: Number((unitPrice * quantity).toFixed(2)),
                            currency: 'EGP',
                            contents: [{ id: String(productId), quantity: quantity }]
                        });
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
                _token: "{{ csrf_token() }}",
                product_id: "{{ $product->id }}",
                rating: rating,
                comment: $('#commentInput').val()
            },
            success: function() { location.reload(); }
        });
    });
</script>
</x-slot:scripts>

</x-web.layout>