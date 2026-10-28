<x-admin.header />
<x-admin.aside />
<x-admin.navbar />
@php
    $isRtl = app()->getLocale() === 'ar';
@endphp

<main id="main">
    <div class="container">
        <div class="row pt-4">
            <div class="pagetitle mb-3">
                <h1 class="mb-1">{{ __('admin_list.view_title') }}</h1>
                <ol class="breadcrumb d-flex {{ $isRtl ? 'text-end' : 'text-start' }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                    <li class="breadcrumb-item"><a href="{{ route('admin.list') }}">{{ __('admin_list.breadcrumb_main') }}</a></li>
                    <li class="mx-2">-</li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.list') }}">{{ __('admin_list.breadcrumb_active') }}</a></li>
                    <li class="mx-2">-</li>
                    <li class="breadcrumb-item active">{{ __('admin_list.view_breadcrumb') }}</li>
                </ol>
            </div>

            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                        <dl class="row mb-0">
                            <dt class="col-sm-4">{{ __('table.headers.id') }}</dt>
                            <dd class="col-sm-8">#{{ $admin->id }}</dd>

                            <dt class="col-sm-4">{{ __('table.headers.name') }}</dt>
                            <dd class="col-sm-8">{{ $admin->username }}</dd>

                            <dt class="col-sm-4">{{ __('table.headers.email') }}</dt>
                            <dd class="col-sm-8">{{ $admin->email }}</dd>

                            <dt class="col-sm-4">{{ __('table.status') }}</dt>
                            <dd class="col-sm-8">
                                {{ $admin->is_active ? __('table.status_active') : __('table.status_inactive') }}
                            </dd>

                            <dt class="col-sm-4">{{ __('table.headers.created at') }}</dt>
                            <dd class="col-sm-8 mb-0">{{ optional($admin->created_at)->format('m/d/Y') }}</dd>
                        </dl>

                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <a href="{{ route('admin.list') }}" class="btn btn-outline-dark">{{ __('admin_list.back_to_list') }}</a>
                            <form method="POST" action="{{ route('admin.toggleStatus', $admin->id) }}">
                                @csrf
                                <button type="submit" class="btn {{ $admin->is_active ? 'btn-success' : 'btn-warning' }}">
                                    {{ $admin->is_active ? __('table.status_active') : __('table.status_inactive') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<x-admin.footer />
