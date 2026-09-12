@php
    $idPrefix = $idPrefix ?? 'd';
    $linkDismiss = !empty($linkDismiss);
    $dismissAttr = $linkDismiss ? ' data-bs-dismiss="offcanvas"' : '';

    $open = [
        'category' => (bool) $id,
        'price' => request()->filled('min_price') || request()->filled('max_price'),
        'type' => request()->filled('type_id'),
        'color' => request()->filled('color'),
        'size' => request()->filled('size'),
        'stock' => request()->filled('stock_status'),
    ];
@endphp

{{-- Categories --}}
<div class="filter-section">
    <button class="filter-section-toggle" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#{{ $idPrefix }}-filter-category"
            aria-expanded="{{ $open['category'] ? 'true' : 'false' }}"
            aria-controls="{{ $idPrefix }}-filter-category">
        <span>{{ __('web.category') }}</span>
        <i class="bi bi-chevron-down" aria-hidden="true"></i>
    </button>
    <div class="collapse {{ $open['category'] ? 'show' : '' }}" id="{{ $idPrefix }}-filter-category">
        <nav class="nav flex-column nav-pills">
            <a href="{{ route('product.List') }}"
               class="nav-link {{ !$id ? 'active' : '' }}"{!! $dismissAttr !!}>
                {{ __('web.all_products') }}
            </a>
            @foreach ($categories as $category)
                <a href="{{ route('product.List', ['id' => $category->id]) }}"
                   class="nav-link {{ $id == $category->id ? 'active' : '' }}"{!! $dismissAttr !!}>
                    {{ $category->name }}
                </a>
            @endforeach
        </nav>
    </div>
</div>

{{-- Price --}}
<div class="filter-section">
    <button class="filter-section-toggle" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#{{ $idPrefix }}-filter-price"
            aria-expanded="{{ $open['price'] ? 'true' : 'false' }}"
            aria-controls="{{ $idPrefix }}-filter-price">
        <span>{{ __('web.price') }}</span>
        <i class="bi bi-chevron-down" aria-hidden="true"></i>
    </button>
    <div class="collapse {{ $open['price'] ? 'show' : '' }}" id="{{ $idPrefix }}-filter-price">
        <div class="price-inputs">
            <input type="number" name="min_price" placeholder="{{ __('web.min_price') }}" value="{{ request('min_price') }}" min="0">
            <span class="price-separator">-</span>
            <input type="number" name="max_price" placeholder="{{ __('web.max_price') }}" value="{{ request('max_price') }}" min="0" max="{{ $maxProductPrice }}">
        </div>
    </div>
</div>

{{-- Type --}}
@if ((is_array($types) && count($types)) || (is_object($types) && $types->count()))
<div class="filter-section">
    <button class="filter-section-toggle" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#{{ $idPrefix }}-filter-type"
            aria-expanded="{{ $open['type'] ? 'true' : 'false' }}"
            aria-controls="{{ $idPrefix }}-filter-type">
        <span>{{ __('products.type') }}</span>
        <i class="bi bi-chevron-down" aria-hidden="true"></i>
    </button>
    <div class="collapse {{ $open['type'] ? 'show' : '' }}" id="{{ $idPrefix }}-filter-type">
        @foreach ($types as $type)
            <div class="form-check">
                <input class="form-check-input" type="radio" name="type_id" value="{{ $type->id }}"
                       id="{{ $idPrefix }}-type-{{ $type->id }}" {{ request('type_id') == $type->id ? 'checked' : '' }}>
                <label class="form-check-label" for="{{ $idPrefix }}-type-{{ $type->id }}">
                    {{ $type->name }}
                </label>
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- Color --}}
@if ((is_array($colors) && count($colors)) || (is_object($colors) && $colors->count()))
<div class="filter-section">
    <button class="filter-section-toggle" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#{{ $idPrefix }}-filter-color"
            aria-expanded="{{ $open['color'] ? 'true' : 'false' }}"
            aria-controls="{{ $idPrefix }}-filter-color">
        <span>{{ __('web.color') }}</span>
        <i class="bi bi-chevron-down" aria-hidden="true"></i>
    </button>
    <div class="collapse {{ $open['color'] ? 'show' : '' }}" id="{{ $idPrefix }}-filter-color">
        @foreach ($colors as $c)
            <div class="form-check">
                <input class="form-check-input" type="radio" name="color" value="{{ $c }}"
                       id="{{ $idPrefix }}-color-{{ $c }}" {{ request('color') == $c ? 'checked' : '' }}>
                <label class="form-check-label" for="{{ $idPrefix }}-color-{{ $c }}">
                    {{ $c }}
                </label>
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- Size --}}
@if ((is_array($sizes) && count($sizes)) || (is_object($sizes) && $sizes->count()))
<div class="filter-section">
    <button class="filter-section-toggle" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#{{ $idPrefix }}-filter-size"
            aria-expanded="{{ $open['size'] ? 'true' : 'false' }}"
            aria-controls="{{ $idPrefix }}-filter-size">
        <span>{{ __('web.size') }}</span>
        <i class="bi bi-chevron-down" aria-hidden="true"></i>
    </button>
    <div class="collapse {{ $open['size'] ? 'show' : '' }}" id="{{ $idPrefix }}-filter-size">
        @foreach ($sizes as $s)
            <div class="form-check">
                <input class="form-check-input" type="radio" name="size" value="{{ $s }}"
                       id="{{ $idPrefix }}-size-{{ $s }}" {{ request('size') == $s ? 'checked' : '' }}>
                <label class="form-check-label" for="{{ $idPrefix }}-size-{{ $s }}">
                    {{ $s }}
                </label>
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- Stock --}}
<div class="filter-section">
    <button class="filter-section-toggle" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#{{ $idPrefix }}-filter-stock"
            aria-expanded="{{ $open['stock'] ? 'true' : 'false' }}"
            aria-controls="{{ $idPrefix }}-filter-stock">
        <span>{{ __('web.stock') }}</span>
        <i class="bi bi-chevron-down" aria-hidden="true"></i>
    </button>
    <div class="collapse {{ $open['stock'] ? 'show' : '' }}" id="{{ $idPrefix }}-filter-stock">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="stock_status" value="in_stock"
                   id="{{ $idPrefix }}-in-stock" {{ request('stock_status') == 'in_stock' ? 'checked' : '' }}>
            <label class="form-check-label" for="{{ $idPrefix }}-in-stock">
                {{ __('web.in_stock') }}
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="stock_status" value="out_of_stock"
                   id="{{ $idPrefix }}-out-stock" {{ request('stock_status') == 'out_of_stock' ? 'checked' : '' }}>
            <label class="form-check-label" for="{{ $idPrefix }}-out-stock">
                {{ __('web.out_of_stock') }}
            </label>
        </div>
    </div>
</div>

{{-- On sale stays visible --}}
<div class="filter-section">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="on_sale" value="1" id="{{ $idPrefix }}-on-sale"
               {{ ($onSale ?? false) ? 'checked' : '' }}>
        <label class="form-check-label" for="{{ $idPrefix }}-on-sale">{{ __('web.on_sale') }}</label>
    </div>
</div>
