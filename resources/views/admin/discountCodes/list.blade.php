<x-admin.header />
<x-admin.aside />
<x-admin.navbar />

<main id="main">
    <div class="container">
        <div class="row pt-4">
            @php $isRtl = app()->getLocale() === 'ar'; @endphp

            <div class="pagetitle d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div>
                    <h1>{{ __('discount_codes.title') }}</h1>
                    <nav>
                        <ol class="breadcrumb d-flex {{ $isRtl ? 'text-end' : 'text-start' }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                            <li class="breadcrumb-item">
                                <a href="#">{{ __('discount_codes.breadcrumb_main') }}</a>
                            </li>
                            <li class="mx-2">-</li>
                            <li class="breadcrumb-item active">
                                {{ __('discount_codes.breadcrumb_active') }}
                            </li>
                        </ol>
                    </nav>
                </div>
                <x-admin.add-button :href="\App\Support\AdminListReturn::addUrl('discountCodes.edit', 'discountCodes')" :label="__('admin_sidebar.add_discount_code')" />
            </div>

            <x-admin.discount-tabs />

            <!-- Filter -->
            <div class="filter-card mb-4">
                <div class="filter-header">
                    <h5>{{ __('web.filter') }}</h5>
                </div>
                <div class="filter-body">
                    <form method="GET" class="row g-2">
                        <div class="col-lg-2 col-md-4">
                            <label class="form-label">Code</label>
                            <input type="text" name="search" class="form-control" placeholder="Search code" value="{{ request('search') }}">
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <label class="form-label">Min Discount %</label>
                            <input type="number" name="min_discount" class="form-control" placeholder="0" value="{{ request('min_discount') }}">
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <label class="form-label">Max Discount %</label>
                            <input type="number" name="max_discount" class="form-control" placeholder="100" value="{{ request('max_discount') }}">
                        </div>
                        <div class="col-12">
                            <div class="filter-buttons">
                                <button type="submit" class="btn-apply">Apply</button>
                                <a href="{{ route('discountCodes.list') }}" class="btn-clear">Clear</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Data Table for discount_codes -->
            <x-data-table :headers="$headers" :rows="$rows" :url="$url" />
        </div>

        <!-- Pagination links -->
        <div class="mt-4">
            {{ $data->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
</main>

<x-admin.footer />
