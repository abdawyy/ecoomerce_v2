@props([
    'href',
    'label' => null,
])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'btn btn-primary btn-sm']) }}>
    {{ $label ?? __('table.view') }}
</a>
