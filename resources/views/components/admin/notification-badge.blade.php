@php
    $variant = $variant ?? 'warning';
    $count = (int) ($count ?? 0);
    $key = $key ?? null;
    $textClass = match ($variant) {
        'warning' => 'text-dark',
        'danger', 'info', 'primary', 'success' => 'text-white',
        default => '',
    };
@endphp
<span @if ($key) data-admin-notify="{{ $key }}" @endif
    class="badge bg-{{ $variant }} {{ $textClass }} {{ app()->getLocale() === 'ar' ? 'me-auto' : 'ms-auto' }} {{ $count > 0 ? '' : 'd-none' }}">{{ $count > 99 ? '99+' : $count }}</span>
