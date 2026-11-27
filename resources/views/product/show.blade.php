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

    .img-main-container {
        border-radius: 14px;
        overflow: hidden;
        background: var(--brand-soft);
        scroll-margin-top: calc(var(--header-height, 136px) + 12px);
    }
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
    .qty-group .form-control { border-inline: 0; }
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

    .js-add-to-cart.is-adding:disabled,
    .js-add-to-cart.is-added:disabled {
        opacity: 1;
        cursor: default;
    }

    .js-add-to-cart.is-added {
        text-transform: none;
        background: var(--brand-burgundy);
        border-color: var(--brand-burgundy);
    }

    .js-sticky-add-to-cart.is-go-to-cart {
        text-transform: none;
        opacity: 1;
        cursor: pointer;
    }

    .pdp-cart-flyer {
        position: fixed;
        z-index: 10050;
        object-fit: cover;
        border-radius: 12px;
        pointer-events: none;
        transition: transform 0.65s cubic-bezier(.2, .7, .2, 1), opacity 0.65s ease;
    }

    .pdp-lightbox {
        position: fixed;
        inset: 0;
        z-index: 10000;
        background: rgba(0, 0, 0, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        direction: ltr;
        touch-action: none;
        overscroll-behavior: none;
    }

    .pdp-lightbox-img {
        max-width: min(78vw, 1100px);
        max-height: 86vh;
        border-radius: 10px;
        object-fit: contain;
        user-select: none;
        -webkit-user-drag: none;
    }

    .pdp-lightbox-nav,
    .pdp-lightbox-close {
        position: absolute;
        border: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--brand-cream, #fcf4e9);
        background: var(--brand-navy, #193049);
    }

    .pdp-lightbox-nav {
        top: 50%;
        width: 46px;
        height: 46px;
        border-radius: 50%;
        transform: translateY(-50%);
        font-size: 1.35rem;
    }

    .pdp-lightbox-prev { left: 12px; }
    .pdp-lightbox-next { right: 12px; }

    .pdp-lightbox-close {
        top: 12px;
        right: 12px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        font-size: 1.6rem;
        line-height: 1;
    }

    @media (max-width: 575.98px) {
        .pdp-lightbox-img { max-width: 74vw; }
        .pdp-lightbox-prev { left: 8px; }
        .pdp-lightbox-next { right: 8px; }
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
                    <div class="img-main-container mb-2">
                        <img id="mainImage" class="img-oneProduct pointer"
                             src="{{ $product->productImages->first() ? asset('storage/' . $product->productImages->first()->images) : $branding->placeholderProductUrl() }}"
                             onclick="openImage(this)">
                    </div>
                    
                    <div class="thumb-scroll-container">
                        @foreach ($product->productImages as $productImage)
                            <img class="img-oneProduct-sub {{ $loop->first ? 'img-selected' : '' }}"
                                 src="{{ asset('storage/' . $productImage->images) }}"
                                 loading="lazy" decoding="async"
                                 onclick="changeMainImage(this)">
                        @endforeach
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
                        <button type="button" class="btn btn-dark flex-grow-1 py-3 fw-bold rounded-3 cta-btn js-add-to-cart" onclick="addToCart({{ $product->id }}, this)" @if ($totalQuantity <= 0) disabled aria-disabled="true" @endif>
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
        <button type="button" class="btn btn-dark flex-grow-1 py-2 fw-bold cta-btn js-add-to-cart js-sticky-add-to-cart" onclick="addToCart({{ $product->id }}, this)" @if ($totalQuantity <= 0) disabled aria-disabled="true" @endif>
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
        document.getElementById('mainImage').src = img.src;
        $('.img-oneProduct-sub').removeClass('img-selected');
        $(img).addClass('img-selected');
    }

    function openImage(img) {
        const thumbs = Array.from(document.querySelectorAll('.img-oneProduct-sub'));
        if (!thumbs.length || !img) return;

        let index = thumbs.findIndex(function (el) { return el.src === img.src; });
        if (index < 0) index = 0;

        const overlay = document.createElement('div');
        overlay.className = 'pdp-lightbox';
        overlay.setAttribute('role', 'dialog');
        overlay.setAttribute('aria-modal', 'true');
        overlay.innerHTML = ''
            + '<button type="button" class="pdp-lightbox-close" aria-label="Close">&times;</button>'
            + '<button type="button" class="pdp-lightbox-nav pdp-lightbox-prev" aria-label="Previous"><i class="bi bi-chevron-left"></i></button>'
            + '<img class="pdp-lightbox-img" alt="" draggable="false">'
            + '<button type="button" class="pdp-lightbox-nav pdp-lightbox-next" aria-label="Next"><i class="bi bi-chevron-right"></i></button>';
        document.body.appendChild(overlay);

        const largeImg = overlay.querySelector('.pdp-lightbox-img');
        const prevBtn = overlay.querySelector('.pdp-lightbox-prev');
        const nextBtn = overlay.querySelector('.pdp-lightbox-next');
        if (thumbs.length < 2) {
            prevBtn.hidden = true;
            nextBtn.hidden = true;
        }

        const previousOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden';

        function show(nextIndex) {
            index = (nextIndex + thumbs.length) % thumbs.length;
            largeImg.src = thumbs[index].src;
            changeMainImage(thumbs[index]);
        }

        function close() {
            document.removeEventListener('keydown', onKey);
            document.body.style.overflow = previousOverflow;
            overlay.remove();
        }

        function onKey(event) {
            if (event.key === 'Escape') close();
            if (event.key === 'ArrowLeft') show(index - 1);
            if (event.key === 'ArrowRight') show(index + 1);
        }

        show(index);
        document.addEventListener('keydown', onKey);

        overlay.addEventListener('click', function (event) {
            if (overlay.dataset.swiped === '1') {
                overlay.dataset.swiped = '0';
                return;
            }
            if (event.target === overlay) close();
        });
        overlay.querySelector('.pdp-lightbox-close').addEventListener('click', function (event) {
            event.stopPropagation();
            close();
        });
        prevBtn.addEventListener('click', function (event) {
            event.stopPropagation();
            show(index - 1);
        });
        nextBtn.addEventListener('click', function (event) {
            event.stopPropagation();
            show(index + 1);
        });
        largeImg.addEventListener('click', function (event) { event.stopPropagation(); });

        let startX = null;
        let pointerId = null;
        overlay.addEventListener('pointerdown', function (event) {
            if (event.target.closest('.pdp-lightbox-nav, .pdp-lightbox-close')) return;
            startX = event.clientX;
            pointerId = event.pointerId;
        });
        overlay.addEventListener('pointerup', function (event) {
            if (startX === null || event.pointerId !== pointerId) return;
            const delta = event.clientX - startX;
            startX = null;
            if (Math.abs(delta) < 40) return;
            overlay.dataset.swiped = '1';
            show(delta < 0 ? index + 1 : index - 1);
            window.setTimeout(function () { overlay.dataset.swiped = '0'; }, 0);
        });
        overlay.addEventListener('pointercancel', function () { startX = null; });
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
            if (btn.classList.contains('is-adding') || btn.classList.contains('is-added') || btn.classList.contains('is-go-to-cart')) return;
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
    const addedShortLabel = @json(__('web.added_short'));
    const goToCartLabel = @json(__('web.go_to_cart'));
    const cartUrl = @json(route('cart.index'));
    let addedTimer = null;

    function cartButtons() {
        return Array.from(document.querySelectorAll('.js-add-to-cart'));
    }

    function rememberCartLabels() {
        cartButtons().forEach(function (btn) {
            if (!btn.dataset.label) btn.dataset.label = btn.innerHTML;
        });
    }

    function lockCartButtons() {
        rememberCartLabels();
        cartButtons().forEach(function (btn) {
            if (btn.classList.contains('is-go-to-cart')) return;
            btn.disabled = true;
            btn.setAttribute('aria-disabled', 'true');
            btn.classList.add('is-adding');
            btn.classList.remove('is-added');
        });
    }

    function promoteStickyToCart() {
        const btn = document.querySelector('.js-sticky-add-to-cart');
        if (!btn || btn.classList.contains('is-go-to-cart')) return;
        btn.classList.remove('is-adding', 'is-added', 'js-add-to-cart');
        btn.classList.add('is-go-to-cart');
        btn.disabled = false;
        btn.removeAttribute('aria-disabled');
        btn.innerHTML = '<i class="bi bi-bag-check-fill me-1"></i> ' + goToCartLabel;
        btn.onclick = function () {
            window.location.href = cartUrl;
        };
    }

    function showAddedButtons() {
        cartButtons().forEach(function (btn) {
            if (btn.classList.contains('js-sticky-add-to-cart')) return;
            btn.classList.remove('is-adding');
            btn.classList.add('is-added');
            btn.disabled = true;
            btn.setAttribute('aria-disabled', 'true');
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> ' + addedShortLabel;
        });
        promoteStickyToCart();
        window.clearTimeout(addedTimer);
        addedTimer = window.setTimeout(restoreCartButtons, 1600);
    }

    function restoreCartButtons() {
        window.clearTimeout(addedTimer);
        cartButtons().forEach(function (btn) {
            btn.classList.remove('is-adding', 'is-added');
            if (btn.dataset.label) btn.innerHTML = btn.dataset.label;
        });
        syncAddToCartButtons(selectedStock() <= 0);
    }

    function productImageFrame() {
        return document.querySelector('.img-main-container') || document.getElementById('mainImage');
    }

    function productImageIsVisible(frame) {
        const rect = frame.getBoundingClientRect();
        const header = parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--header-height')) || 136;
        const topLimit = header + 8;
        const visibleTop = Math.max(rect.top, topLimit);
        const visibleBottom = Math.min(rect.bottom, window.innerHeight);
        const visible = Math.max(0, visibleBottom - visibleTop);
        return visible >= Math.min(rect.height, window.innerHeight - topLimit) * 0.6;
    }

    function revealImageThenFly() {
        const frame = productImageFrame();
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (reduceMotion) {
            if (frame && !productImageIsVisible(frame)) {
                window.scrollTo({ top: 0, behavior: 'auto' });
            }
            return;
        }

        const flyAfterPause = function (ms) {
            window.setTimeout(flyImageToCart, ms);
        };

        if (!frame || productImageIsVisible(frame)) {
            flyAfterPause(400);
            return;
        }

        let finished = false;
        const done = function () {
            if (finished) return;
            finished = true;
            window.removeEventListener('scrollend', done);
            flyAfterPause(200);
        };

        if ('onscrollend' in window) {
            window.addEventListener('scrollend', done, { once: true });
            window.setTimeout(done, 1200);
        } else {
            window.setTimeout(done, 700);
        }

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function flyImageToCart() {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        const img = document.getElementById('mainImage');
        const cart = document.querySelector('.storefront-cart-btn');
        if (!img || !cart || !img.getAttribute('src')) return;
        const from = img.getBoundingClientRect();
        const to = cart.getBoundingClientRect();
        if (from.width < 2 || from.height < 2) return;
        const flyer = img.cloneNode(true);
        flyer.removeAttribute('id');
        flyer.removeAttribute('onclick');
        flyer.className = 'pdp-cart-flyer';
        flyer.style.left = from.left + 'px';
        flyer.style.top = from.top + 'px';
        flyer.style.width = from.width + 'px';
        flyer.style.height = from.height + 'px';
        document.body.appendChild(flyer);
        const dx = (to.left + to.width / 2) - (from.left + from.width / 2);
        const dy = (to.top + to.height / 2) - (from.top + from.height / 2);
        requestAnimationFrame(function () {
            flyer.style.transform = 'translate(' + dx + 'px, ' + dy + 'px) scale(0.12)';
            flyer.style.opacity = '0.35';
        });
        const removeFlyer = function () { flyer.remove(); };
        flyer.addEventListener('transitionend', removeFlyer, { once: true });
        window.setTimeout(removeFlyer, 800);
    }

    function bumpCartIcon() {
        const cart = document.querySelector('.storefront-cart-btn');
        if (!cart) return;
        cart.classList.remove('is-cart-bump');
        void cart.offsetWidth;
        cart.classList.add('is-cart-bump');
        cart.addEventListener('animationend', function () {
            cart.classList.remove('is-cart-bump');
        }, { once: true });
    }

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

        lockCartButtons();

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
                    bumpCartIcon();
                    showAddedButtons();
                    revealImageThenFly();
                    if (typeof window.trackMeta === 'function' && data.meta) {
                        window.trackMeta('AddToCart', data.meta);
                    }
                    if (window.toastr) {
                        toastr.success(data.message || @json(__('web.added_to_cart')));
                    }
                } else if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    restoreCartButtons();
                    if (window.toastr) {
                        toastr.error(data.message || @json(__('web.unable_to_add_to_cart')));
                    }
                }
            })
            .catch(err => {
                restoreCartButtons();
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