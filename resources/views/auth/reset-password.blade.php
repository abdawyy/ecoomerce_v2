@php
    $locale = app()->getLocale();
@endphp

<x-guest-layout>
    @section('title', __('auth.reset_password_title'))
    @section('meta_description', __('auth.reset_password_description'))

    @push('meta')
        <meta name="description" content="{{ __('auth.reset_password_description') }}">
        <meta name="robots" content="noindex, nofollow">
    @endpush

    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="block">
                <x-label for="email">{{ __('auth.email') }}</x-label>
                <x-input id="email" class="block mt-1 w-full"
                         type="email" name="email"
                         :value="old('email', $request->email)"
                         required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password">{{ __('auth.password') }}</x-label>
                <x-input id="password" class="block mt-1 w-full"
                         type="password" name="password"
                         required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation">{{ __('auth.confirm_password') }}</x-label>
                <x-input id="password_confirmation" class="block mt-1 w-full"
                         type="password" name="password_confirmation"
                         required autocomplete="new-password" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('auth.reset_password') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
