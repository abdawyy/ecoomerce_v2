<x-web.layout :seo="$seo ?? null" :title="$product->name ?? null">
@push('styles')
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

    .pdp-gallery {
        display: flex;
        flex-direction: row;
        align-items: stretch;
        gap: 12px;
        direction: ltr;
    }
    .pdp-gallery .img-main-container {
        flex: 1;
        min-width: 0;
        margin-bottom: 0;
    }
    .img-main-container { border-radius: 14px; overflow: hidden; background: var(--brand-soft); }
    .img-oneProduct {
        display: block;
        width: 100%;
        height: auto;
        aspect-ratio: 4 / 5;
        object-fit: contain;
        object-position: center;
        background: var(--brand-soft);
        opacity: 1;
        transition: opacity 0.25s ease;
    }
    .img-oneProduct.is-fading { opacity: 0; }

    .thumb-scroll-container {
        display: flex;
        flex-direction: column;
        flex: 0 0 80px;
        width: 80px;
        gap: 8px;
        padding: 0;
        overflow-x: hidden;
        overflow-y: auto;
    }
    .img-oneProduct-sub {
        width: 80px;
        height: auto;
        aspect-ratio: 4 / 5;
        flex: 1 1 0;
        min-height: 64px;
        max-height: 120px;
        object-fit: contain;
        object-position: center;
        background: var(--brand-soft);
        border-radius: 10px;
        border: 2px solid transparent;
        cursor: pointer;
        transition: border-color 0.2s ease, opacity 0.2s ease;
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
    .stock-badge.is-out {
        border-color: #dc3545;
        color: #dc3545;
        background: rgba(220, 53, 69, 0.1);
    }
    .stock-badge.is-low {
        border-color: var(--brand-burgundy);
        color: var(--brand-burgundy);
        background: rgba(111, 38, 43, 0.1);
        font-weight: 800;
        font-size: 0.82rem;
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
    .size-guide-link {
        appearance: none;
        border: 0;
        background: transparent;
        padding: 0;
        margin: 0;
        font-size: 0.75rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        font-weight: 700;
        color: var(--brand-muted);
        text-decoration: underline;
        text-underline-offset: 4px;
        cursor: pointer;
    }
    .size-guide-link:hover { color: var(--brand-burgundy); }
    .size-chart-sheet {
        background: var(--brand-white);
        color: var(--brand-black);
        border: 1px solid var(--brand-border);
        border-radius: 18px;
        box-shadow: var(--shadow-soft);
        overflow: hidden;
    }
    .size-chart-sheet .modal-header { padding: 18px 20px 0; }
    .size-chart-sheet .modal-title {
        font-family: var(--font-display);
        letter-spacing: -0.02em;
    }
    .size-chart-sheet .modal-body { padding: 14px 20px 20px; }
    .size-chart-image {
        display: block;
        width: 100%;
        height: auto;
        max-height: 70vh;
        object-fit: contain;
        background: var(--brand-soft);
        border-radius: 12px;
    }
    @media (max-width: 767.98px) {
        .size-chart-modal .modal-dialog { margin: 0.75rem; }
    }
    body.modal-open .pdp-sticky-bar {
        visibility: hidden;
    }

    .qty-group .btn { width: 42px; }
    .qty-group .form-control { border-left: 0; border-right: 0; }
    .qty-group .btn:focus,
    .qty-group .btn:focus-visible {
        box-shadow: none;
        background: transparent;
        color: var(--brand-navy);
        border-color: var(--brand-navy);
        outline: none;
    }
    .qty-group .btn:hover,
    .qty-group .btn:active {
        background: var(--brand-burgundy);
        border-color: var(--brand-burgundy);
        color: #fff;
    }
    .qty-group .btn:disabled,
    .qty-group .btn:disabled:hover,
    .qty-group .btn:disabled:active,
    .qty-group .btn:disabled:focus {
        background: transparent;
        color: var(--brand-muted);
        border-color: var(--brand-border);
        opacity: 0.4;
        pointer-events: none;
        box-shadow: none;
    }

    .cta-btn {
        letter-spacing: 0.04em;
        text-transform: uppercase;
        font-size: 0.9rem;
    }

    .js-add-to-cart:disabled {
        opacity: 0.4;
        cursor: not-allowed;
        box-shadow: none;
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

    @media (max-width: 575.98px) {
        .thumb-scroll-container {
            flex: 0 0 64px;
            width: 64px;
        }
        .img-oneProduct-sub {
            width: 64px;
            min-height: 52px;
            max-height: 96px;
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
@endpush

@php $sizeChartUrl = $branding->sizeChartUrl(); @endphp
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
                    <div class="pdp-gallery">
                        <div class="thumb-scroll-container">
                            @foreach ($product->productImages as $productImage)
                                <img class="img-oneProduct-sub {{ $loop->first ? 'img-selected' : '' }}"
                                     src="{{ asset('storage/' . $productImage->images) }}"
                                     loading="lazy" decoding="async"
                                     onclick="changeMainImage(this)">
                            @endforeach
                        </div>
                        <div class="img-main-container">
                            <img id="mainImage" class="img-oneProduct pointer"
                                 src="{{ $product->productImages->first() ? asset('storage/' . $product->productImages->first()->images) : $branding->placeholderProductUrl() }}"
                                 onclick="openImage(this)">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="content-card p-4 p-md-5">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        @php $totalQuantity = $product->productItems->sum('quantity'); @endphp
                        <span class="stock-badge {{ $totalQuantity <= 0 ? 'is-out' : '' }}"
                            id="stock-badge"
                            data-in-stock="{{ __('web.in_stock') }}"
                            data-out-of-stock="{{ __('web.out_of_stock') }}"
                            data-only="{{ __('web.only_in_stock') }}"
                            data-threshold="5"
                            data-total="{{ $totalQuantity }}">
                            {{ $totalQuantity > 0 ? __('web.in_stock') : __('web.out_of_stock') }}
                        </span>
                    </div>

                    <h1 class="fw-bold fs-3 mb-1">{{ $product->name }}</h1>
                    <p class="product-catalog-meta text-secondary small mb-3">
                        {{ collect([
                            $product->category->name ?? null,
                            $product->type->name ?? null,
                            $product->season->name ?? null,
                        ])->filter()->implode(' · ') }}
                    </p>
                    @php
                        $salePrice = $product->sale
                            ? $product->price - ($product->price * $product->sale / 100)
                            : null;
                        $discountAmount = $salePrice !== null ? $product->price - $salePrice : 0;
                        $discountLabel = abs($discountAmount - round($discountAmount)) < 0.001
                            ? number_format($discountAmount, 0)
                            : number_format($discountAmount, 2);
                    @endphp
                    <div class="mb-4">
                        <h2 class="fw-bold fs-2 mb-0">
                            @if ($salePrice !== null)
                                <span class="price-current">{{ number_format($salePrice, 2) }} LE</span>
                                <span class="text-muted text-decoration-line-through fs-5 fw-normal ms-2">{{ number_format($product->price, 2) }}</span>
                            @else
                                {{ number_format($product->price, 2) }} LE
                            @endif
                        </h2>
                        @if ($discountAmount > 0)
                            <div class="product-save-amount product-save-amount-lg mt-1">{{ __('web.save_amount', ['amount' => $discountLabel]) }}</div>
                        @endif
                    </div>

                    <div class="mb-4">
                        <p class="section-title mb-1">{{ __('web.description') }}</p>
                        <p class="lh-base text-secondary">{{ $product->description }}</p>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex align-items-center justify-content-between gap-3 mb-2">
                            <p class="section-title mb-0">{{ __('web.size') }}</p>
                            @if ($sizeChartUrl && $product->productItems->isNotEmpty())
                                <button type="button" class="size-guide-link" data-bs-toggle="modal" data-bs-target="#sizeChartModal">
                                    {{ __('guides.size_guide') }}
                                </button>
                            @endif
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($product->productItems as $productItem)
                                <input type="radio" class="btn-check" name="productItem_id" id="size_{{ $productItem->id }}"
                                       value="{{ $productItem->id }}" data-qty="{{ (int) $productItem->quantity }}"
                                       {{ $productItem->quantity <= 0 ? 'disabled' : '' }}>
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
                        <p class="section-title mb-2">{{ __('web.quantity') }}</p>
                        <div class="input-group qty-group" style="width: 160px;">
                            <button id="qty-minus" class="btn btn-outline-dark" type="button" onclick="changeQuantity(-1)" aria-label="-">-</button>
                            <input type="text" id="quantity" class="form-control text-center fw-bold" value="1" readonly>
                            <button id="qty-plus" class="btn btn-outline-dark" type="button" onclick="changeQuantity(1)" aria-label="+">+</button>
                        </div>
                    </div>

                    <div class="d-flex gap-2 align-items-stretch">
                        <button type="button" class="btn btn-dark flex-grow-1 py-3 fw-bold rounded-3 cta-btn js-add-to-cart" onclick="addToCart({{ $product->id }})" @if ($totalQuantity <= 0) disabled aria-disabled="true" @endif>
                            <i class="bi bi-bag-plus-fill me-2"></i> {{ __('web.add_to_cart') }}
                        </button>
                        @php $isFavorite = collect($favoriteProductIds ?? [])->contains($product->id); @endphp
                        @auth
                            <form action="{{ route('favorites.toggle', $product) }}" method="POST" class="product-fav-form" data-no-debounce="1">
                                @csrf
                                <button type="submit" class="btn btn-outline-dark product-page-fav {{ $isFavorite ? 'is-active' : '' }}" data-product-id="{{ $product->id }}" aria-label="{{ $isFavorite ? __('web.remove_from_favorites') : __('web.add_to_favorites') }}">
                                    <i class="bi {{ $isFavorite ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-dark product-page-fav" aria-label="{{ __('web.add_to_favorites') }}">
                                <i class="bi bi-heart"></i>
                            </a>
                        @endauth
                    </div>
                    <x-web.return-policy variant="compact" class="mt-3" />

                    <hr class="my-5">

                    <div id="reviews-section">
                        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                            <h4 class="fw-bold mb-0">{{ __('web.customer_reviews') }}</h4>
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
                        @if ($canReview ?? false)
                        <div class="review-form-box shadow-sm">
                            <label class="fw-bold small mb-2">{{ __('web.rate_this_product') }}</label>
                            <div id="submitStars" class="text-muted fs-4 mb-3">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star pointer" data-value="{{ $i }}"></i>
                                @endfor
                            </div>
                            <input type="hidden" id="ratingInput" value="0">
                            <textarea id="commentInput" class="form-control mb-3" rows="3" placeholder="{{ __('web.write_review_placeholder') }}"></textarea>
                            <button class="btn btn-dark px-4" id="ajaxSubmitReview">{{ __('web.submit_review') }}</button>
                        </div>
                        @else
                        <div class="alert alert-secondary border mb-4 text-center">
                            <p class="mb-0 small text-muted">{{ __('web.review_requires_purchase') }}</p>
                        </div>
                        @endif
                    @else
                        <div class="alert alert-secondary border mb-4 text-center">
                            <p class="mb-0 small text-muted">{!! __('web.login_to_leave_review', ['login' => '<a href="'.route('login').'" class="fw-bold">'.e(__('account.login')).'</a>']) !!}</p>
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
                            <p class="text-muted small">{{ __('web.no_reviews_yet') }}</p>
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

@if ($sizeChartUrl && $product->productItems->isNotEmpty())
    <div class="modal fade size-chart-modal" id="sizeChartModal" tabindex="-1" aria-labelledby="sizeChartTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content size-chart-sheet">
                <div class="modal-header border-0">
                    <h2 class="modal-title fs-5 fw-bold mb-0" id="sizeChartTitle">{{ __('guides.size_guide') }}</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('guides.close') }}"></button>
                </div>
                <div class="modal-body">
                    <img src="{{ $sizeChartUrl }}" alt="{{ __('guides.size_guide') }}" class="size-chart-image">
                </div>
            </div>
        </div>
    </div>
@endif

<div class="pdp-sticky-bar d-lg-none">
    <div class="container d-flex align-items-center justify-content-between gap-3">
        <div>
            @if ($product->sale)
                <span class="price-current">{{ number_format($product->price - ($product->price * $product->sale / 100), 2) }} LE</span>
            @else
                <span class="fw-bold">{{ number_format($product->price, 2) }} LE</span>
            @endif
        </div>
        <button type="button" class="btn btn-dark flex-grow-1 py-2 fw-bold cta-btn js-add-to-cart" onclick="addToCart({{ $product->id }})" @if ($totalQuantity <= 0) disabled aria-disabled="true" @endif>
            <i class="bi bi-bag-plus-fill me-1"></i> {{ __('web.add_to_cart') }}
        </button>
    </div>
</div>

@push('scripts')
<script>
    @if (!empty($metaViewContent))
    if (typeof window.trackMeta === 'function') {
        window.trackMeta('ViewContent', @json($metaViewContent));
    }
    @endif

    /* Image Gallery Logic */
    function changeMainImage(img) {
        var main = document.getElementById('mainImage');
        if (!main || main.src === img.src) {
            document.querySelectorAll('.img-oneProduct-sub').forEach(function (el) { el.classList.remove('img-selected'); });
            img.classList.add('img-selected');
            return;
        }
        main.classList.add('is-fading');
        window.setTimeout(function () {
            main.src = img.src;
            main.classList.remove('is-fading');
        }, 160);
        document.querySelectorAll('.img-oneProduct-sub').forEach(function (el) { el.classList.remove('img-selected'); });
        img.classList.add('img-selected');
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

    function selectedStock() {
        const selected = document.querySelector('input[name="productItem_id"]:checked');
        if (selected) {
            return parseInt(selected.getAttribute('data-qty'), 10) || 0;
        }
        const badge = document.getElementById('stock-badge');
        return parseInt(badge?.getAttribute('data-total'), 10) || 0;
    }

    function updateStockBadge() {
        const badge = document.getElementById('stock-badge');
        if (!badge) return;
        const qty = selectedStock();
        const threshold = parseInt(badge.getAttribute('data-threshold'), 10) || 5;
        badge.classList.remove('is-low', 'is-out');
        if (qty <= 0) {
            badge.textContent = badge.getAttribute('data-out-of-stock') || '';
            badge.classList.add('is-out');
            syncAddToCartButtons(true);
            syncQtyButtons();
            return;
        }
        if (qty <= threshold) {
            const template = badge.getAttribute('data-only') || 'Only :count in stock!!';
            badge.textContent = template.replace(':count', String(qty));
            badge.classList.add('is-low');
            syncAddToCartButtons(false);
            syncQtyButtons();
            return;
        }
        badge.textContent = badge.getAttribute('data-in-stock') || '';
        syncAddToCartButtons(false);
        syncQtyButtons();
    }

    function syncAddToCartButtons(outOfStock) {
        document.querySelectorAll('.js-add-to-cart').forEach(function (btn) {
            btn.disabled = !!outOfStock;
            btn.setAttribute('aria-disabled', outOfStock ? 'true' : 'false');
        });
    }

    function maxQuantity() {
        const stock = selectedStock();
        if (stock <= 0) {
            return 0;
        }

        return Math.min(10, stock);
    }

    function syncQtyButtons() {
        const stock = selectedStock();
        const qty = parseInt(document.getElementById('quantity')?.value, 10) || 1;
        const minus = document.getElementById('qty-minus');
        const plus = document.getElementById('qty-plus');
        const outOfStock = stock <= 0;
        if (minus) {
            minus.disabled = outOfStock || qty <= 1;
        }
        if (plus) {
            plus.disabled = outOfStock || qty >= stock;
        }
    }

    /* Quantity Logic */
    function changeQuantity(val) {
        const stock = selectedStock();
        if (stock <= 0) {
            $('#quantity').val(1);
            syncQtyButtons();
            if (document.activeElement && typeof document.activeElement.blur === 'function') {
                document.activeElement.blur();
            }
            return;
        }

        let qty = parseInt($('#quantity').val(), 10) || 1;
        const max = maxQuantity();
        const next = qty + val;
        if (next >= 1 && next <= max) $('#quantity').val(next);
        syncQtyButtons();
        if (document.activeElement && typeof document.activeElement.blur === 'function') {
            document.activeElement.blur();
        }
    }

    document.querySelectorAll('input[name="productItem_id"]').forEach(function (input) {
        input.addEventListener('change', function () {
            const max = maxQuantity();
            const current = parseInt($('#quantity').val(), 10) || 1;
            if (max <= 0) {
                $('#quantity').val(1);
            } else if (current > max) {
                $('#quantity').val(max);
            }
            updateStockBadge();
        });
    });
    updateStockBadge();

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
        if (selectedStock() <= 0) {
            return;
        }

        let selectedSize = $('input[name="productItem_id"]:checked').val();
        if (!selectedSize) {
            if (window.toastr) {
                toastr.error(@json(__('web.select_size_first')));
            }
            return;
        }

        fetch('/cart/add', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
            body: JSON.stringify({
                product_id: productId,
                size_id: selectedSize,
                quantity: parseInt($('#quantity').val(), 10)
            })
        })
            .then(async (res) => {
                const data = await res.json().catch(() => ({}));
                if (!res.ok) {
                    throw new Error(data.message || @json(__('web.unable_to_add_to_cart')));
                }
                return data;
            })
            .then(data => {
                if (data.success) {
                    $('.cart-Notify, .cart-count').text(data.cartCount);
                    if (typeof window.trackMeta === 'function' && data.meta) {
                        window.trackMeta('AddToCart', data.meta);
                    }
                    if (window.toastr) {
                        toastr.success(data.message || @json(__('web.added_to_cart')));
                    }
                } else if (data.redirect) {
                    window.location.href = data.redirect;
                } else if (window.toastr) {
                    toastr.error(data.message || @json(__('web.unable_to_add_to_cart')));
                }
            })
            .catch(err => {
                if (window.toastr) {
                    toastr.error(err.message || @json(__('web.unable_to_add_to_cart')));
                }
            });
    }

    /* Submit Review AJAX */
    $('#ajaxSubmitReview').on('click', function() {
        let rating = $('#ratingInput').val();
        if (rating == 0) {
            if (window.toastr) {
                toastr.error(@json(__('web.select_rating')));
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
            success: function() { location.reload(); },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || @json(__('web.review_requires_purchase'));
                if (window.toastr) {
                    toastr.error(message);
                }
            }
        });
    });
</script>
@endpush

</x-web.layout>