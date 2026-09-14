<x-admin.header />
<x-admin.aside />
<x-admin.navbar />
@php $isRtl = app()->getLocale() === 'ar'; @endphp

<main id="main">
    <div class="container">
        <div class="row pt-4">
            <x-admin.page-header
                :title="__('guest.guest_title')"
                :breadcrumb="__('guest.breadcrumb_main')"
                :activeBreadcrumb="__('guest.breadcrumb_guest')" />

            <div class="admin-card card mb-4">
                <div class="card-header bg-white fw-bold">{{ __('web.filter') }}</div>
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-lg-4 col-md-6">
                            <label class="form-label">{{ __('guest.filter_search') }}</label>
                            <input type="text" name="search" class="form-control"
                                placeholder="{{ __('guest.filter_search_placeholder') }}"
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-lg-4 col-md-6 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="has_orders" value="1" id="guest_has_orders"
                                    @checked(request()->boolean('has_orders'))>
                                <label class="form-check-label" for="guest_has_orders">{{ __('guest.filter_has_orders') }}</label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="new" value="1" id="guest_new"
                                    @checked(request()->boolean('new'))>
                                <label class="form-check-label" for="guest_new">{{ __('guest.filter_new') }}</label>
                            </div>
                        </div>
                        <div class="col-12 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">{{ __('guest.apply_filters') }}</button>
                            <a href="{{ route('admin.guest.list') }}" class="btn btn-outline-secondary">{{ __('guest.clear_filters') }}</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="admin-card card">
                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>{{ __('guest.name') }}</th>
                                <th>{{ __('guest.email') }}</th>
                                <th>{{ __('guest.kpi_orders') }}</th>
                                <th>{{ __('guest.kpi_last_order') }}</th>
                                <th>{{ __('guest.joined') }}</th>
                                <th>{{ __('guest.type') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($guests as $guest)
                                <tr>
                                    <td>{{ $guest->id }}</td>
                                    <td>
                                        {{ $guest->name }}
                                        @if ($guest->created_at && $guest->created_at->gte(now()->subDays(7)))
                                            <span class="badge bg-info text-dark ms-1">{{ __('guest.badge_new') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $guest->email }}</td>
                                    <td>{{ $guest->orders_count }}</td>
                                    <td>
                                        @if ($guest->last_order_at)
                                            {{ \Carbon\Carbon::parse($guest->last_order_at)->format('M d, Y') }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>{{ $guest->created_at->format('M d, Y') }}</td>
                                    <td><span class="badge bg-secondary">{{ __('guest.badge_guest') }}</span></td>
                                    <td data-label="{{ __('table.headers.action') }}">
                                        @php $actionsAlign = $isRtl ? 'justify-content-start' : 'justify-content-end'; @endphp
                                        <div class="table-actions {{ $actionsAlign }}">
                                            <x-admin.view-link :href="route('admin.guest.show', $guest->id)" />
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">{{ __('table.no_results') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $guests->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</main>

<x-admin.footer />
