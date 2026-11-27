<x-admin.header />
<x-admin.aside />
<x-admin.navbar />

<main id="main">
    <div class="container">
        <div class="row pt-4">
            <div class="pagetitle mb-3">
                <h1 class="mb-0">{{ __('orders.title') }}</h1>
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
                                @foreach (\App\Support\OrderStatus::all() as $option)
                                    <option value="{{ $option }}" {{ request('status') === $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
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

<x-admin.footer />
