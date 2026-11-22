@php
    $departmentTitle = count($typeIds ?? []) === 1
        ? optional($types->firstWhere('id', (int) $typeIds[0]))->name
        : null;
    $listTitle = ($categoryName?->name) ?: ($departmentTitle ?: __('web.all_products'));
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
                <form method="GET" action="{{ route('product.List', ($id && ! request()->has('category_id')) ? ['id' => $id] : []) }}" class="plp-sort-form d-none d-md-flex align-items-center gap-2">
                    @foreach (request()->except('sort', 'page') as $key => $value)
                        @foreach ((array) $value as $item)
                            @if (is_scalar($item) && $item !== '')
                                <input type="hidden" name="{{ is_array($value) ? $key.'[]' : $key }}" value="{{ $item }}">
                            @endif
                        @endforeach
                    @endforeach
                    <label for="sort" class="plp-sort-label small text-muted mb-0">{{ __('web.sort_by') }}</label>
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
            $keepPath = $id && ! request()->has('category_id');
            $filterUrlWithout = function (string $key, string $value) use ($id, $keepPath) {
                $params = request()->except(['page']);
                $current = collect((array) ($params[$key] ?? []))
                    ->filter(fn ($item) => is_scalar($item) && trim((string) $item) !== '')
                    ->map(fn ($item) => (string) $item)
                    ->reject(fn ($item) => $item === $value)
                    ->values()
                    ->all();
                unset($params[$key]);
                if ($current !== []) {
                    $params[$key] = $current;
                }
                if ($key !== 'category_id' && $keepPath) {
                    $params = ['id' => $id] + $params;
                }

                return route('product.List', $params);
            };
            $activeFilters = collect();
            foreach ($selectedCategories ?? [] as $category) {
                $activeFilters->push([
                    'label' => $category->name,
                    'url' => $filterUrlWithout('category_id', (string) $category->id),
                ]);
            }
            if (($priceBands ?? []) === []) {
                if ($minPrice) {
                    $activeFilters->push([
                        'label' => __('web.min_price').': '.$minPrice,
                        'url' => $filterUrlWithout('min_price', (string) $minPrice),
                    ]);
                }
                if ($maxPrice) {
                    $activeFilters->push([
                        'label' => __('web.max_price').': '.$maxPrice,
                        'url' => $filterUrlWithout('max_price', (string) $maxPrice),
                    ]);
                }
            }
            foreach ($priceBands ?? [] as $band) {
                $activeFilters->push([
                    'label' => $band['label'],
                    'url' => $filterUrlWithout('price_band', (string) $band['id']),
                ]);
            }
            foreach ($typeIds ?? [] as $selectedTypeId) {
                $activeFilters->push([
                    'label' => optional($types->firstWhere('id', (int) $selectedTypeId))->name,
                    'url' => $filterUrlWithout('type_id', (string) $selectedTypeId),
                ]);
            }
            foreach ($seasonIds ?? [] as $selectedSeasonId) {
                $activeFilters->push([
                    'label' => optional($seasons->firstWhere('id', (int) $selectedSeasonId))->name,
                    'url' => $filterUrlWithout('season_id', (string) $selectedSeasonId),
                ]);
            }
            foreach ($colors ?? [] as $selectedColor) {
                $activeFilters->push([
                    'label' => $selectedColor,
                    'url' => $filterUrlWithout('color', (string) $selectedColor),
                ]);
            }
            foreach ($sizes ?? [] as $selectedSize) {
                $activeFilters->push([
                    'label' => $selectedSize,
                    'url' => $filterUrlWithout('size', (string) $selectedSize),
                ]);
            }
            foreach ($stockStatuses ?? [] as $selectedStock) {
                $activeFilters->push([
                    'label' => $selectedStock === 'in_stock' ? __('web.in_stock') : __('web.out_of_stock'),
                    'url' => $filterUrlWithout('stock_status', (string) $selectedStock),
                ]);
            }
            if ($onSale ?? false) {
                $activeFilters->push([
                    'label' => __('web.on_sale'),
                    'url' => $filterUrlWithout('on_sale', '1'),
                ]);
            }
        @endphp

        @if ($activeFilters->isNotEmpty())
            <div class="plp-active-filters">
                @foreach ($activeFilters as $filter)
                    <a href="{{ $filter['url'] }}" class="plp-filter-chip">
                        {{ $filter['label'] }} <i class="bi bi-x"></i>
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

@push('scripts')
<script>
    document.addEventListener('change', function (event) {
        const input = event.target;
        if (!input.classList || !input.classList.contains('plp-filter-option-input')) {
            return;
        }
        const text = input.closest('.plp-filter-option')?.querySelector('span')?.textContent?.trim();
        const toggle = input.closest('.dropdown')?.querySelector('[data-bs-toggle="dropdown"]');
        const buttonLabel = toggle?.querySelector('.plp-filter-btn-label');
        if (buttonLabel && text) {
            buttonLabel.textContent = text;
        }
        if (toggle && window.bootstrap?.Dropdown) {
            window.bootstrap.Dropdown.getOrCreateInstance(toggle).hide();
        }
        const sectionLabel = input.closest('.filter-section')?.querySelector('.filter-chosen-label');
        if (sectionLabel && text) {
            sectionLabel.textContent = text;
        }
    });

    window.addEventListener('scroll', function (event) {
        const target = event.target;
        if (target && target.closest && target.closest('.plp-filter-menu')) {
            return;
        }
        if (!window.bootstrap?.Dropdown) {
            return;
        }
        document.querySelectorAll('.plp-filter-bar [data-bs-toggle="dropdown"]').forEach(function (toggle) {
            window.bootstrap.Dropdown.getInstance(toggle)?.hide();
        });
    }, { passive: true });
</script>
@endpush

</x-web.layout>
