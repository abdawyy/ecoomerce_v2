@props(['status'])

@php
    $canonical = \App\Support\OrderStatus::normalize($status);
    $normalized = strtolower($canonical);
    $variant = match ($normalized) {
        'completed' => 'success',
        'processing' => 'info',
        'out for delivery' => 'primary',
        'cancelled' => 'secondary',
        default => 'warning',
    };
    $textClass = in_array($variant, ['warning'], true) ? 'text-dark' : 'text-white';
    $labelKey = 'account.status_'.strtolower(str_replace(' ', '_', $canonical));
    $label = __($labelKey) === $labelKey ? $canonical : __($labelKey);
@endphp

<span {{ $attributes->merge(['class' => "badge bg-{$variant} {$textClass} account-status-pill"]) }}>
    {{ $label }}
</span>
