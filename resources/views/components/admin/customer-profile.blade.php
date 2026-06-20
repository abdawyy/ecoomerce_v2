@props(['profile'])

@php
    $isRtl = app()->getLocale() === 'ar';
@endphp

<div class="admin-customer-profile">
    <x-admin.page-header
        :title="$profile['show_title']"
        :breadcrumb="__('users.breadcrumb_main')"
        :activeBreadcrumb="$profile['name']">
        <x-slot:actions>
            <a href="{{ $profile['list_route'] }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> {{ __('users.back_to_list') }}
            </a>
            @if ($profile['last_order_id'])
                <x-admin.view-link :href="route('order.show', $profile['last_order_id'])" :label="__('users.open_latest_order')" />
            @endif
            @if ($profile['toggle_url'])
                <form method="POST" action="{{ $profile['toggle_url'] }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm {{ $profile['is_active'] ? 'btn-success' : 'btn-warning' }}">
                        {{ $profile['is_active'] ? __('users.status_active') : __('users.status_inactive') }}
                    </button>
                </form>
            @endif
        </x-slot:actions>
    </x-admin.page-header>

    <div class="admin-card card mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                <h2 class="h4 mb-0">{{ $profile['name'] }}</h2>
                <span class="badge {{ $profile['type'] === 'registered' ? 'bg-primary' : 'bg-secondary' }}">
                    {{ $profile['type'] === 'registered' ? __('users.badge_registered') : __('users.badge_guest') }}
                </span>
                @if ($profile['is_new'])
                    <span class="badge bg-info text-dark">{{ __('users.badge_new') }}</span>
                @endif
                @if ($profile['type'] === 'registered')
                    <span class="badge {{ $profile['is_active'] ? 'bg-success' : 'bg-warning text-dark' }}">
                        {{ $profile['is_active'] ? __('users.status_active') : __('users.status_inactive') }}
                    </span>
                @endif
            </div>
            <p class="mb-1"><i class="bi bi-envelope me-1"></i> {{ $profile['email'] }}</p>
            @if ($profile['phone'])
                <p class="mb-1"><i class="bi bi-telephone me-1"></i> {{ $profile['phone'] }}</p>
            @endif
            @if ($profile['joined_at'])
                <p class="text-muted small mb-0">{{ __('users.joined') }}: {{ $profile['joined_at']->format('M d, Y') }}</p>
            @endif
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="admin-card card h-100">
                <div class="card-body">
                    <div class="text-muted small">{{ __('users.kpi_orders') }}</div>
                    <div class="fs-4 fw-bold">{{ $profile['orders_count'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="admin-card card h-100">
                <div class="card-body">
                    <div class="text-muted small">{{ __('users.kpi_spent') }}</div>
                    <div class="fs-4 fw-bold">{{ number_format($profile['total_spent'], 2) }} LE</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="admin-card card h-100">
                <div class="card-body">
                    <div class="text-muted small">{{ __('users.kpi_last_order') }}</div>
                    <div class="fs-6 fw-bold">
                        {{ $profile['last_order_at'] ? $profile['last_order_at']->format('M d, Y') : __('users.not_available') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="admin-card card h-100">
                <div class="card-body">
                    <div class="text-muted small">{{ __('users.kpi_city') }}</div>
                    <div class="fs-6 fw-bold">{{ $profile['city'] ?? __('users.not_available') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="admin-card card">
                <div class="card-header bg-white fw-bold">{{ __('users.orders') }}</div>
                <div class="card-body p-0">
                    @if ($profile['orders']->isEmpty())
                        <p class="p-4 mb-0 text-muted">{{ __('users.no_orders') }}</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-modern align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('users.order_date') }}</th>
                                        <th>{{ __('users.total') }}</th>
                                        <th>{{ __('users.status') }}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($profile['orders'] as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                                            <td>{{ number_format($order->total_amount ?? 0, 2) }} LE</td>
                                            <td>
                                                @php
                                                    $statusClass = match (strtolower($order->status ?? 'pending')) {
                                                        'completed' => 'success',
                                                        'processing' => 'info',
                                                        'cancelled' => 'secondary',
                                                        default => 'warning text-dark',
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $statusClass }}">{{ $order->status ?? __('users.pending') }}</span>
                                            </td>
                                            <td data-label="{{ __('table.headers.action') }}">
                                                @php $actionsAlign = $isRtl ? 'justify-content-start' : 'justify-content-end'; @endphp
                                                <div class="table-actions {{ $actionsAlign }}">
                                                    <x-admin.view-link :href="route('order.show', $order->id)" />
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="admin-card card">
                <div class="card-header bg-white fw-bold">{{ __('users.addresses') }}</div>
                <div class="card-body">
                    @if ($profile['addresses']->isEmpty())
                        <p class="text-muted mb-0">{{ __('users.not_available') }}</p>
                    @else
                        @foreach ($profile['addresses'] as $address)
                            <div class="border rounded p-3 mb-3 {{ $loop->last ? 'mb-0' : '' }}">
                                @if ($address->is_default ?? false)
                                    <span class="badge bg-dark mb-2">{{ __('users.default_address') }}</span>
                                @endif
                                <p class="mb-1 fw-semibold">{{ $address->address_line1 }}</p>
                                @if ($address->address_line2)
                                    <p class="mb-1 small text-muted">{{ $address->address_line2 }}</p>
                                @endif
                                <p class="mb-1 small">{{ $address->city }} {{ $address->postal_code }}</p>
                                <p class="mb-0 small"><i class="bi bi-telephone me-1"></i>{{ $address->phone_number ?? __('users.not_available') }}</p>
                            </div>
                        @endforeach
                    @endif

                    @if ($profile['email'])
                        <a href="mailto:{{ $profile['email'] }}" class="btn btn-outline-dark btn-sm w-100 mt-3">
                            <i class="bi bi-envelope me-1"></i> {{ __('users.email_customer') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
