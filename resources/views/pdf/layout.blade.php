<!DOCTYPE html>
<html lang="{{ $branding['locale'] }}" dir="{{ $branding['dir'] }}">
<head>
    <meta charset="UTF-8">
    <title>@yield('pdf-title', $branding['siteName'])</title>
    @include('pdf.partials.styles')
</head>
<body>
    @include('pdf.partials.header')
    @yield('content')
    @include('pdf.partials.footer')
</body>
</html>
