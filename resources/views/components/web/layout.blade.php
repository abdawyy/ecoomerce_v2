@props([
    'title' => null,
    'seo' => null,
])

@php
    $isRtl = app()->getLocale() === 'ar';
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Language" content="ar, en">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <x-seo.meta :seo="$seo ?? null" />

    @if ($title)
        <title>{{ $title }} | {{ $branding->siteName() }}</title>
    @endif

    @if ($branding->faviconUrl())
        <link rel="icon" href="{{ $branding->faviconUrl() }}" type="image/x-icon">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset($isRtl ? 'assets/css/bootstrap.rtl.min.css' : 'assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>
    <link rel="stylesheet" href="{{ asset('assets/css/storefront-shell.css') }}?v={{ filemtime(public_path('assets/css/storefront-shell.css')) }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v={{ filemtime(public_path('assets/css/style.css')) }}">
    <link rel="stylesheet" href="{{ asset('assets/css/storefront-theme.css') }}?v={{ filemtime(public_path('assets/css/storefront-theme.css')) }}">
    <script src="{{ asset('assets/js/storefront-theme.js') }}?v={{ filemtime(public_path('assets/js/storefront-theme.js')) }}"></script>
</head>
<body class="storefront-body">
    <a class="skip-to-content" href="#storefront-main">{{ __('web.skip_to_content') }}</a>
    <x-web.navbar />

    <div class="storefront-main" id="storefront-main">
        {{ $slot }}
    </div>

    @stack('styles')

    <x-web.footer />
    @stack('scripts')
</body>
</html>