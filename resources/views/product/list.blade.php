@php
    $departmentTitle = $type_id
        ? $branding->departmentLabel(optional($types->firstWhere('id', (int) $type_id))->name ?? null)
        : null;
    $listTitle = $id ? $categoryName->name : ($departmentTitle ?: __('web.all_products'));
    app(\App\Services\SeoService::class)->setContext([
        'title' => $listTitle.' | '.$branding->siteName(),
    ]);
    $categoryInBar = collect($filterBar['groups'] ?? [])->contains(fn ($group) => $group['key'] === 'category');
    $listRouteParams = (! $categoryInBar && $id) ? ['id' => $id] : [];
    $filterAction = route('product.List', $listRouteParams);
    $clearUrl = route('product.List', $listRouteParams);
@endphp
<x-web.layout :title="$listTitle">
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/plp.css') }}?v={{ filemtime(public_path('assets/css/plp.css')) }}">
    @endpush

<section class="pb-5 plp-page">
    <div class="container py-5">
        @php
            $breadcrumbItems = [
                ['label' => __('web.home_breadcrumb'), 'url' => route('home')],
                ['label' => $listTitle, 'url' => route('product.List', $id ? ['id' => $id] : [])],
            ];
        @endphp
        <x-web.breadcrumb :items="$breadcrumbItems" />

        <form method="GET" action="{{ $filterAction }}" class="plp-filter-bar d-none d-lg-flex">
            @if (request('sort') && request('sort') !== 'newest')
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif
            @if (request()->filled('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            @include('product.partials.filters', ['mode' => 'bar', 'idPrefix' => 'd'])
            <div class="plp-filter-actions">
                <button type="submit" class="btn-filter apply">{{ __('web.filter') }}</button>
                <a href="{{ $clearUrl }}" class="btn-filter clear">{{ __('web.clear_filters') }}</a>
            </div>
        </form>

        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
            <div>
                <h1 class="fw-bold h3 mb-1">{{ $listTitle }}</h1>
                <p class="text-muted small mb-0">{{ __('web.products_count', ['count' => $data->total()]) }}</p>
            </div>

            <div class="d-flex align-items-center gap-2">
                <form method="GET" action="{{ route('product.List', $id ? ['id' => $id] : []) }}" class="d-none d-md-flex align-items-center gap-2">
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

                <button class="btn btn-dark btn-sm d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileFilterPanel">
                    <i class="fas fa-sliders-h me-2"></i>{{ __('web.filter') }}
                </button>
            </div>
        </div>

        @php
            $activeFilters = collect([
                'category_id' => request()->filled('category_id') && $categoryName ? $categoryName->name : null,
                'min_price' => ! $priceBand && $minPrice ? __('web.min_price').': '.$minPrice : null,
                'max_price' => ! $priceBand && $maxPrice ? __('web.max_price').': '.$maxPrice : null,
                'price_band' => $priceBand['label'] ?? null,
                'type_id' => $departmentTitle,
                'color' => $color ?: null,
                'size' => $size ?: null,
                'stock_status' => $stock_status === 'in_stock' ? __('web.in_stock') : ($stock_status === 'out_of_stock' ? __('web.out_of_stock') : null),
                'on_sale' => ($onSale ?? false) ? __('web.on_sale') : null,
            ])->filter();
        @endphp

        @if ($activeFilters->isNotEmpty())
            <div class="plp-active-filters">
                @foreach ($activeFilters as $key => $label)
                    <a href="{{ route('product.List', array_merge($id && $key !== 'category_id' ? ['id' => $id] : [], request()->except([$key, 'page']))) }}"
                       class="plp-filter-chip">
                        {{ $label }} <i class="bi bi-x"></i>
                    </a>
                @endforeach
                <a href="{{ $clearUrl }}" class="plp-filter-chip">{{ __('web.clear_filters') }}</a>
            </div>
        @endif

        <div class="row g-3 g-md-4">
            @forelse ($data as $product)
                <div class="col-6 col-md-4 col-lg-3 mb-3">
                    <x-web.product-card :product="$product" image-class="list-product-img" />
                </div>
            @empty
                <div class="col-12">
                    <div class="plp-empty-state">
                        <p class="mb-3">{{ __('web.no_products_found') }}</p>
                        <a href="{{ $clearUrl }}" class="btn btn-dark">{{ __('web.clear_filters') }}</a>
                        <a href="{{ route('product.List') }}" class="btn btn-outline-dark ms-2">{{ __('web.browse_all_products') }}</a>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-5">
            {!! $data->links('pagination::bootstrap-5') !!}
        </div>
    </div>
</section>

@php $filterSide = app()->getLocale() === 'ar' ? 'offcanvas-start' : 'offcanvas-end'; @endphp
<div class="offcanvas {{ $filterSide }} plp-mobile-filter" tabindex="-1" id="mobileFilterPanel" aria-labelledby="mobileFilterLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="mobileFilterLabel">{{ __('web.filter') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="{{ __('guides.close') }}"></button>
    </div>
    <div class="offcanvas-body p-0">
        <form method="GET" action="{{ $filterAction }}" class="filter-form">
            @if (request('sort') && request('sort') !== 'newest')
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif
            @if (request()->filled('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            @include('product.partials.filters', ['mode' => 'stack', 'idPrefix' => 'm'])
            <div class="filter-buttons p-3 border-top">
                <button type="submit" class="btn-filter apply">{{ __('web.filter') }}</button>
                <a href="{{ $clearUrl }}" class="btn-filter clear">{{ __('web.clear_filters') }}</a>
            </div>
        </form>
    </div>
</div>

</x-web.layout>
