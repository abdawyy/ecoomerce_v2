@php
    use App\Support\AdminNav;
    $notify = $adminNotifications ?? ['pending_orders' => 0, 'unread_messages' => 0, 'new_users' => 0, 'new_guests' => 0];
    $notifyTotal = AdminNav::notificationTotal($notify);
    $feed = $adminActivityFeed ?? collect();
    $locale = app()->getLocale();
@endphp

<nav id="navbar" class="navbar py-3 admin-navbar" style="position: sticky; top: 0; z-index: 999;">
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between w-100 gap-3">
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="toggle-sidebar-btn admin-icon-btn border me-2" aria-label="Toggle sidebar">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <a class="navbar-brand mb-0" href="{{ route('admin.dashboard') }}">
                    <x-branding.logo style="width: 120px; height: 60px;" />
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

                <div class="dropdown">
                    <button class="admin-icon-btn border admin-notify-btn position-relative"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                        aria-label="{{ __('dashboard.notifications') }}">
                        <i class="bi bi-bell fs-5"></i>
                        @if ($notifyTotal > 0)
                            <span id="nav-notify-total"
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $notifyTotal > 99 ? '99+' : $notifyTotal }}
                            </span>
                        @else
                            <span id="nav-notify-total" class="d-none position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end admin-notify-menu shadow-sm p-0">
                        <li class="dropdown-header d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                            <span class="fw-semibold">{{ __('dashboard.notifications') }}</span>
                            <a href="{{ route('admin.dashboard') }}" class="small text-decoration-none">{{ __('dashboard.view_all') }}</a>
                        </li>
                        @forelse ($feed as $entry)
                            <li>
                                <a class="dropdown-item admin-notify-item py-2 px-3"
                                    href="{{ $entry['url'] }}">
                                    <div class="d-flex gap-2 align-items-start">
                                        <span class="admin-notify-icon bg-{{ $entry['variant'] }} bg-opacity-10 text-{{ $entry['variant'] }}">
                                            <i class="bi {{ $entry['icon'] }}"></i>
                                        </span>
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="fw-semibold small text-truncate">{{ $entry['title'] }}</div>
                                            <div class="text-muted small text-truncate">{{ $entry['subtitle'] }}</div>
                                            <div class="text-muted" style="font-size: 0.7rem;">{{ $entry['at']?->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <li class="dropdown-item-text text-muted small px-3 py-3">{{ __('dashboard.no_activity') }}</li>
                        @endforelse
                        <li class="border-top">
                            <div class="d-flex flex-wrap gap-2 p-2 small">
                                @if (($notify['pending_orders'] ?? 0) > 0)
                                    <a href="{{ route('order.list') }}?status=Pending" class="badge bg-warning text-dark text-decoration-none">
                                        {{ __('dashboard.pending_orders') }}: <span id="nav-pending-orders">{{ $notify['pending_orders'] }}</span>
                                    </a>
                                @else
                                    <span class="d-none"><span id="nav-pending-orders">0</span></span>
                                @endif
                                @if (($notify['unread_messages'] ?? 0) > 0)
                                    <a href="{{ route('admin.contact.list') }}" class="badge bg-danger text-decoration-none">
                                        {{ __('dashboard.unread_messages') }}: <span id="nav-unread-messages">{{ $notify['unread_messages'] }}</span>
                                    </a>
                                @else
                                    <span class="d-none"><span id="nav-unread-messages">0</span></span>
                                @endif
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
