@php
    $mode = $mode ?? 'stack';
    $idPrefix = $idPrefix ?? 'd';
    $groups = $filterBar['groups'] ?? [];
    $selectedCategory = (string) ($id ?? '');
    $selectedBand = (string) request('price_band');
@endphp

@foreach ($groups as $group)
    @php
        $menuId = $idPrefix.'-filter-'.$group['key'];
        $active = match ($group['key']) {
            'category' => $selectedCategory !== '',
            'price' => $group['kind'] === 'price_bands' ? $selectedBand !== '' : (request()->filled('min_price') || request()->filled('max_price')),
            'type' => request()->filled('type_id'),
            'color' => request()->filled('color'),
            'size' => request()->filled('size'),
            'stock' => request()->filled('stock_status'),
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
                {{ __('catalog_filters.groups.'.$group['key']) }}
            </button>
            <div class="dropdown-menu plp-filter-menu">
                @include('product.partials.filter-options', ['group' => $group, 'idPrefix' => $idPrefix, 'selectedCategory' => $selectedCategory, 'selectedBand' => $selectedBand])
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
                    <span>{{ __('catalog_filters.groups.'.$group['key']) }}</span>
                    <i class="bi bi-chevron-down" aria-hidden="true"></i>
                </button>
                <div class="collapse {{ $active ? 'show' : '' }}" id="{{ $menuId }}">
                    @include('product.partials.filter-options', ['group' => $group, 'idPrefix' => $idPrefix, 'selectedCategory' => $selectedCategory, 'selectedBand' => $selectedBand])
                </div>
            @endif
        </div>
    @endif
@endforeach
