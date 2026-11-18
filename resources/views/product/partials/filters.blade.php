@php
    $mode = $mode ?? 'stack';
    $idPrefix = $idPrefix ?? 'd';
    $groups = $filterBar['groups'] ?? [];
    $selectedValues = [
        'category_id' => collect((array) request('category_id'))
            ->filter(fn ($value) => is_scalar($value) && trim((string) $value) !== '')
            ->map(fn ($value) => (string) $value)
            ->values()
            ->all(),
        'type_id' => collect((array) request('type_id'))
            ->filter(fn ($value) => is_scalar($value) && trim((string) $value) !== '')
            ->map(fn ($value) => (string) $value)
            ->values()
            ->all(),
        'color' => collect((array) request('color'))
            ->filter(fn ($value) => is_scalar($value) && trim((string) $value) !== '')
            ->map(fn ($value) => (string) $value)
            ->values()
            ->all(),
        'size' => collect((array) request('size'))
            ->filter(fn ($value) => is_scalar($value) && trim((string) $value) !== '')
            ->map(fn ($value) => (string) $value)
            ->values()
            ->all(),
        'price_band' => collect((array) request('price_band'))
            ->filter(fn ($value) => is_scalar($value) && trim((string) $value) !== '')
            ->map(fn ($value) => (string) $value)
            ->values()
            ->all(),
        'stock_status' => collect((array) request('stock_status'))
            ->filter(fn ($value) => is_scalar($value) && trim((string) $value) !== '')
            ->map(fn ($value) => (string) $value)
            ->values()
            ->all(),
    ];
    if ($selectedValues['category_id'] === [] && ! request()->has('category_id') && ! empty($id)) {
        $selectedValues['category_id'] = [(string) $id];
    }
@endphp

@foreach ($groups as $group)
    @php
        $menuId = $idPrefix.'-filter-'.$group['key'];
        $field = match (true) {
            ($group['kind'] ?? '') === 'price_bands' => 'price_band',
            ($group['kind'] ?? '') === 'stock' => 'stock_status',
            $group['key'] === 'category' => 'category_id',
            $group['key'] === 'type' => 'type_id',
            default => $group['key'],
        };
        $choiceList = ($group['kind'] ?? '') === 'stock'
            ? [
                ['value' => 'in_stock', 'label' => __('web.in_stock')],
                ['value' => 'out_of_stock', 'label' => __('web.out_of_stock')],
            ]
            : ($group['choices'] ?? []);
        $chosenValue = $selectedValues[$field][0] ?? null;
        $chosen = $chosenValue === null
            ? null
            : collect($choiceList)->first(fn ($choice) => (string) $choice['value'] === (string) $chosenValue);
        $buttonLabel = is_array($chosen) ? $chosen['label'] : __('catalog_filters.groups.'.$group['key']);
        $active = match ($group['key']) {
            'category' => $selectedValues['category_id'] !== [],
            'price' => $group['kind'] === 'price_bands'
                ? $selectedValues['price_band'] !== []
                : (request()->filled('min_price') || request()->filled('max_price')),
            'type' => $selectedValues['type_id'] !== [],
            'color' => $selectedValues['color'] !== [],
            'size' => $selectedValues['size'] !== [],
            'stock' => $selectedValues['stock_status'] !== [],
            'on_sale' => (bool) ($onSale ?? false),
            default => false,
        };
    @endphp

    @if ($mode === 'bar' && $group['key'] === 'on_sale')
        <label class="plp-filter-check {{ $active ? 'is-active' : '' }}">
            <input type="checkbox" name="on_sale" value="1" @checked($active)>
            <span>{{ __('web.on_sale') }}</span>
        </label>
        @continue
    @endif

    @if ($mode === 'bar')
        <div class="dropdown">
            <button class="plp-filter-btn dropdown-toggle {{ $active ? 'is-active' : '' }}" type="button"
                    data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                <span class="plp-filter-btn-label">{{ $buttonLabel }}</span>
            </button>
            <div class="dropdown-menu plp-filter-menu">
                @include('product.partials.filter-options', ['group' => $group, 'idPrefix' => $idPrefix, 'selectedValues' => $selectedValues])
            </div>
        </div>
    @else
        <div class="filter-section">
            @if ($group['key'] === 'on_sale')
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="on_sale" value="1" id="{{ $idPrefix }}-on-sale" @checked($active)>
                    <label class="form-check-label" for="{{ $idPrefix }}-on-sale">{{ __('web.on_sale') }}</label>
                </div>
            @else
                <button class="filter-section-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $menuId }}"
                        aria-expanded="{{ $active ? 'true' : 'false' }}">
                    <span class="filter-chosen-label">{{ $buttonLabel }}</span>
                    <i class="bi bi-chevron-down" aria-hidden="true"></i>
                </button>
                <div class="collapse {{ $active ? 'show' : '' }}" id="{{ $menuId }}">
                    @include('product.partials.filter-options', ['group' => $group, 'idPrefix' => $idPrefix, 'selectedValues' => $selectedValues])
                </div>
            @endif
        </div>
    @endif
@endforeach
