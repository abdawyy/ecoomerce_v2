<x-web.layout :title="__('auth.verify_email_title')">
    <x-web.auth-card :title="__('auth.verify_email_title')" :subtitle="__('auth.verify_email_message')">
        <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
            @csrf
            <button type="submit" class="btn btn-dark btn-lg w-100">
                {{ __('auth.resend_verification_email') }}
            </button>
        </form>

        <div class="d-flex flex-wrap gap-3 justify-content-center">
            <a href="{{ route('account.profile') }}" class="small">{{ __('auth.edit_profile') }}</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-link p-0 small">{{ __('auth.logout') }}</button>
            </form>
        </div>
    </x-web.auth-card>
</x-web.layout>
