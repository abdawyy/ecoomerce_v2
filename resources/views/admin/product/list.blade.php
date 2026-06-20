<x-admin.header />
<x-admin.aside />

<x-admin.navbar />

@php $isRtl = app()->getLocale() === 'ar'; @endphp

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
            <div class="pagetitle">
                <h1>{{ __('products.title') }}</h1>
                <nav>
                    <ol class="breadcrumb d-flex {{ $isRtl ? 'text-end' : 'text-start' }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                        <li class="breadcrumb-item"><a href="#">{{ __('products.breadcrumb_main') }}</a></li>
                        <li class="mx-2">-</li>
                        <li class="breadcrumb-item active">{{ __('products.breadcrumb_list') }}</li>
                    </ol>
                </nav>
            </div>

            <!-- Filter Card -->
            <div class="filter-card mb-4">
                <div class="filter-header">
                    <h5>{{ __('web.filter') }} Products</h5>
                </div>
                <div class="filter-body">
                    <form method="GET" action="{{ route('products.list') }}" class="row g-2">
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <label class="form-label" style="font-size: 0.8rem; font-weight: 600;">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Product..." value="{{ request('search') }}">
                        </div>

                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <label class="form-label" style="font-size: 0.8rem; font-weight: 600;">Category</label>
                            <select name="category_id" class="form-select">
                                <option value="">All</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <label class="form-label" style="font-size: 0.8rem; font-weight: 600;">Type</label>
                            <select name="type_id" class="form-select">
                                <option value="">All</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" {{ request('type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <label class="form-label" style="font-size: 0.8rem; font-weight: 600;">Color</label>
                            <select name="color" class="form-select">
                                <option value="">All</option>
                                @foreach($colors as $c)
                                    <option value="{{ $c }}" {{ request('color') == $c ? 'selected' : '' }}>{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-1 col-md-4 col-sm-6">
                            <label class="form-label" style="font-size: 0.8rem; font-weight: 600;">Min</label>
                            <input type="number" name="min_price" class="form-control" placeholder="0" value="{{ request('min_price') }}" min="0">
                        </div>

                        <div class="col-lg-1 col-md-4 col-sm-6">
                            <label class="form-label" style="font-size: 0.8rem; font-weight: 600;">Max</label>
                            <input type="number" name="max_price" class="form-control" placeholder="999" value="{{ request('max_price') }}" min="0">
                        </div>

                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <label class="form-label" style="font-size: 0.8rem; font-weight: 600;">Stock</label>
                            <select name="stock_status" class="form-select">
                                <option value="">All</option>
                                <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out Stock</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <div class="filter-buttons">
                                <button type="submit" class="btn btn-apply">Apply</button>
                                <a href="{{ route('products.list') }}" class="btn btn-clear">Clear</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <x-data-table :headers="$headers" :rows="$rows" :url="$url"/>

            <!-- Pagination links -->
            <div class="mt-4">
{{ $data->links('vendor.pagination.bootstrap-5') }}
            </div>

            <style>
                .btn-danger {
                    display: none;
                }

                svg {
                    width: 5px !important;
                    height: 5px !important;
                }
            </style>
        </div>
    </div>
</main>

<x-admin.footer />
