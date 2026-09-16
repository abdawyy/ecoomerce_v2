<x-web.layout :title="__('auth.register_title')">
    <x-web.auth-card :title="__('auth.register')" :subtitle="__('auth.register_description')">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">{{ __('auth.name') }}</label>
                <input id="name" type="text" name="name" value="{{ old('name', request('name')) }}"
                       class="form-control form-control-lg @error('name') is-invalid @enderror"
                       required autofocus autocomplete="name">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">{{ __('auth.email') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email', request('email')) }}"
                       class="form-control form-control-lg @error('email') is-invalid @enderror"
                       required autocomplete="username">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">{{ __('auth.password') }}</label>
                <div class="input-group storefront-auth-password">
                    <input id="password" type="password" name="password"
                           class="form-control form-control-lg @error('password') is-invalid @enderror"
                           required autocomplete="new-password">
                    <button type="button" class="btn btn-outline-secondary" data-password-toggle="#password"
                            aria-label="{{ __('auth.show_password') }}">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">{{ __('auth.confirm_password') }}</label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                       class="form-control form-control-lg" required autocomplete="new-password">
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="terms" required>
                <label class="form-check-label small" for="terms">
                    {!! __('auth.agree_terms', [
                        'terms_of_service' => '<a href="'.e(route('legal')).'">'.e(__('auth.terms')).'</a>',
                        'privacy_policy' => '<a href="'.e(route('legal')).'#privacy">'.e(__('auth.privacy')).'</a>',
                    ]) !!}
                </label>
            </div>

            <button type="submit" class="btn btn-dark btn-lg w-100">{{ __('auth.register') }}</button>

            <p class="text-center text-muted small mt-4 mb-0">
                {{ __('auth.already_registered') }}
                <a href="{{ route('login') }}" class="fw-semibold">{{ __('auth.login') }}</a>
            </p>
        </form>
    </x-web.auth-card>
</x-web.layout>
