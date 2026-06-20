@props(['dark' => false])

<a href="{{ url('/') }}">
    <img src="{{ $branding->logoUrl($dark) }}" alt="{{ $branding->logoAlt() }}" {{ $attributes->merge(['style' => 'max-width: 160px; height: auto;']) }}>
</a>
