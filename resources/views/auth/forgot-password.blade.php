@php
    $locale = app()->getLocale();
@endphp

<x-guest-layout>
    @section('title', __('auth.forgot_password_title'))
    @section('meta_description', __('auth.forgot_password_description'))

    @push('meta')
        <meta name="description" content="{{ __('auth.forgot_password_description') }}">
        <meta name="robots" content="noindex, nofollow">
    @endpush

    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{ __('auth.forgot_password_message') }}
        </div>


        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="block">
                <x-label for="email">{{ __('auth.email') }}</x-label>
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('auth.send_reset_link') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
