<x-web.layout :seo="$seo ?? null" :title="__('guides.title')">

<main class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold">{{ __('guides.title') }}</h1>
            <p class="text-muted">{{ __('guides.meta_description') }}</p>
        </div>
    </div>
    <div class="row g-4">
        @forelse ($guides as $guide)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $guide->title() }}</h5>
                        @if ($guide->description())
                            <p class="card-text text-muted small flex-grow-1">{{ $guide->description() }}</p>
                        @endif
                        <a href="{{ route('guides.download', $guide->slug) }}" class="btn btn-dark mt-auto align-self-start">
                            <i class="fa-solid fa-download me-1"></i> {{ __('guides.download') }}
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-5">{{ __('guides.empty') }}</div>
        @endforelse
    </div>
</main>

</x-web.layout>
