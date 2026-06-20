<x-admin.header />

<div class="admin-auth-page py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-9">
                <div class="card border-0 shadow-lg overflow-hidden rounded-4">
                    <div class="row g-0">
                        <div class="col-12 col-md-5 d-none d-md-flex">
                            <div class="admin-auth-visual w-100 d-flex flex-column justify-content-between p-4 p-xl-5">
                                <div>
                                    <h2 class="mb-3">{{ __('admin.login_title') }}</h2>
                                    <p class="mb-0 text-white-50">{{ __('admin.email') }} • {{ __('admin.password') }}</p>
                                </div>
                                <div class="small text-white-50">HAYAH Admin Portal</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-7">
                            <div class="p-4 p-lg-5">
                                <div class="d-flex justify-content-center justify-content-md-end align-items-center gap-2 mb-3 small">
                                    <a class="admin-lang-link text-decoration-none {{ app()->getLocale() == 'en' ? 'active' : '' }}" href="{{ url('/lang/en') }}">EN</a>
                                    <span class="text-muted">|</span>
                                    <a class="admin-lang-link text-decoration-none {{ app()->getLocale() == 'ar' ? 'active' : '' }}" href="{{ url('/lang/ar') }}">العربية</a>
                                    <span class="text-muted mx-1">|</span>
                                    <button type="button"
                                        id="admin-theme-toggle"
                                        class="btn btn-sm admin-icon-btn border admin-theme-toggle"
                                        data-label-dark="{{ __('admin.theme_dark') }}"
                                        data-label-light="{{ __('admin.theme_light') }}"
                                        aria-label="{{ __('admin.theme_dark') }}">
                                        <i class="bi bi-moon-stars-fill" id="admin-theme-icon"></i>
                                    </button>
                                </div>

                                @csrf
                                <h3 class="text-center mb-4 d-md-none">{{ __('admin.login_title') }}</h3>

                                <form action="{{ route('admin.login') }}" method="POST" class="admin-auth-form">
                                    @csrf
                                    <x-alert-error />

                                    <div class="mb-3">
                                        <label for="email" class="form-label">{{ __('admin.email') }}</label>
                                        <input type="email" name="email" id="email" class="form-control form-control-lg" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="password" class="form-label">{{ __('admin.password') }}</label>
                                        <input type="password" name="password" id="password" class="form-control form-control-lg" required>
                                        @error('password')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-dark btn-lg w-100">{{ __('admin.login_button') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-admin.footer />
