<x-web.layout :title="__('auth.confirm_password_title')">
    <x-web.auth-card :title="__('auth.confirm')" :subtitle="__('auth.confirm_password_notice')">
        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="mb-4">
                <label for="password" class="form-label">{{ __('auth.password') }}</label>
                <div class="input-group storefront-auth-password">
                    <input id="password" type="password" name="password"
                           class="form-control form-control-lg @error('password') is-invalid @enderror"
                           required autofocus autocomplete="current-password">
                    <button type="button" class="btn btn-outline-secondary" data-password-toggle="#password"
                            aria-label="{{ __('auth.show_password') }}">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-dark btn-lg w-100">{{ __('auth.confirm') }}</button>
        </form>
    </x-web.auth-card>
</x-web.layout>
