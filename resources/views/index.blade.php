<x-web.layout :seo="$seo ?? null">
@push('styles')
<style>
    .hero {
        min-height: 600px;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        border-radius: var(--radius-lg);
    }

    .hero-img,
    .category-img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        padding: 60px;
        border-radius: var(--radius-lg);
    }

    .section-title h2 {
        letter-spacing: -1px;
        color: var(--brand-black);
    }

    .category-box {
        height: 500px;
        border-radius: var(--radius-lg);
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: flex-end;
        background: var(--brand-soft);
        transition: transform 0.5s ease;
    }

    .category-overlay {
        position: relative;
        z-index: 2;
        background: linear-gradient(to top, rgba(0,0,0,0.88) 8%, rgba(0,0,0,0.35) 48%, transparent 100%);
        width: 100%;
        padding: 40px;
        transition: padding 0.4s ease;
    }

    .category-overlay h3 {
        color: #fff !important;
        text-shadow: 0 2px 16px rgba(0, 0, 0, 0.45);
    }

    .category-box:hover .category-overlay {
        padding-bottom: 50px;
    }

    .custom-ctrl { opacity: 1; width: 40px; }
    .custom-ctrl-icon {
        background-color: var(--brand-black) !important;
        border-radius: 50%;
        width: 45px;
        height: 45px;
        background-size: 40%;
    }
</style>
@endpush

<section id="home" class="pt-4 pb-5">
    <div class="container">
        <div class="hero">
            <img src="{{ $branding->heroImageUrl() }}" class="hero-img" alt="{{ $branding->siteName() }}">
            <div class="hero-content text-white">
                <span class="text-uppercase fw-bold mb-2 d-block" style="letter-spacing: 3px; font-size: 0.8rem;">
                    {{ $branding->tagline() ?: __('web.hero_tagline') }}
                </span>
                <h1 class="display-2 fw-bold mb-3">{{ __('web.brand_collection_title') }}</h1>
                <p class="lead mb-4 opacity-75 w-75 d-none d-md-block">{{ __('web.brand_collection_desc') }}</p>
                <a href="{{ route('product.List') }}" class="btn btn-light btn-lg px-5 py-3 rounded-pill fw-bold shadow-lg">
                    {{ __('web.discover') }}
                </a>
            </div>
        </div>
    </div>
</section>

<section id="newCollection" class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div class="section-title">
                <h2 class="fw-bold display-5 mb-0">{{ __('web.new_collection') }}</h2>
                <p class="text-muted mt-2">{{ __('web.new_collection_desc') }}</p>
            </div>
            <a href="{{ route('product.List') }}" class="home-show-all fw-bold text-decoration-none d-none d-md-block">
                {{ __('web.show_all') }}
            </a>
        </div>

        <div id="newCollectionCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($products->chunk(4) as $chunkIndex => $chunk)
                    <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                        <div class="row g-3 g-md-4">
                            @foreach($chunk as $product)
                                <div class="col-6 col-lg-3">
                                    <x-web.product-card :product="$product" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <button class="carousel-control-prev custom-ctrl" type="button" data-bs-target="#newCollectionCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon custom-ctrl-icon"></span>
            </button>
            <button class="carousel-control-next custom-ctrl" type="button" data-bs-target="#newCollectionCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon custom-ctrl-icon"></span>
            </button>
        </div>
        <p class="carousel-swipe-hint d-md-none"><i class="bi bi-arrow-left-right me-1"></i> Swipe to browse</p>
    </div>
</section>

<section id="category" class="py-5 home-categories">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <div class="col-12 col-md-6">
                <div class="category-box">
                    <img src="{{ $branding->categoryImage1Url() }}" class="category-img" alt="{{ __('web.top') }}">
                    <div class="category-overlay">
                        <h3 class="fw-bold text-white display-4 mb-3">{{ __('web.top') }}</h3>
                        <a href="{{ url('product/list/category/1') }}" class="btn btn-light px-4 py-2 rounded-pill fw-bold">
                            {{ __('web.see_details') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="category-box">
                    <img src="{{ $branding->categoryImage2Url() }}" class="category-img" alt="{{ __('web.Long Sleeve') }}">
                    <div class="category-overlay">
                        <h3 class="fw-bold text-white display-4 mb-3">{{ __('web.Long Sleeve') }}</h3>
                        <a href="{{ url('product/list/category/5') }}" class="btn btn-light px-4 py-2 rounded-pill fw-bold">
                            {{ __('web.see_details') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</x-web.layout>
