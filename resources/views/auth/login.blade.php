<x-web.layout :title="__('auth.login_title')">
    <x-web.auth-card :title="__('auth.login')" :subtitle="__('auth.login_description')">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">{{ __('auth.email') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       class="form-control form-control-lg @error('email') is-invalid @enderror"
                       required autofocus autocomplete="username">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">{{ __('auth.password') }}</label>
                <div class="input-group storefront-auth-password">
                    <input id="password" type="password" name="password"
                           class="form-control form-control-lg @error('password') is-invalid @enderror"
                           required autocomplete="current-password">
                    <button type="button" class="btn btn-outline-secondary" data-password-toggle="#password"
                            aria-label="{{ __('auth.show_password') }}">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                    <label class="form-check-label" for="remember_me">{{ __('auth.remember_me') }}</label>
                </div>
                @if (Route::has('password.request'))
                    <a class="small" href="{{ route('password.request') }}">{{ __('auth.forgot_password') }}</a>
                @endif
            </div>

            <button type="submit" class="btn btn-dark btn-lg w-100">{{ __('auth.login') }}</button>

            <p class="text-center text-muted small mt-4 mb-0">
                {{ __('auth.no_account') }}
                <a href="{{ route('register') }}" class="fw-semibold">{{ __('auth.register') }}</a>
            </p>
        </form>
    </x-web.auth-card>
</x-web.layout>
