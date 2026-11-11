<x-admin.header />
<x-admin.aside />
<x-admin.navbar />

@php $isRtl = app()->getLocale() === 'ar'; @endphp

<main id="main">
    <div class="container">
        <div class="row pt-4">
            <div class="pagetitle mb-3">
                <h1 class="mb-1">{{ __('reviews.title') }}</h1>
                <x-admin.return-button :href="isset($productId) ? route('products.edit', $productId) : \App\Support\AdminListReturn::href('products', 'products.list')" />
            </div>

            <!-- Data Table -->
            <x-data-table :headers="$headers" :rows="$rows" :url="$url" />

            <!-- Pagination -->
            <div class="mt-4">
                {{ $data->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</main>
<style>
    .btn-primary{
        display: none;
    }
    </style>

<x-admin.footer />
