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

    <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '3262657207254492');
    fbq('track', 'PageView');
    window.HayahPixel = {
        track: function (event, data) {
            if (typeof fbq !== 'function') return;
            if (data) {
                fbq('track', event, data);
            } else {
                fbq('track', event);
            }
        }
    };
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=3262657207254492&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Meta Pixel Code -->
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