@php
    use App\Support\AdminNav;
    $notify = $adminNotifications ?? ['pending_orders' => 0, 'unread_messages' => 0, 'new_users' => 0, 'new_guests' => 0];
    $sections = AdminNav::sections($notify);
    $chevron = app()->getLocale() === 'ar' ? 'left' : 'right';
    $chevronAuto = app()->getLocale() === 'ar' ? 'me-auto' : 'ms-auto';
@endphp

<aside id="sidebar"
    class="sidebar pt-4 d-flex flex-column"
    dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

    <ul class="sidebar-nav flex-grow-1" id="sidebar-nav">
        @foreach ($sections as $section)
            @if (! empty($section['heading']))
                <li class="nav-heading">{{ __($section['heading']) }}</li>
            @endif

            @foreach ($section['items'] as $item)
                @if ($item['type'] === 'link')
                    <li class="nav-item">
                        <a class="nav-link {{ $item['active'] ? 'active' : 'collapsed' }}"
                            href="{{ $item['url'] }}">
                            <i class="bi {{ $item['icon'] }} mx-2"></i>
                            <span>{{ __($item['label']) }}</span>
                            <x-admin.notification-badge :count="$item['badge']" :variant="$item['badge_variant']" />
                        </a>
                    </li>
                @elseif ($item['type'] === 'collapse')
                    <li class="nav-item">
                        <a class="nav-link {{ $item['open'] ? '' : 'collapsed' }}"
                            data-bs-target="#{{ $item['id'] }}"
                            data-bs-toggle="collapse"
                            href="#">
                            <i class="bi {{ $item['icon'] }} mx-2"></i>
                            <span>{{ __($item['label']) }}</span>
                            <i class="bi bi-chevron-{{ $chevron }} {{ $chevronAuto }}"></i>
                        </a>
                        <ul id="{{ $item['id'] }}"
                            class="nav-content collapse {{ $item['open'] ? 'show' : '' }}"
                            data-bs-parent="#sidebar-nav">
                            @foreach ($item['children'] as $child)
                                <li>
                                    <a class="{{ $child['active'] ? 'active' : '' }}"
                                        href="{{ route($child['route']) }}">
                                        {{ __($child['label']) }}
                                        <x-admin.notification-badge :count="$child['badge']" :variant="$child['badge_variant']" />
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
            @endforeach
        @endforeach
    </ul>

    <div class="sidebar-footer pb-2">
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn admin-sidebar-logout w-100">
                <i class="bi bi-box-arrow-right me-2"></i>{{ __('admin_sidebar.logout') }}
            </button>
        </form>
    </div>
</aside>
