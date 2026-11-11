@props(['href'])

@php
    $arrow = app()->getLocale() === 'ar' ? 'right' : 'left';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'admin-return-link']) }}>
    <i class="bi bi-arrow-{{ $arrow }}"></i>
    <span>{{ __('table.return') }}</span>
</a>
