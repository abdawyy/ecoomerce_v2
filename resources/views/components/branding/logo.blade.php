@props([
    'dark' => false,
    'href' => null,
    'link' => true,
    'compact' => false,
])

@php
    $headerLight = public_path('assets/img/logo-true-label-header.png');
    $headerDark = public_path('assets/img/logo-true-label-header-dark.png');
    $fallbackDark = public_path('assets/img/logo-true-label-dark.png');
    $useCompact = $compact && is_file($headerLight);
    $lightUrl = $useCompact
        ? asset('assets/img/logo-true-label-header.png').'?v='.filemtime($headerLight)
        : $branding->logoUrl(false);
    if ($useCompact && is_file($headerDark)) {
        $darkUrl = asset('assets/img/logo-true-label-header-dark.png').'?v='.filemtime($headerDark);
    } elseif ($useCompact && is_file($fallbackDark)) {
        $darkUrl = asset('assets/img/logo-true-label-dark.png').'?v='.filemtime($fallbackDark);
    } else {
        $darkUrl = $branding->logoUrl(true);
    }
    $hasDark = filled($darkUrl) && $darkUrl !== $lightUrl;
    $alt = $branding->logoAlt();
    $href = $href ?? url('/');
    $imgClass = trim('branding-logo-img '.$attributes->get('class'));
    $imgStyle = $attributes->get('style') ?: 'max-width: 180px; height: auto; object-fit: contain;';
@endphp

@if ($link)
    <a href="{{ $href }}" class="branding-logo-link d-inline-block">
@else
    <span class="branding-logo-link d-inline-block">
@endif
    @if ($dark)
        <img src="{{ $hasDark ? $darkUrl : $lightUrl }}" alt="{{ $alt }}"
            class="{{ $imgClass }} branding-logo-img-on-dark" style="{{ $imgStyle }}">
    @else
        <img src="{{ $lightUrl }}" alt="{{ $alt }}"
            class="{{ $imgClass }} is-theme-light" style="{{ $imgStyle }}">
        @if ($hasDark)
            <img src="{{ $darkUrl }}" alt=""
                class="{{ $imgClass }} is-theme-dark" style="{{ $imgStyle }}">
        @endif
    @endif
@if ($link)
    </a>
@else
    </span>
@endif
