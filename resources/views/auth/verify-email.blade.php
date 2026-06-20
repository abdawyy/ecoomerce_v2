@php $locale = app()->getLocale(); @endphp

<x-guest-layout>
    @section('title', __('auth.verify_email_title'))
    @section('meta_description', __('auth.verify_email_description'))

    @push('meta')
        <meta name="description" content="{{ __('auth.verify_email_description') }}">
        <meta name="robots" content="noindex, nofollow">
    @endpush

    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('auth.verify_email_message') }}
        </div>


        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <x-button type="submit">
                    {{ __('auth.resend_verification_email') }}
                </x-button>
            </form>

            <div>
                <a href="{{ route('account.profile') }}"
                   class="underline text-sm text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('auth.edit_profile') }}
                </a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                            class="underline text-sm text-gray-600 hover:text-gray-900 ms-2 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('auth.logout') }}
                    </button>
                </form>
            </div>
        </div>
    </x-authentication-card>
</x-guest-layout>
