@props([
    'href',
    'label' => null,
])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'btn btn-sm admin-table-view']) }}>
    {{ $label ?? __('table.view') }}
</a>
