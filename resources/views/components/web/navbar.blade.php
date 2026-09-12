<div class="promo-bar">
    {{ __('web.title') }} — {{ __('web.promo_tagline') }}
</div>

<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
            <i class="fa-solid fa-bars-staggered"></i>
        </button>

        <a class="navbar-brand d-inline-flex align-items-center py-0" href="{{ route('home') }}">
            <x-branding.logo :link="false" style="width: 104px; height: auto; max-height: 44px; object-fit: contain;" />
        </a>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link px-3" href="/">{{ __('web.home') }}</a></li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle px-3" href="#" role="button">
                        {{ __('web.category') }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('product.List') }}"><strong>{{ __('web.all_products') }}</strong></a></li>
                        <li><hr class="dropdown-divider"></li>
                        @foreach ($categories as $category)
                            <li><a class="dropdown-item" href="{{ route('product.List', ['id' => $category->id]) }}">{{ $category->name }}</a></li>
                        @endforeach
                    </ul>
                </li>
            </ul>
        </div>

        <div class="d-flex align-items-center gap-1 gap-md-2 storefront-toolbar">
            <button type="button" class="storefront-icon-btn" data-storefront-theme-toggle
                data-label-light="{{ __('account.theme_light') }}"
                data-label-dark="{{ __('account.theme_dark') }}"
                aria-label="{{ __('account.theme_dark') }}">
                <i class="bi bi-moon-stars-fill"></i>
            </button>

            <a href="#" class="storefront-icon-btn" data-bs-toggle="modal" data-bs-target="#searchModal" aria-label="{{ __('web.search') }}">
                <i class="fa-solid fa-magnifying-glass"></i>
            </a>

            <a href="{{ route('cart.index') }}" class="storefront-icon-btn position-relative" aria-label="{{ __('web.footer_cart') }}">
                <i class="fa-solid fa-bag-shopping"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge-cart cart-count">{{ $cartCount ?? 0 }}</span>
            </a>

            @auth
                <div class="dropdown">
                    <a href="#" class="storefront-icon-btn" data-bs-toggle="dropdown" aria-expanded="false" aria-label="{{ __('account.account_menu') }}">
                        <i class="fa-solid fa-user"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li class="dropdown-header small">{{ Auth::user()->name }}</li>
                        <li><a class="dropdown-item" href="{{ route('account.dashboard') }}">{{ __('account.dashboard') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('account.orders') }}">{{ __('account.orders') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('account.profile') }}">{{ __('account.profile') }}</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">{{ __('account.logout') }}</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('login') }}" class="storefront-icon-btn d-none d-md-inline-flex" title="{{ __('account.login') }}">
                    <i class="fa-solid fa-user"></i>
                </a>
            @endauth

            <div class="d-none d-md-flex gap-2 ms-2 small fw-bold">
                <a href="{{ url('/lang/en') }}" class="text-decoration-none {{ app()->getLocale() == 'en' ? 'text-dark' : 'text-muted' }}">EN</a>
                <a href="{{ url('/lang/ar') }}" class="text-decoration-none {{ app()->getLocale() == 'ar' ? 'text-dark' : 'text-muted' }}">AR</a>
            </div>
        </div>
    </div>
</nav>

<div class="offcanvas {{ app()->getLocale() == 'ar' ? 'offcanvas-end' : 'offcanvas-start' }}" tabindex="-1" id="mobileMenu">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-bold">{{ __('web.menu') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav flex-column gap-3">
            <li class="nav-item border-bottom pb-2"><a class="nav-link p-0 fs-5" href="/">{{ __('web.home') }}</a></li>
            <li class="nav-item">
                <p class="text-muted small mb-2 fw-bold text-uppercase">{{ __('web.category') }}</p>
                <div class="list-group list-group-flush ps-2">
                    <a href="{{ route('product.List') }}" class="list-group-item list-group-item-action border-0">{{ __('web.all_products') }}</a>
                    @foreach ($categories as $category)
                        <a href="{{ route('product.List', ['id' => $category->id]) }}" class="list-group-item list-group-item-action border-0">{{ $category->name }}</a>
                    @endforeach
                </div>
            </li>
        </ul>

        <div class="mt-4 pt-3 border-top">
            @auth
                <p class="text-muted small mb-2 fw-bold text-uppercase">{{ __('account.account_menu') }}</p>
                <div class="list-group list-group-flush ps-2 mb-3">
                    <a href="{{ route('account.dashboard') }}" class="list-group-item list-group-item-action border-0">{{ __('account.dashboard') }}</a>
                    <a href="{{ route('account.orders') }}" class="list-group-item list-group-item-action border-0">{{ __('account.orders') }}</a>
                    <a href="{{ route('account.profile') }}" class="list-group-item list-group-item-action border-0">{{ __('account.profile') }}</a>
                </div>
            @else
                <div class="d-flex gap-3 mb-3">
                    <a href="{{ route('login') }}" class="btn btn-dark btn-sm flex-fill">{{ __('account.login') }}</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-outline-dark btn-sm flex-fill">{{ __('account.register') }}</a>
                    @endif
                </div>
            @endauth
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ url('/lang/en') }}" class="text-decoration-none fw-bold {{ app()->getLocale() == 'en' ? 'text-dark' : 'text-muted' }}">EN</a>
                <span class="text-muted">|</span>
                <a href="{{ url('/lang/ar') }}" class="text-decoration-none fw-bold {{ app()->getLocale() == 'ar' ? 'text-dark' : 'text-muted' }}">AR</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-search-overlay" id="searchModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0"><button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button></div>
            <div class="modal-body d-flex align-items-center">
                <form method="POST" action="{{ route('product.List') }}" class="container text-center">
                    @csrf
                    <input type="text" name="search" class="search-input-full" placeholder="{{ __('web.search_placeholder') }}" required autofocus>
                    <button type="submit" class="btn btn-outline-light mt-5 px-5 py-3 rounded-pill fw-bold text-uppercase">{{ __('web.search') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
