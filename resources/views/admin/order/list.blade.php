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
            @php
                $isRtl = app()->getLocale() === 'ar';
            @endphp

            <div class="pagetitle">
                <h1>{{ __('orders.title') }}</h1>
                <nav>
                    <ol class="breadcrumb d-flex {{ $isRtl ? 'text-end' : 'text-start' }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                        <li class="breadcrumb-item">
                            <a href="#">{{ __('orders.breadcrumb_main') }}</a>
                        </li>
                        <li class="mx-2">-</li>
                        <li class="breadcrumb-item active">
                            {{ __('orders.breadcrumb_active') }}
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
                            <label class="form-label">Order ID</label>
                            <input type="number" name="id" class="form-control" placeholder="#123" value="{{ request('id') }}">
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Order/Email" value="{{ request('search') }}">
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Processing" {{ request('status') === 'Processing' ? 'selected' : '' }}>Processing</option>
                                <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="filter-buttons">
                                <button type="submit" class="btn-apply">Apply</button>
                                <a href="{{ route('order.list') }}" class="btn-clear">Clear</a>
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
        </div>
    </div>
</main>

<style>
    .btn-danger {
        display: none;
    }

    svg {
        width: 5px !important;
        height: 5px !important;
    }
</style>

<x-admin.footer />
