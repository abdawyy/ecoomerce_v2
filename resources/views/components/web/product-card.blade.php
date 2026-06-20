@props([
    'product',
    'showCartOverlay' => true,
    'imageClass' => 'product-img',
])

@php
    $image = $product->productImages->isNotEmpty()
        ? asset('storage/' . $product->productImages->first()->images)
        : $branding->placeholderProductUrl();
    $salePrice = $product->sale
        ? $product->price - ($product->price * $product->sale / 100)
        : null;
@endphp

<div {{ $attributes->merge(['class' => 'product-card h-100']) }}>
    <div class="image-wrapper">
        @if ($product->sale)
            <div class="sale-badge">-{{ $product->sale }}%</div>
        @endif

        <a href="{{ route('product.show', $product->id) }}">
            <img src="{{ $image }}" class="{{ $imageClass }}" alt="{{ $product->name }}">
        </a>

        @if ($showCartOverlay)
            <a href="{{ route('product.show', $product->id) }}" class="btn-cart-overlay" aria-label="{{ __('web.add_to_cart') }}">
                <i class="bi bi-bag-plus-fill"></i>
            </a>
        @endif
    </div>

    <div class="pt-3">
        <h6 class="fw-bold mb-1 product-card-title">{{ $product->name }}</h6>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            @if ($salePrice !== null)
                <span class="fw-bold text-danger">{{ number_format($salePrice, 2) }} LE</span>
                <span class="text-muted text-decoration-line-through small">{{ number_format($product->price, 2) }}</span>
            @else
                <span class="fw-bold">{{ number_format($product->price, 2) }} LE</span>
            @endif
        </div>
    </div>
</div>
