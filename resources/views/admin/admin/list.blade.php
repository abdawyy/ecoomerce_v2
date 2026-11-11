<x-admin.header />
<x-admin.aside />
<x-admin.navbar />
<style>
    .pagination .page-link { border-radius: 10px; }
</style>

<main id="main">
    <div class="container">
        <div class="row pt-4">
            <div class="pagetitle d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <h1 class="mb-0">{{ __('admin_list.title') }}</h1>
                <x-admin.add-button :href="\App\Support\AdminListReturn::addUrl('admin.create', 'admins')" :label="__('admin_sidebar.add_admin')" />
            </div>

            <!-- Filter -->
            <div class="filter-card mb-4">
                <div class="filter-header">
                    <h5>{{ __('web.filter') }}</h5>
                </div>
                <div class="filter-body">
                    <form method="GET" class="row g-2">
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label">Name/Email</label>
                            <input type="text" name="search" class="form-control" placeholder="Search by name or email" value="{{ request('search') }}">
                        </div>
                        <div class="col-12">
                            <div class="filter-buttons">
                                <button type="submit" class="btn-apply">Apply</button>
                                <a href="{{ route('admin.list') }}" class="btn-clear">Clear</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <x-data-table :headers="$headers" :rows="$rows" :url="$url" />
                    </div>
                </div>
            </div>

            <!-- Pagination links -->
            <div class="mt-4">
                {{ $admins->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</main>
<style>
    .pagination .page-link {
        border-radius: 10px;
    }
</style>

<x-admin.footer />
