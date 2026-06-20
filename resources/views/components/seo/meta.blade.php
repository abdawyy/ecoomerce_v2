@php
    $seo = $seo ?? app(\App\Services\SeoService::class)->resolve();
@endphp

<title>{{ $seo['title'] }}</title>
<meta name="description" content="{{ $seo['description'] }}">
@if (!empty($seo['keywords']))
    <meta name="keywords" content="{{ $seo['keywords'] }}">
@endif
<meta name="robots" content="{{ $seo['robots'] }}">
<meta name="author" content="{{ $branding->siteName() }}">

<meta property="og:title" content="{{ $seo['og_title'] }}">
<meta property="og:description" content="{{ $seo['og_description'] }}">
<meta property="og:image" content="{{ $seo['og_image'] }}">
<meta property="og:url" content="{{ $seo['canonical'] }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ $branding->siteName() }}">
<meta property="og:locale" content="{{ app()->getLocale() === 'ar' ? 'ar_AR' : 'en_US' }}">

<meta name="twitter:card" content="{{ $seo['twitter_card'] }}">
<meta name="twitter:title" content="{{ $seo['og_title'] }}">
<meta name="twitter:description" content="{{ $seo['og_description'] }}">
<meta name="twitter:image" content="{{ $seo['og_image'] }}">

<link rel="canonical" href="{{ $seo['canonical'] }}">
<link rel="alternate" hreflang="ar" href="{{ $seo['canonical'] }}">
<link rel="alternate" hreflang="en" href="{{ $seo['canonical'] }}">
<link rel="alternate" hreflang="x-default" href="{{ $seo['canonical'] }}">

@foreach ($seo['json_ld'] ?? [] as $block)
    <script type="application/ld+json">{!! json_encode($block, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endforeach
