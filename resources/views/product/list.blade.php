@php
    app(\App\Services\SeoService::class)->setContext([
        'title' => $listTitle.' | '.$branding->siteName(),
    ]);
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

        @include('product.partials.results')
    </div>
</section>

@push('scripts')
<script src="{{ asset('assets/js/plp-filters.js') }}?v={{ filemtime(public_path('assets/js/plp-filters.js')) }}"></script>
@endpush

</x-web.layout>
