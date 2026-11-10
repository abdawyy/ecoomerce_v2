<div class="promo-bar">
    {{ __('web.title') }} — {{ __('web.promo_tagline') }}
</div>

<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container navbar-inner">

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-label="{{ __('web.menu') }}">
            <i class="fa-solid fa-bars-staggered"></i>
        </button>

        <a class="navbar-brand d-inline-flex align-items-center py-0" href="{{ route('home') }}">
            <x-branding.logo :link="false" compact style="width: auto; height: 48px; max-height: 48px; max-width: 190px; object-fit: contain;" />
        </a>

        @php $departments = $branding->departmentMenus(); @endphp

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav">
                @foreach ($departments as $department)
                    <li class="nav-item nav-mega">
                        <a class="nav-link" href="{{ $department['url'] }}">{{ $department['label'] }}</a>
                        <div class="mega-panel">
                            <a class="mega-feature" href="{{ $department['url'] }}">
                                <img src="{{ $department['image'] }}" alt="">
                                <span>{{ $department['shop_label'] }}</span>
                            </a>
                            @foreach ($department['columns'] as $column)
                                <div class="mega-column">
                                    <p class="mega-heading">{{ $column['title'] }}</p>
                                    @foreach ($column['links'] as $link)
                                        <a class="mega-link" href="{{ $link['url'] }}">{{ $link['name'] }}</a>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="d-flex align-items-center storefront-toolbar">
            <button type="button" class="storefront-icon-btn" data-storefront-theme-toggle
                data-label-light="{{ __('account.theme_light') }}"
                data-label-dark="{{ __('account.theme_dark') }}"
                aria-label="{{ __('account.theme_dark') }}">
                <i class="bi bi-moon-stars-fill"></i>
            </button>

            <a href="#" class="storefront-icon-btn" data-bs-toggle="modal" data-bs-target="#searchModal" aria-label="{{ __('web.search') }}">
                <i class="fa-solid fa-magnifying-glass"></i>
            </a>

            <a href="{{ route('cart.index') }}" class="storefront-icon-btn storefront-cart-btn" aria-label="{{ __('web.footer_cart') }}">
                <svg class="storefront-cart-svg" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l.84 4.479 9.144-.459L13.89 4z"/>
                    <path d="M5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                </svg>
                <span class="badge-cart cart-count">{{ $cartCount ?? 0 }}</span>
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
        <div class="d-flex flex-column gap-3">
            @foreach ($departments as $department)
                <details class="mobile-dept">
                    <summary>{{ $department['label'] }}</summary>
                    <a class="mega-link" href="{{ $department['url'] }}">{{ $department['shop_label'] }}</a>
                    @foreach ($department['columns'] as $column)
                        <p class="mobile-dept-heading">{{ $column['title'] }}</p>
                        @foreach ($column['links'] as $link)
                            <a class="mega-link" href="{{ $link['url'] }}">{{ $link['name'] }}</a>
                        @endforeach
                    @endforeach
                </details>
            @endforeach
        </div>

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

<script>
    document.querySelectorAll('.nav-mega').forEach(function (item) {
        var timer;

        item.addEventListener('mouseenter', function () {
            clearTimeout(timer);
            document.querySelectorAll('.nav-mega.is-open').forEach(function (open) {
                if (open !== item) {
                    open.classList.remove('is-open');
                }
            });
            item.classList.add('is-open');
        });

        item.addEventListener('mouseleave', function () {
            clearTimeout(timer);
            timer = setTimeout(function () {
                item.classList.remove('is-open');
            }, 280);
        });
    });
</script>
