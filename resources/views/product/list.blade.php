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
                        @include('product.partials.filters', ['idPrefix' => 'd', 'linkDismiss' => false])

                        <div class="filter-buttons">
                            <button type="submit" class="btn-filter apply">{{ __('web.filter') }}</button>
                            <a href="{{ route('product.List', ($id ? ['id' => $id] : [])) }}" class="btn-filter clear">{{ __('web.clear_filters') }}</a>
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
            @include('product.partials.filters', ['idPrefix' => 'm', 'linkDismiss' => true])

            <div class="filter-buttons p-3 border-top">
                <button type="submit" class="btn-filter apply">{{ __('web.filter') }}</button>
                <a href="{{ route('product.List', ($id ? ['id' => $id] : [])) }}" class="btn-filter clear">{{ __('web.clear_filters') }}</a>
            </div>
        </form>
    </div>
</div>

</x-web.layout>
