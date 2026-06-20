@props(['product' => null, 'class' => 'img-fluid'])

@php
    $src = $product?->productImages?->first()?->images
        ? asset('storage/' . $product->productImages->first()->images)
        : $branding->placeholderProductUrl();
    $alt = $product->name ?? $branding->logoAlt();
@endphp

<img src="{{ $src }}" alt="{{ $alt }}" {{ $attributes->merge(['class' => $class]) }}>
