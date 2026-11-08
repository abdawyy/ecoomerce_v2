@props([
    'product',
    'showCartOverlay' => true,
    'imageClass' => 'product-img',
])

@php
    $image = $product->productImages?->isNotEmpty()
        ? asset('storage/' . $product->productImages->first()->images)
        : $branding->placeholderProductUrl();
    $salePrice = $product->sale
        ? $product->price - ($product->price * $product->sale / 100)
        : null;
    $discountAmount = $salePrice !== null ? $product->price - $salePrice : 0;
    $discountLabel = abs($discountAmount - round($discountAmount)) < 0.001
        ? number_format($discountAmount, 0)
        : number_format($discountAmount, 2);
    $productUrl = filled($product->slug ?? null)
        ? route('product.slug', $product->slug)
        : route('product.show', $product->id);
@endphp

<div {{ $attributes->merge(['class' => 'product-card h-100']) }}>
    <div class="image-wrapper">
        @if ($product->sale)
            <div class="sale-badge">-{{ $product->sale }}%</div>
        @endif

        <a href="{{ $productUrl }}">
            <img src="{{ $image }}" class="{{ $imageClass }}" alt="{{ $product->name }}">
        </a>

        @if ($showCartOverlay)
            <a href="{{ $productUrl }}" class="btn-cart-overlay" aria-label="{{ __('web.add_to_cart') }}">
                <i class="bi bi-bag-plus-fill"></i>
            </a>
        @endif
    </div>

    <div class="pt-3 product-card-meta">
        <h6 class="fw-bold mb-1 product-card-title">
            <a href="{{ $productUrl }}" class="product-card-name">{{ $product->name }}</a>
        </h6>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            @if ($salePrice !== null)
                <span class="fw-bold text-danger">{{ number_format($salePrice, 2) }} LE</span>
                <span class="text-muted text-decoration-line-through small">{{ number_format($product->price, 2) }}</span>
            @else
                <span class="fw-bold">{{ number_format($product->price, 2) }} LE</span>
            @endif
        </div>
        @if ($discountAmount > 0)
            <div class="product-save-amount">{{ __('web.save_amount', ['amount' => $discountLabel]) }}</div>
        @endif
    </div>
</div>
