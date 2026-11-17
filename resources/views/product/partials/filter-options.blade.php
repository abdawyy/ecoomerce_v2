@if ($group['kind'] === 'price_range')
    <div class="price-inputs">
        <input type="number" name="min_price" placeholder="{{ __('web.min_price') }}" value="{{ request('min_price') }}" min="0">
        <span class="price-separator">-</span>
        <input type="number" name="max_price" placeholder="{{ __('web.max_price') }}" value="{{ request('max_price') }}" min="0" max="{{ $maxProductPrice }}">
    </div>
@elseif ($group['kind'] === 'price_bands')
    <div class="form-check">
        <input class="form-check-input" type="radio" name="price_band" value="" id="{{ $idPrefix }}-price-any" @checked($selectedBand === '')>
        <label class="form-check-label" for="{{ $idPrefix }}-price-any">{{ __('web.all_products') }}</label>
    </div>
    @foreach ($group['choices'] as $choice)
        <div class="form-check">
            <input class="form-check-input" type="radio" name="price_band" value="{{ $choice['value'] }}"
                   id="{{ $idPrefix }}-price-{{ $choice['value'] }}" @checked($selectedBand === (string) $choice['value'])>
            <label class="form-check-label" for="{{ $idPrefix }}-price-{{ $choice['value'] }}">{{ $choice['label'] }}</label>
        </div>
    @endforeach
@elseif ($group['kind'] === 'stock')
    <div class="form-check">
        <input class="form-check-input" type="radio" name="stock_status" value="" id="{{ $idPrefix }}-stock-any" @checked(! request()->filled('stock_status'))>
        <label class="form-check-label" for="{{ $idPrefix }}-stock-any">{{ __('web.all_products') }}</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="stock_status" value="in_stock" id="{{ $idPrefix }}-in-stock" @checked(request('stock_status') === 'in_stock')>
        <label class="form-check-label" for="{{ $idPrefix }}-in-stock">{{ __('web.in_stock') }}</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="stock_status" value="out_of_stock" id="{{ $idPrefix }}-out-stock" @checked(request('stock_status') === 'out_of_stock')>
        <label class="form-check-label" for="{{ $idPrefix }}-out-stock">{{ __('web.out_of_stock') }}</label>
    </div>
@else
    @php
        $field = match ($group['key']) {
            'category' => 'category_id',
            'type' => 'type_id',
            default => $group['key'],
        };
        $current = match ($group['key']) {
            'category' => $selectedCategory,
            'type' => (string) request('type_id'),
            default => (string) request($field),
        };
    @endphp
    <div class="form-check">
        <input class="form-check-input" type="radio" name="{{ $field }}" value="" id="{{ $idPrefix }}-{{ $group['key'] }}-any" @checked($current === '')>
        <label class="form-check-label" for="{{ $idPrefix }}-{{ $group['key'] }}-any">{{ __('web.all_products') }}</label>
    </div>
    @foreach ($group['choices'] as $choice)
        <div class="form-check">
            <input class="form-check-input" type="radio" name="{{ $field }}" value="{{ $choice['value'] }}"
                   id="{{ $idPrefix }}-{{ $group['key'] }}-{{ md5($choice['value']) }}"
                   @checked($current === (string) $choice['value'])>
            <label class="form-check-label" for="{{ $idPrefix }}-{{ $group['key'] }}-{{ md5($choice['value']) }}">{{ $choice['label'] }}</label>
        </div>
    @endforeach
@endif
