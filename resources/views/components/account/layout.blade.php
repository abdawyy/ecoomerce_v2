@props(['title' => null])

<x-web.layout :title="$title ?? __('account.title')">
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/account.css') }}">
    @endpush

    <section class="account-page py-4 pb-5">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-4">
                <div class="col-lg-3">
                    <x-account.sidebar />
                </div>
                <div class="col-lg-9">
                    @if ($title)
                        <div class="account-page-header mb-4">
                            <h1 class="h3 fw-bold mb-0">{{ $title }}</h1>
                        </div>
                    @endif
                    {{ $slot }}
                </div>
            </div>
        </div>
    </section>
</x-web.layout>
