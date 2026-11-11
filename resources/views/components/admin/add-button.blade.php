@props([
    'href',
    'label' => null,
])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'btn btn-dark btn-sm']) }}>
    <i class="bi bi-plus-lg me-1"></i>{{ $label ?? __('table.add') }}
</a>
