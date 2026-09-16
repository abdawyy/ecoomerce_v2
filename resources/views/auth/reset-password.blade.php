<x-web.layout :title="__('auth.reset_password_title')">
    <x-web.auth-card :title="__('auth.reset_password')" :subtitle="__('auth.reset_password_description')">
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="mb-3">
                <label for="email" class="form-label">{{ __('auth.email') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}"
                       class="form-control form-control-lg @error('email') is-invalid @enderror"
                       required autofocus autocomplete="username">
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

            <div class="mb-4">
                <label for="password_confirmation" class="form-label">{{ __('auth.confirm_password') }}</label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                       class="form-control form-control-lg" required autocomplete="new-password">
            </div>

            <button type="submit" class="btn btn-dark btn-lg w-100">{{ __('auth.reset_password') }}</button>
        </form>
    </x-web.auth-card>
</x-web.layout>
