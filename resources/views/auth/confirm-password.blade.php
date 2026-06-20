@php
    $locale = app()->getLocale();
@endphp

<x-guest-layout>
    @section('title', __('auth.confirm_password_title'))
    @section('meta_description', __('auth.confirm_password_description'))

    @push('meta')
        <meta name="description" content="{{ __('auth.confirm_password_description') }}">
        <meta name="robots" content="noindex, nofollow">
    @endpush

    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{ __('auth.confirm_password_notice') }}
        </div>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div>
                <x-label for="password">{{ __('auth.password') }}</x-label>
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" autofocus />
            </div>

            <div class="flex justify-end mt-4">
                <x-button class="ms-4">
                    {{ __('auth.confirm') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
