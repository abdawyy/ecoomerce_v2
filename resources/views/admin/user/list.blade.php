<x-admin.header />
<x-admin.aside />
<x-admin.navbar />
@php $isRtl = app()->getLocale() === 'ar'; @endphp

<main id="main">
    <div class="container">
        <div class="row pt-4">
            <x-admin.page-header
                :title="__('users.users_title')"
                :breadcrumb="__('users.breadcrumb_main')"
                :activeBreadcrumb="__('users.breadcrumb_users')" />

            <div class="admin-card card mb-4">
                <div class="card-header bg-white fw-bold">{{ __('web.filter') }}</div>
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-lg-4 col-md-6">
                            <label class="form-label">{{ __('users.filter_search') }}</label>
                            <input type="text" name="search" class="form-control"
                                placeholder="{{ __('users.filter_search_placeholder') }}"
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <label class="form-label">{{ __('users.status') }}</label>
                            <select name="status" class="form-select">
                                <option value="">{{ __('users.filter_all') }}</option>
                                <option value="active" @selected(request('status') === 'active')>{{ __('users.status_active') }}</option>
                                <option value="inactive" @selected(request('status') === 'inactive')>{{ __('users.status_inactive') }}</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-4 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="has_orders" value="1" id="has_orders"
                                    @checked(request()->boolean('has_orders'))>
                                <label class="form-check-label" for="has_orders">{{ __('users.filter_has_orders') }}</label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="new" value="1" id="new_users"
                                    @checked(request()->boolean('new'))>
                                <label class="form-check-label" for="new_users">{{ __('users.filter_new') }}</label>
                            </div>
                        </div>
                        <div class="col-12 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">{{ __('users.apply_filters') }}</button>
                            <a href="{{ route('users.list') }}" class="btn btn-outline-secondary">{{ __('users.clear_filters') }}</a>
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
                                <th>{{ __('users.name') }}</th>
                                <th>{{ __('users.email') }}</th>
                                <th>{{ __('users.kpi_orders') }}</th>
                                <th>{{ __('users.kpi_last_order') }}</th>
                                <th>{{ __('users.joined') }}</th>
                                <th>{{ __('users.status') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        {{ $user->name }}
                                        @if ($user->created_at && $user->created_at->gte(now()->subDays(7)))
                                            <span class="badge bg-info text-dark ms-1">{{ __('users.badge_new') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->order_count }}</td>
                                    <td>
                                        @if ($user->last_order_at)
                                            {{ \Carbon\Carbon::parse($user->last_order_at)->format('M d, Y') }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-warning text-dark' }}">
                                            {{ $user->is_active ? __('users.status_active') : __('users.status_inactive') }}
                                        </span>
                                    </td>
                                    <td data-label="{{ __('table.headers.action') }}">
                                        @php $actionsAlign = $isRtl ? 'justify-content-start' : 'justify-content-end'; @endphp
                                        <div class="table-actions {{ $actionsAlign }} table-actions--multi">
                                            <x-admin.view-link :href="route('user.show', $user->id)" />
                                            <form method="POST" action="{{ route('admin.users.toggleStatus', $user->id) }}" class="table-actions-form">
                                                @csrf
                                                <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-success' : 'btn-warning' }}">
                                                    {{ $user->is_active ? __('table.status_active') : __('table.status_inactive') }}
                                                </button>
                                            </form>
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
                {{ $users->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</main>

<x-admin.footer />
