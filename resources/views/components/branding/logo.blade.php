@props([
    'dark' => false,
    'href' => null,
    'link' => true,
])

@php
    $url = $branding->logoUrl($dark);
    $alt = $branding->logoAlt();
    $href = $href ?? url('/');
@endphp

@if ($link)
    <a href="{{ $href }}" class="branding-logo-link d-inline-block">
        <img src="{{ $url }}"
             alt="{{ $alt }}"
             {{ $attributes->merge(['class' => 'branding-logo-img', 'style' => 'max-width: 160px; height: auto; object-fit: contain;']) }}>
    </a>
@else
    <img src="{{ $url }}"
         alt="{{ $alt }}"
         {{ $attributes->merge(['class' => 'branding-logo-img', 'style' => 'max-width: 160px; height: auto; object-fit: contain;']) }}>
@endif
