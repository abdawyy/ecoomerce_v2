<x-web.layout :seo="$seo ?? null">
@push('styles')
<style>
    .hero {
        min-height: 72vh;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: flex-end;
        border-radius: 0;
    }

    .hero-img,
    .category-img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        max-width: none;
        max-height: none;
        object-position: center;
    }

    .hero-img {
        object-fit: cover;
    }

    .category-img {
        object-fit: contain;
        z-index: 1;
    }

    .category-img-fill {
        position: absolute;
        inset: -16%;
        background-size: cover;
        background-position: center;
        filter: blur(22px) saturate(1.08);
        transform: scale(1.12);
        pointer-events: none;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        width: 100%;
        padding: 7vh 1.5rem 4.25rem;
        border-radius: 0;
        text-align: center;
    }

    .hero-content .hero-cta {
        border-radius: 4px;
    }

    .section-title h2 {
        letter-spacing: -1px;
        color: var(--brand-black);
    }

    .category-box {
        height: auto;
        aspect-ratio: 4 / 3;
        min-height: 280px;
        border-radius: var(--radius-lg);
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: flex-end;
        background: #111;
        transition: transform 0.5s ease;
        color: inherit;
    }

    .category-overlay {
        position: absolute;
        inset: 0;
        z-index: 2;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        align-items: flex-start;
        background: linear-gradient(to top, rgba(0,0,0,0.84) 0%, rgba(0,0,0,0.58) 46%, rgba(0,0,0,0.4) 100%);
        width: 100%;
        height: 100%;
        padding: 40px;
        transition: padding 0.4s ease;
    }

    .category-overlay h3 {
        color: #fff !important;
        font-size: clamp(1.55rem, 2.4vw, 2.15rem);
        line-height: 1.25;
        text-shadow: 0 2px 16px rgba(0, 0, 0, 0.45);
    }

    .category-box:hover .category-overlay {
        padding-bottom: 50px;
    }
</style>
@endpush

<section id="home" class="home-hero-section">
    <div class="hero">
        <img src="{{ $branding->heroImageUrl() }}" class="hero-img" alt="{{ $branding->siteName() }}">
        <div class="hero-content">
            <span class="hero-kicker text-uppercase fw-bold mb-2 d-block">
                {{ $branding->tagline() ?: __('web.hero_tagline') }}
            </span>
            <h1 class="display-2 fw-bold mb-3">{{ __('web.brand_collection_title') }}</h1>
            <p class="lead hero-lead mb-4 d-none d-md-block">{{ __('web.brand_collection_desc') }}</p>
            @php $departments = $branding->departmentMenus(); @endphp
            <div class="hero-actions">
                @forelse ($departments as $department)
                    <a href="{{ $department['url'] }}" class="btn btn-light hero-cta">{{ $department['shop_label'] }}</a>
                @empty
                    <a href="{{ route('product.List') }}" class="btn btn-light hero-cta">{{ __('web.discover') }}</a>
                @endforelse
            </div>
        </div>
    </div>
</section>

<section id="newCollection" class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4 mb-md-5">
            <div class="section-title">
                <h2 class="fw-bold display-5 mb-0">{{ __('web.new_collection') }}</h2>
                <p class="text-muted mt-2">{{ __('web.new_collection_desc') }}</p>
            </div>
            <a href="{{ route('product.List') }}" class="home-show-all fw-bold text-decoration-none d-none d-md-block">
                {{ __('web.show_all') }}
            </a>
        </div>
    </div>

    <div class="collection-carousel-wrap">
        <button type="button" class="custom-ctrl collection-ctrl collection-ctrl-prev" aria-label="Previous">
            <i class="bi bi-chevron-left" aria-hidden="true"></i>
        </button>
        <div id="newCollectionTrack" class="collection-track" tabindex="0">
            @foreach($products as $product)
                <div class="collection-slide">
                    <x-web.product-card :product="$product" />
                </div>
            @endforeach
        </div>
        <button type="button" class="custom-ctrl collection-ctrl collection-ctrl-next" aria-label="Next">
            <i class="bi bi-chevron-right" aria-hidden="true"></i>
        </button>
    </div>
    <p class="carousel-swipe-hint d-md-none"><i class="bi bi-arrow-left-right me-1"></i> Swipe to browse</p>
</section>

<section id="category" class="py-5 home-categories">
    <div class="container">
        <div class="row g-4 justify-content-center">
            @foreach ($departments as $department)
                <div class="col-12 col-md-6">
                    <a href="{{ $department['url'] }}" class="category-box text-decoration-none d-block">
                        <span class="category-img-fill" aria-hidden="true" style="background-image: url('{{ $department['image'] }}')"></span>
                        <img src="{{ $department['image'] }}" class="category-img" alt="{{ $department['shop_label'] }}">
                        <div class="category-overlay">
                            <h3 class="fw-bold text-white mb-3">{{ $department['shop_label'] }}</h3>
                            <span class="btn btn-light px-4 py-2 rounded-pill fw-bold">
                                {{ __('web.see_details') }}
                            </span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

</x-web.layout>
