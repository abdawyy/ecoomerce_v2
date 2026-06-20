<x-web.layout :title="$id ? $categoryName->name : __('web.all_products')">
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/plp.css') }}">
    @endpush

<section class="pb-5 plp-page">
    <div class="container py-5">
        <div class="row">
            <!-- Desktop Sidebar -->
            <aside class="col-lg-3 d-none d-lg-block">
                <div class="card card-aside position-sticky" style="top: 100px;">
                    <div class="filter-sidebar-header">
                        <h6 class="filter-title">{{ __('web.filter') }}</h6>
                    </div>
                    
                    <form method="GET" action="{{ route('product.List', ($id ? ['id' => $id] : [])) }}" class="filter-form">
                        <!-- Categories -->
                        <div class="filter-section">
                            <h6 class="filter-section-title">{{ __('web.category') }}</h6>
                            <nav class="nav flex-column nav-pills">
                                <a href="{{ route('product.List') }}" class="nav-link {{ !$id ? 'active' : '' }}">
                                    {{ __('web.all_products') }}
                                </a>
                                @foreach ($categories as $category)
                                    <a href="{{ route('product.List', ['id' => $category->id]) }}" 
                                       class="nav-link {{ $id == $category->id ? 'active' : '' }}">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            </nav>
                        </div>

                        <!-- Price Range -->
                        <div class="filter-section">
                            <h6 class="filter-section-title">{{ __('web.price') }}</h6>
                            <div class="price-inputs">
                                <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}" min="0">
                                <span class="price-separator">-</span>
                                <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}" min="0" max="{{ $maxProductPrice }}">
                            </div>
                        </div>

                        <!-- Type -->
                        @if(is_array($types) && count($types) || (is_object($types) && $types->count()))
                        <div class="filter-section">
                            <h6 class="filter-section-title">{{ __('products.type') }}</h6>
                            @foreach ($types as $type)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type_id" value="{{ $type->id }}" 
                                           id="type-{{ $type->id }}" {{ request('type_id') == $type->id ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type-{{ $type->id }}">
                                        {{ $type->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @endif

                        <!-- Color -->
                        @if(is_array($colors) && count($colors) || (is_object($colors) && $colors->count()))
                        <div class="filter-section">
                            <h6 class="filter-section-title">{{ __('web.color') }}</h6>
                            @foreach ($colors as $c)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="color" value="{{ $c }}" 
                                           id="color-{{ $c }}" {{ request('color') == $c ? 'checked' : '' }}>
                                    <label class="form-check-label" for="color-{{ $c }}">
                                        {{ $c }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @endif

                        <!-- Size -->
                        @if(is_array($sizes) && count($sizes) || (is_object($sizes) && $sizes->count()))
                        <div class="filter-section">
                            <h6 class="filter-section-title">{{ __('web.size') }}</h6>
                            @foreach ($sizes as $s)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="size" value="{{ $s }}" 
                                           id="size-{{ $s }}" {{ request('size') == $s ? 'checked' : '' }}>
                                    <label class="form-check-label" for="size-{{ $s }}">
                                        {{ $s }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @endif

                        <!-- Stock Status -->
                        <div class="filter-section">
                            <h6 class="filter-section-title">{{ __('web.stock') }}</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="stock_status" value="in_stock" 
                                       id="in-stock" {{ request('stock_status') == 'in_stock' ? 'checked' : '' }}>
                                <label class="form-check-label" for="in-stock">
                                    {{ __('web.in_stock') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="stock_status" value="out_of_stock" 
                                       id="out-stock" {{ request('stock_status') == 'out_of_stock' ? 'checked' : '' }}>
                                <label class="form-check-label" for="out-stock">
                                    {{ __('web.out_of_stock') }}
                                </label>
                            </div>
                        </div>

                        <!-- On sale -->
                        <div class="filter-section">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="on_sale" value="1" id="on-sale"
                                       {{ ($onSale ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="on-sale">{{ __('web.on_sale') }}</label>
                            </div>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="filter-buttons">
                            <button type="submit" class="btn-filter apply">Filter</button>
                            <a href="{{ route('product.List', ($id ? ['id' => $id] : [])) }}" class="btn-filter clear">Clear</a>
                        </div>
                    </form>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="col-lg-9 col-12">
                @php
                    $listRouteParams = $id ? ['id' => $id] : [];
                    $breadcrumbItems = [
                        ['label' => __('web.home_breadcrumb'), 'url' => route('home')],
                        ['label' => $id ? $categoryName->name : __('web.all_products'), 'url' => route('product.List', $listRouteParams)],
                    ];
                @endphp

                <x-web.breadcrumb :items="$breadcrumbItems" />

                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                    <div>
                        <h1 class="fw-bold h3 mb-1">{{ $id ? $categoryName->name : __('web.all_products') }}</h1>
                        <p class="text-muted small mb-0">{{ __('web.products_count', ['count' => $data->total()]) }}</p>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <form method="GET" action="{{ route('product.List', $listRouteParams) }}" class="d-none d-md-flex align-items-center gap-2">
                            @foreach (request()->except('sort', 'page') as $key => $value)
                                @if (is_scalar($value) && $value !== '')
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach
                            <label for="sort" class="small text-muted mb-0">{{ __('web.sort_by') }}</label>
                            <select name="sort" id="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="newest" @selected(($sort ?? 'newest') === 'newest')>{{ __('web.sort_newest') }}</option>
                                <option value="price_asc" @selected(($sort ?? '') === 'price_asc')>{{ __('web.sort_price_asc') }}</option>
                                <option value="price_desc" @selected(($sort ?? '') === 'price_desc')>{{ __('web.sort_price_desc') }}</option>
                            </select>
                        </form>

                        @php $filterSide = app()->getLocale() === 'ar' ? 'offcanvas-start' : 'offcanvas-end'; @endphp
                        <button class="btn btn-dark btn-sm d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#mobileFilterPanel">
                            <i class="fas fa-sliders-h me-2"></i>{{ __('web.filter') }}
                        </button>
                    </div>
                </div>

                @php
                    $activeFilters = collect([
                        'min_price' => $minPrice ? __('web.min_price') . ': ' . $minPrice : null,
                        'max_price' => $maxPrice ? __('web.max_price') . ': ' . $maxPrice : null,
                        'type_id' => $type_id ? optional($types->firstWhere('id', $type_id))->name : null,
                        'color' => $color ?: null,
                        'size' => $size ?: null,
                        'stock_status' => $stock_status === 'in_stock' ? __('web.in_stock') : ($stock_status === 'out_of_stock' ? __('web.out_of_stock') : null),
                        'on_sale' => ($onSale ?? false) ? __('web.on_sale') : null,
                    ])->filter();
                @endphp

                @if ($activeFilters->isNotEmpty())
                    <div class="plp-active-filters">
                        @foreach ($activeFilters as $key => $label)
                            <a href="{{ route('product.List', array_merge($listRouteParams, request()->except([$key, 'page']))) }}"
                               class="plp-filter-chip">
                                {{ $label }} <i class="bi bi-x"></i>
                            </a>
                        @endforeach
                        <a href="{{ route('product.List', $listRouteParams) }}" class="plp-filter-chip">{{ __('web.clear_filters') }}</a>
                    </div>
                @endif

                <!-- Products Grid -->
                <div class="row g-3 g-md-4">
                    @forelse ($data as $product)
                        <div class="col-6 col-lg-3 mb-3">
                            <x-web.product-card :product="$product" image-class="list-product-img" />
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="plp-empty-state">
                                <p class="mb-3">{{ __('web.no_products_found') }}</p>
                                <a href="{{ route('product.List', $listRouteParams) }}" class="btn btn-dark">{{ __('web.clear_filters') }}</a>
                                <a href="{{ route('product.List') }}" class="btn btn-outline-dark ms-2">{{ __('web.browse_all_products') }}</a>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-5">
                    {!! $data->links('pagination::bootstrap-5') !!}
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mobile Filter Modal (Offcanvas) -->
@php $filterSide = app()->getLocale() === 'ar' ? 'offcanvas-start' : 'offcanvas-end'; @endphp
<div class="offcanvas {{ $filterSide }} plp-mobile-filter" tabindex="-1" id="mobileFilterPanel" aria-labelledby="mobileFilterLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="mobileFilterLabel">{{ __('web.filter') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <form method="GET" action="{{ route('product.List', ($id ? ['id' => $id] : [])) }}" class="filter-form">
            <!-- All Filter Sections in Mobile -->
            
            <!-- Categories -->
            <div class="filter-section">
                <h6 class="filter-section-title">{{ __('web.category') }}</h6>
                <nav class="nav flex-column nav-pills">
                    <a href="{{ route('product.List') }}" class="nav-link" data-bs-dismiss="offcanvas">
                        {{ __('web.all_products') }}
                    </a>
                    @foreach ($categories as $category)
                        <a href="{{ route('product.List', ['id' => $category->id]) }}" class="nav-link" data-bs-dismiss="offcanvas">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </nav>
            </div>

            <!-- Price Range -->
            <div class="filter-section">
                <h6 class="filter-section-title">{{ __('web.price') }}</h6>
                <div class="price-inputs">
                    <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}" min="0">
                    <span class="price-separator">-</span>
                    <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}" min="0" max="{{ $maxProductPrice }}">
                </div>
            </div>

            <!-- Type -->
            @if(is_array($types) && count($types) || (is_object($types) && $types->count()))
            <div class="filter-section">
                <h6 class="filter-section-title">{{ __('products.type') }}</h6>
                @foreach ($types as $type)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type_id" value="{{ $type->id }}" 
                               id="m-type-{{ $type->id }}" {{ request('type_id') == $type->id ? 'checked' : '' }}>
                        <label class="form-check-label" for="m-type-{{ $type->id }}">
                            {{ $type->name }}
                        </label>
                    </div>
                @endforeach
            </div>
            @endif

            <!-- Color -->
            @if(is_array($colors) && count($colors) || (is_object($colors) && $colors->count()))
            <div class="filter-section">
                <h6 class="filter-section-title">{{ __('web.color') }}</h6>
                @foreach ($colors as $c)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="color" value="{{ $c }}" 
                               id="m-color-{{ $c }}" {{ request('color') == $c ? 'checked' : '' }}>
                        <label class="form-check-label" for="m-color-{{ $c }}">
                            {{ $c }}
                        </label>
                    </div>
                @endforeach
            </div>
            @endif

            <!-- Size -->
            @if(is_array($sizes) && count($sizes) || (is_object($sizes) && $sizes->count()))
            <div class="filter-section">
                <h6 class="filter-section-title">{{ __('web.size') }}</h6>
                @foreach ($sizes as $s)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="size" value="{{ $s }}" 
                               id="m-size-{{ $s }}" {{ request('size') == $s ? 'checked' : '' }}>
                        <label class="form-check-label" for="m-size-{{ $s }}">
                            {{ $s }}
                        </label>
                    </div>
                @endforeach
            </div>
            @endif

            <!-- Stock Status -->
            <div class="filter-section">
                <h6 class="filter-section-title">{{ __('web.stock') }}</h6>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="stock_status" value="in_stock"
                           id="m-in-stock" {{ request('stock_status') == 'in_stock' ? 'checked' : '' }}>
                    <label class="form-check-label" for="m-in-stock">
                        {{ __('web.in_stock') }}
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="stock_status" value="out_of_stock"
                           id="m-out-stock" {{ request('stock_status') == 'out_of_stock' ? 'checked' : '' }}>
                    <label class="form-check-label" for="m-out-stock">
                        {{ __('web.out_of_stock') }}
                    </label>
                </div>
            </div>

            <div class="filter-section">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="on_sale" value="1" id="m-on-sale"
                           {{ ($onSale ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="m-on-sale">{{ __('web.on_sale') }}</label>
                </div>
            </div>

            <!-- Filter Buttons -->
            <div class="filter-buttons p-3 border-top">
                <button type="submit" class="btn-filter apply">Filter</button>
                <a href="{{ route('product.List', ($id ? ['id' => $id] : [])) }}" class="btn-filter clear">Clear</a>
            </div>
        </form>
    </div>
</div>

</x-web.layout>
