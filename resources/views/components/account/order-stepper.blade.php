@props(['status'])

@php
    $canonical = \App\Support\OrderStatus::normalize($status);
    $normalized = strtolower($canonical);
    $steps = [
        ['key' => 'pending', 'label' => __('account.step_pending')],
        ['key' => 'processing', 'label' => __('account.step_processing')],
        ['key' => 'out for delivery', 'label' => __('account.step_out_for_delivery')],
        ['key' => 'completed', 'label' => __('account.step_completed')],
    ];
    $activeIndex = match ($normalized) {
        'completed' => 3,
        'out for delivery' => 2,
        'processing' => 1,
        default => 0,
    };
@endphp

@if ($normalized === 'cancelled')
    <div class="account-stepper account-stepper-cancelled mb-3">
        <x-account.status-pill :status="$status" />
    </div>
@else
    <div class="account-stepper mb-4">
        @foreach ($steps as $index => $step)
            @php
                $isDone = $index < $activeIndex;
                $isCurrent = $index === $activeIndex;
            @endphp
            <div class="account-step {{ $isDone ? 'is-done' : '' }} {{ $isCurrent ? 'is-current' : '' }}">
                <span class="account-step-dot">{{ $index + 1 }}</span>
                <span class="account-step-label">{{ $step['label'] }}</span>
            </div>
            @if (! $loop->last)
                <div class="account-step-line {{ $index < $activeIndex ? 'is-done' : '' }}"></div>
            @endif
        @endforeach
    </div>
@endif
