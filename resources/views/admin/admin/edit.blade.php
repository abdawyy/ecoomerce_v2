<x-admin.header />
<x-admin.aside />
<x-admin.navbar />
@php
    $isRtl = app()->getLocale() === 'ar';
    $isEdit = (bool) optional($model ?? null)->id;
@endphp

<main id="main">
    <div class="container">
        <div class="row pt-4">
            <div class="pagetitle mb-3">
                <h1 class="mb-1">{{ __('admin_list.title') }}</h1>
                <x-admin.return-button :href="\App\Support\AdminListReturn::href('admins', 'admin.list')" />
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ $isEdit ? route('admin.edit', $model->id) : route('admin.create') }}" method="POST">
                        @csrf
                        <input type="hidden" name="return" value="{{ \App\Support\AdminListReturn::fieldValue('admins') }}">

                        <div class="mb-3">
                            <label for="username" class="form-label">{{ __('admin_register.name') }} <x-admin.required /></label>
                            <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $model->username ?? '') }}" required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('admin_register.email') }} <x-admin.required /></label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $model->email ?? '') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                {{ __('admin_register.password') }}
                                @unless ($isEdit)
                                    <x-admin.required />
                                @endunless
                            </label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" @required(! $isEdit) autocomplete="new-password">
                            @if ($isEdit)
                                <div class="form-text">{{ __('admin_list.password_hint') }}</div>
                            @endif
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">
                                {{ __('admin_register.confirm_password') }}
                                @unless ($isEdit)
                                    <x-admin.required />
                                @endunless
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" @required(! $isEdit) autocomplete="new-password">
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if ($isEdit)
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('admin_list.created_by') }}</label>
                                    <input type="text" class="form-control" value="{{ $model->creator->username ?? '—' }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('admin_list.created_at') }}</label>
                                    <input type="text" class="form-control" value="{{ $model->created_at?->format('Y-m-d H:i') ?? '—' }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('admin_list.modified_by') }}</label>
                                    <input type="text" class="form-control" value="{{ $model->updater->username ?? '—' }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('admin_list.modified_at') }}</label>
                                    <input type="text" class="form-control" value="{{ $model->updated_at?->format('Y-m-d H:i') ?? '—' }}" readonly>
                                </div>
                            </div>
                        @endif

                        <button type="submit" class="btn btn-dark">{{ __('admin_list.form_submit') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<x-admin.footer />
