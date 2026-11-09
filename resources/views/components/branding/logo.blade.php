@props([
    'dark' => false,
    'href' => null,
    'link' => true,
    'compact' => false,
])

@php
    $headerLight = public_path('assets/img/logo-true-label-header.png');
    $headerDark = public_path('assets/img/logo-true-label-header-dark.png');
    $useCompact = $compact && is_file($headerLight) && is_file($headerDark);
    $lightUrl = $useCompact
        ? asset('assets/img/logo-true-label-header.png').'?v='.filemtime($headerLight)
        : $branding->logoUrl(false);
    $darkUrl = $useCompact
        ? asset('assets/img/logo-true-label-header-dark.png').'?v='.filemtime($headerDark)
        : $branding->logoUrl(true);
    $hasDark = $useCompact || (filled($branding->settings()->logo_dark_path) && $darkUrl !== $lightUrl);
    $alt = $branding->logoAlt();
    $href = $href ?? url('/');
@endphp

@if ($link)
    <a href="{{ $href }}" class="branding-logo-link d-inline-block">
@endif
    @if ($dark && $hasDark)
        <img src="{{ $darkUrl }}" alt="{{ $alt }}" {{ $attributes->merge(['class' => 'branding-logo-img', 'style' => 'max-width: 180px; height: auto; object-fit: contain;']) }}>
    @else
        <img src="{{ $lightUrl }}" alt="{{ $alt }}" {{ $attributes->merge(['class' => 'branding-logo-img is-theme-light', 'style' => 'max-width: 180px; height: auto; object-fit: contain;']) }}>
        @if ($hasDark)
            <img src="{{ $darkUrl }}" alt="" {{ $attributes->merge(['class' => 'branding-logo-img is-theme-dark', 'style' => 'max-width: 180px; height: auto; object-fit: contain;']) }}>
        @endif
    @endif
@if ($link)
    </a>
@endif
