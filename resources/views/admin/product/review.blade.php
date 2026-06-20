<x-admin.header />
<x-admin.aside />
<x-admin.navbar />

@php $isRtl = app()->getLocale() === 'ar'; @endphp

<main id="main">
    <div class="container">
        <div class="row pt-4">
            <div class="pagetitle">
                <h1>{{ __('reviews.title') }}</h1>
                <nav>
                    <ol class="breadcrumb d-flex {{ $isRtl ? 'text-end' : 'text-start' }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                        <li class="breadcrumb-item"><a href="#">{{ __('reviews.breadcrumb_main') }}</a></li>
                        <li class="mx-2">-</li>
                        <li class="breadcrumb-item active">{{ __('reviews.breadcrumb_list') }}</li>
                    </ol>
                </nav>
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
