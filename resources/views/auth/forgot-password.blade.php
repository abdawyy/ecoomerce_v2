<x-web.layout :title="__('auth.forgot_password_title')">
    <x-web.auth-card :title="__('auth.forgot_password')" :subtitle="__('auth.forgot_password_description')">
        <p class="text-muted mb-4">{{ __('auth.forgot_password_message') }}</p>
        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="form-label">{{ __('auth.email') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       class="form-control form-control-lg @error('email') is-invalid @enderror"
                       required autofocus autocomplete="username">
            </div>

            <button type="submit" class="btn btn-dark btn-lg w-100">{{ __('auth.send_reset_link') }}</button>

            <p class="text-center text-muted small mt-4 mb-0">
                <a href="{{ route('login') }}" class="fw-semibold">{{ __('auth.login') }}</a>
            </p>
        </form>
    </x-web.auth-card>
</x-web.layout>
