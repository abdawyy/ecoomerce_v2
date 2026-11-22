@if ($group['kind'] === 'price_range')
    <div class="price-inputs">
        <input type="number" name="min_price" placeholder="{{ __('web.min_price') }}" value="{{ request('min_price') }}" min="0">
        <span class="price-separator">-</span>
        <input type="number" name="max_price" placeholder="{{ __('web.max_price') }}" value="{{ request('max_price') }}" min="0">
    </div>
@else
    @php
        $field = match (true) {
            $group['kind'] === 'price_bands' => 'price_band',
            $group['kind'] === 'stock' => 'stock_status',
            $group['key'] === 'category' => 'category_id',
            $group['key'] === 'type' => 'type_id',
            $group['key'] === 'season' => 'season_id',
            default => $group['key'],
        };
        $selected = array_slice($selectedValues[$field] ?? [], 0, 1);
        $choices = $group['kind'] === 'stock'
            ? [
                ['value' => 'in_stock', 'label' => __('web.in_stock')],
                ['value' => 'out_of_stock', 'label' => __('web.out_of_stock')],
            ]
            : $group['choices'];
    @endphp
    @foreach ($choices as $choice)
        @php $isSelected = in_array((string) $choice['value'], $selected, true); @endphp
        <label class="plp-filter-option">
            <input class="plp-filter-option-input" type="radio" name="{{ $field }}" value="{{ $choice['value'] }}" @checked($isSelected)>
            <span>{{ $choice['label'] }}</span>
        </label>
    @endforeach
@endif
