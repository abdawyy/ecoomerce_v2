@props(['status'])

@php
    $normalized = strtolower($status ?? 'pending');
    $variant = match ($normalized) {
        'completed' => 'success',
        'processing' => 'info',
        'cancelled' => 'secondary',
        default => 'warning',
    };
    $textClass = in_array($variant, ['warning'], true) ? 'text-dark' : 'text-white';
@endphp

<span {{ $attributes->merge(['class' => "badge bg-{$variant} {$textClass} account-status-pill"]) }}>
    {{ $status }}
</span>
