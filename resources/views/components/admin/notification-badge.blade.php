@if (($count ?? 0) > 0)
    @php
        $variant = $variant ?? 'warning';
        $textClass = match ($variant) {
            'warning' => 'text-dark',
            'danger', 'info', 'primary', 'success' => 'text-white',
            default => '',
        };
    @endphp
    <span class="badge bg-{{ $variant }} {{ $textClass }} {{ app()->getLocale() === 'ar' ? 'me-auto' : 'ms-auto' }}">{{ $count }}</span>
@endif
