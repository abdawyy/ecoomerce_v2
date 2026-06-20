<x-admin.header />
<x-admin.aside />
<x-admin.navbar />

<style>
    .filter-card {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 6px;
    }
    .filter-header {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 12px 15px;
    }
    .filter-header h5 {
        margin: 0;
        font-weight: 600;
        font-size: 0.95rem;
    }
    .filter-body {
        padding: 15px;
    }
    .form-control, .form-select {
        border: 1px solid #dee2e6;
        border-radius: 4px;
        font-size: 0.9rem;
        padding: 0.5rem 0.75rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #80bdff;
        box-shadow: none;
    }
    .filter-buttons {
        display: flex;
        gap: 10px;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #dee2e6;
    }
    .btn-apply, .btn-clear {
        flex: 1;
        padding: 0.5rem 1rem;
        font-weight: 500;
        font-size: 0.85rem;
        border-radius: 4px;
        border: none;
        cursor: pointer;
    }
    .btn-apply {
        background: #007bff;
        color: white;
    }
    .btn-apply:hover {
        background: #0056b3;
    }
    .btn-clear {
        background: #e9ecef;
        color: #333;
        border: 1px solid #dee2e6;
    }
    .btn-clear:hover {
        background: #dee2e6;
    }
</style>

<main id="main">
    <div class="container">
        <div class="row pt-4">
            @php $isRtl = app()->getLocale() === 'ar'; @endphp

            <div class="pagetitle">
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

<style>
    .btn-danger {
        display: none;
    }

    svg {
        width: 5px !important;
        height: 5px !important;
    }
</style>
