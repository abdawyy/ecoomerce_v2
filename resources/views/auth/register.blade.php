<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form id="registerForm" method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-label for="name">{{ __('auth.name') }}</x-label>
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', request('name'))" required
                    autofocus />
            </div>

            <div class="mt-4">
                <x-label for="email">{{ __('auth.email') }}</x-label>
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', request('email'))"
                    required />
            </div>

            <div class="mt-4">
                <x-label for="password">{{ __('auth.password') }}</x-label>
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation">{{ __('auth.confirm_password') }}</x-label>
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password"
                    name="password_confirmation" required />
            </div>

            <div class="flex items-center justify-between mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('auth.already_registered') }}
                </a>

                <x-button type="submit" id="registerBtn" class="ms-4">
                    {{ __('auth.register') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
    <style>
         #confirmationModal {
        position: fixed;
        inset: 0;
        z-index: 9999;
        background-color: rgba(0, 0, 0, 0.5);
    }

    #popUp {
        width: 90%;
        max-width: 400px;
    }

    @media (max-width: 640px) {
        #popUp {
            width: 95%;
        }
    }
    </style>
    <!-- Confirmation Modal -->
    <!-- Modal Background -->
    <div id="confirmationModal" class="fixed inset-0 z-50 hidden mb-5 bg-opacity-50 flex items-center justify-center">
        <!-- Modal Box -->
      <div class="bg-white p-4 rounded-md shadow-lg w-11/12 max-w-sm" id="popUp">
    <!-- Title -->
    <h2 class="text-base font-semibold text-gray-800 mb-2">
        {{ __('web.confirm_title') }}
    </h2>

    <!-- Message -->
    <p class="text-sm text-gray-600 mb-3">
        {!! __('web.confirm_message') !!}
    </p>

    <!-- Checkbox -->
    <div class="flex items-start mb-4">
        <input type="checkbox" id="termsInModal" class="mt-1 cursor-pointer" />
        <label for="termsInModal" class="ml-2 text-sm text-gray-700 cursor-pointer">
            <span class="block">
                {!! __('web.confirm_agree', [
                    'terms' => '<span class="text-blue-600 underline hover:text-blue-800">' . __('web.terms') . '</span>',
                    'privacy' => '<span class="text-blue-600 underline hover:text-blue-800">' . __('web.privacy') . '</span>',
                ]) !!}
            </span>
        </label>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-between space-x-2">
        <button id="cancelModal" class="px-3 py-1 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300 mx-1">
            {{ __('Cancel') }}
        </button>
        <button id="confirmModal" disabled class="px-3 py-1 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
            {{ __('Yes, I Agree') }}
        </button>
    </div>
</div>

    </div>





    <!-- JS Logic -->
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('registerForm');
            const registerBtn = document.getElementById('registerBtn');
            const modal = document.getElementById('confirmationModal');
            const cancelBtn = document.getElementById('cancelModal');
            const confirmBtn = document.getElementById('confirmModal');
            const termsCheckbox = document.getElementById('termsInModal');

            // Open modal
            registerBtn.addEventListener('click', function () {
                modal.classList.remove('hidden');
            });

            // Enable/disable "Yes, I Agree" based on checkbox
            termsCheckbox.addEventListener('change', function () {
                confirmBtn.disabled = !this.checked;
            });

            // Cancel button resets modal
            cancelBtn.addEventListener('click', function () {
                modal.classList.add('hidden');
                termsCheckbox.checked = false;
                confirmBtn.disabled = true;
            });

            // Confirm & submit
            confirmBtn.addEventListener('click', function () {
                modal.classList.add('hidden');
                form.submit();
            });
        });
    </script> --}}
</x-guest-layout>