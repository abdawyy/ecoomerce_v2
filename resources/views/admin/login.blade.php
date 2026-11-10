<x-admin.header />

<div class="admin-auth-page py-4 py-md-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-9">
                <div class="card admin-auth-card border-0 overflow-hidden">
                    <div class="row g-0">
                        <div class="col-12 col-md-5 d-none d-md-flex">
                            <div class="admin-auth-visual w-100 d-flex flex-column justify-content-between p-4 p-xl-5">
                                <div>
                                    <x-branding.logo
                                        compact
                                        dark
                                        :link="false"
                                        style="width: auto; height: auto; max-height: 64px; max-width: 200px; object-fit: contain;"
                                    />
                                    <p class="mt-4 mb-0 text-white-50">{{ __('admin.login_tagline') }}</p>
                                </div>
                                <div class="small text-white-50">{{ $branding->siteName() }}</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-7">
                            <div class="p-4 p-lg-5">
                                <div class="d-flex justify-content-between align-items-center gap-2 mb-4">
                                    <div class="d-md-none">
                                        <x-branding.logo
                                            compact
                                            :link="false"
                                            style="width: auto; max-height: 48px; max-width: 160px; object-fit: contain;"
                                        />
                                    </div>
                                    <div class="ms-auto d-flex align-items-center gap-2 small">
                                        <a class="admin-lang-link text-decoration-none {{ app()->getLocale() == 'en' ? 'active' : '' }}" href="{{ url('/lang/en') }}">EN</a>
                                        <span class="text-muted">|</span>
                                        <a class="admin-lang-link text-decoration-none {{ app()->getLocale() == 'ar' ? 'active' : '' }}" href="{{ url('/lang/ar') }}">العربية</a>
                                        <button type="button"
                                            id="admin-theme-toggle"
                                            class="btn btn-sm admin-icon-btn border admin-theme-toggle"
                                            data-label-dark="{{ __('admin.theme_dark') }}"
                                            data-label-light="{{ __('admin.theme_light') }}"
                                            aria-label="{{ __('admin.theme_dark') }}">
                                            <i class="bi bi-moon-stars-fill" id="admin-theme-icon"></i>
                                        </button>
                                    </div>
                                </div>

                                <h1 class="h3 mb-1">{{ __('admin.login_title') }}</h1>
                                <p class="text-muted mb-4">{{ __('admin.login_subtitle') }}</p>

                                <form action="{{ route('admin.login') }}" method="POST" class="admin-auth-form">
                                    @csrf
                                    <x-alert-error />

                                    <div class="mb-3">
                                        <label for="email" class="form-label">{{ __('admin.email') }}</label>
                                        <input type="email" name="email" id="email" class="form-control form-control-lg" value="{{ old('email') }}" required autocomplete="username">
                                        @error('email')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="password" class="form-label">{{ __('admin.password') }}</label>
                                        <input type="password" name="password" id="password" class="form-control form-control-lg" required autocomplete="current-password">
                                        @error('password')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-dark btn-lg w-100 btn-login">{{ __('admin.login_button') }}</button>
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
