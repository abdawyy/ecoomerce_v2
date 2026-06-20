@php
    $isRtl = app()->getLocale() === 'ar';
    $navItems = [
        ['route' => 'account.dashboard', 'label' => 'account.dashboard', 'icon' => 'fa-gauge-high', 'active' => request()->routeIs('account.dashboard')],
        ['route' => 'account.orders', 'label' => 'account.orders', 'icon' => 'fa-box', 'active' => request()->routeIs('account.orders*')],
        ['route' => 'account.addresses', 'label' => 'account.addresses', 'icon' => 'fa-location-dot', 'active' => request()->routeIs('account.addresses*')],
        ['route' => 'account.profile', 'label' => 'account.profile', 'icon' => 'fa-user', 'active' => request()->routeIs('account.profile*')],
        ['route' => 'account.reviews', 'label' => 'account.reviews', 'icon' => 'fa-star', 'active' => request()->routeIs('account.reviews')],
    ];
@endphp

<aside class="account-sidebar">
    <div class="account-sidebar-user mb-4">
        <div class="account-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
        <div>
            <div class="fw-bold">{{ Auth::user()->name }}</div>
            <div class="small text-muted text-truncate">{{ Auth::user()->email }}</div>
        </div>
    </div>

    <nav class="account-nav">
        @foreach ($navItems as $item)
            <a href="{{ route($item['route']) }}"
                class="account-nav-link {{ $item['active'] ? 'active' : '' }}">
                <i class="fa-solid {{ $item['icon'] }}"></i>
                <span>{{ __($item['label']) }}</span>
            </a>
        @endforeach

        <form action="{{ route('logout') }}" method="POST" class="mt-3">
            @csrf
            <button type="submit" class="account-nav-link account-nav-logout w-100 border-0 bg-transparent">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>{{ __('account.logout') }}</span>
            </button>
        </form>

    </nav>
</aside>
