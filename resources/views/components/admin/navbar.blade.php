@php
    use App\Support\AdminNav;
    $notify = $adminNotifications ?? ['pending_orders' => 0, 'unread_messages' => 0, 'new_users' => 0, 'new_guests' => 0];
    $notifyTotal = AdminNav::notificationTotal($notify);
    $locale = app()->getLocale();
@endphp

<nav id="navbar" class="navbar admin-navbar">
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between w-100 gap-3">
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="toggle-sidebar-btn admin-icon-btn border me-2" aria-label="Toggle sidebar">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <a class="navbar-brand mb-0 d-inline-flex align-items-center" href="{{ route('admin.dashboard') }}">
                    <x-branding.logo
                        compact
                        dark
                        :link="false"
                        style="width: auto; height: 44px; max-height: 44px; max-width: 160px; object-fit: contain;"
                    />
                </a>
            </div>

            <div class="d-flex align-items-center gap-2 gap-sm-3">
                <a href="{{ url('/') }}" target="_blank" rel="noopener" class="admin-view-store d-none d-md-inline-flex align-items-center gap-1">
                    <i class="bi bi-box-arrow-up-right"></i>
                    {{ __('admin.view_store') }}
                </a>

                <div class="admin-lang-switch d-none d-sm-flex align-items-center gap-1">
                    <a class="admin-lang-link {{ $locale === 'en' ? 'active' : '' }}"
                        href="{{ url('/lang/en') }}">EN</a>
                    <span class="text-muted">|</span>
                    <a class="admin-lang-link {{ $locale === 'ar' ? 'active' : '' }}"
                        href="{{ url('/lang/ar') }}">العربية</a>
                </div>

                <button type="button"
                    id="admin-theme-toggle"
                    class="admin-icon-btn border admin-theme-toggle"
                    data-label-dark="{{ __('admin.theme_dark') }}"
                    data-label-light="{{ __('admin.theme_light') }}"
                    aria-label="{{ __('admin.theme_dark') }}">
                    <i class="bi bi-moon-stars-fill" id="admin-theme-icon"></i>
                </button>

                <div class="dropdown admin-notify-dropdown">
                    <button class="admin-icon-btn border admin-notify-btn position-relative"
                        type="button"
                        data-bs-toggle="dropdown"
                        data-bs-display="static"
                        data-bs-auto-close="outside"
                        aria-expanded="false"
                        aria-label="{{ __('dashboard.notifications') }}">
                        <i class="bi bi-bell fs-5"></i>
                        <span id="nav-notify-total"
                            data-admin-notify-bell
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ $notifyTotal > 0 ? '' : 'd-none' }}">
                            {{ $notifyTotal > 99 ? '99+' : $notifyTotal }}
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end admin-notify-menu shadow-sm p-0">
                        <li class="dropdown-header d-flex justify-content-between align-items-center gap-2 px-3 py-2 border-bottom">
                            <span class="fw-semibold">{{ __('dashboard.notifications') }}</span>
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('admin.dashboard') }}" class="small text-decoration-none">{{ __('dashboard.view_all') }}</a>
                                <button type="button" class="btn-close admin-notify-close d-md-none" aria-label="Close"></button>
                            </div>
                        </li>
                        <li>
                            <ul id="admin-notify-feed" class="list-unstyled mb-0 admin-notify-feed">
                                <li class="dropdown-item-text text-muted small px-3 py-3">{{ __('dashboard.no_activity') }}</li>
                            </ul>
                        </li>
                        <li class="border-top">
                            <div class="d-flex flex-wrap gap-2 p-2 small">
                                <a href="{{ route('order.list') }}?status=Pending" class="badge bg-warning text-dark text-decoration-none">
                                    {{ __('dashboard.pending_orders') }}: <span id="nav-pending-orders">{{ $notify['pending_orders'] ?? 0 }}</span>
                                </a>
                                <a href="{{ route('admin.contact.list') }}" class="badge bg-danger text-decoration-none">
                                    {{ __('dashboard.unread_messages') }}: <span id="nav-unread-messages">{{ $notify['unread_messages'] ?? 0 }}</span>
                                </a>
                                <a href="{{ route('users.list') }}?new=1" class="badge bg-info text-decoration-none">
                                    {{ __('dashboard.new_users') }}: <span data-admin-notify="new_users">{{ $notify['new_users'] ?? 0 }}</span>
                                </a>
                                <a href="{{ route('admin.guest.list') }}?new=1" class="badge bg-info text-decoration-none">
                                    {{ __('dashboard.new_guests') }}: <span data-admin-notify="new_guests">{{ $notify['new_guests'] ?? 0 }}</span>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
