@props(['variant' => 'bar'])

@php
    $shipping = app(\App\Services\ShippingService::class);
    $freeDeliveryOn = $shipping->isEnabled();
    $freeDeliveryAmount = number_format($shipping->threshold(), 0);
    $stacked = ($variant ?? 'bar') === 'stack';
@endphp

@if ($stacked)
    <div class="product-trust">
        @if ($freeDeliveryOn)
            <p class="product-trust-line">{{ __('web.free_delivery_above', ['amount' => $freeDeliveryAmount]) }}</p>
        @endif
        <p class="product-trust-line mb-0">
            <a href="{{ route('legal') }}#returns">{{ __('web.returns_7_days') }}</a>
        </p>
    </div>
@else
    <div class="storefront-trust-bar">
        @if ($freeDeliveryOn)
            <span>{{ __('web.free_delivery_above', ['amount' => $freeDeliveryAmount]) }}</span>
        @endif
        <a href="{{ route('legal') }}#returns">{{ __('web.returns_7_days') }}</a>
    </div>
@endif
