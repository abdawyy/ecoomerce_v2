@props(['current' => 'details'])

@php
    $steps = [
        'cart' => ['label' => __('checkout.step_cart'), 'route' => route('cart.index')],
        'details' => ['label' => __('checkout.step_details'), 'route' => route('checkout')],
        'confirm' => ['label' => __('checkout.step_confirm'), 'route' => null],
    ];
    $keys = array_keys($steps);
    $currentIndex = array_search($current, $keys, true);
    if ($currentIndex === false) {
        $currentIndex = 1;
    }
@endphp

<nav class="checkout-stepper mb-4 mb-md-5" aria-label="{{ __('checkout.progress') }}">
    @foreach ($steps as $key => $step)
        @php
            $index = $loop->index;
            $isDone = $index < $currentIndex;
            $isCurrent = $index === $currentIndex;
        @endphp
        <div class="checkout-step {{ $isDone ? 'is-done' : '' }} {{ $isCurrent ? 'is-current' : '' }}">
            <span class="checkout-step-dot" aria-hidden="true">{{ $index + 1 }}</span>
            @if ($isDone && $step['route'])
                <a href="{{ $step['route'] }}" class="checkout-step-link">{{ $step['label'] }}</a>
            @else
                <span class="checkout-step-label">{{ $step['label'] }}</span>
            @endif
        </div>
        @if (! $loop->last)
            <div class="checkout-step-line {{ $index < $currentIndex ? 'is-done' : '' }}"></div>
        @endif
    @endforeach
</nav>
