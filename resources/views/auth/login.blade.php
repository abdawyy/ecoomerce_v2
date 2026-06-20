<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 flex items-center py-10 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-4xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 bg-white shadow-xl rounded-2xl overflow-hidden">
                <div class="hidden md:flex flex-col justify-between p-8 bg-slate-900 text-white">
                    <div>
                        <x-authentication-card-logo />
                        <h2 class="mt-6 text-2xl font-semibold">{{ __('auth.login') }}</h2>
                        <p class="mt-2 text-sm text-slate-300">
                            {{ __('auth.email') }} • {{ __('auth.password') }}
                        </p>
                    </div>
                    <p class="text-xs text-slate-400">HAYAH Store</p>
                </div>

                <div class="p-6 sm:p-8">
                    <div class="md:hidden flex items-center justify-center mb-6">
                        <x-authentication-card-logo />
                    </div>

                    <h1 class="text-2xl font-semibold text-slate-900 mb-2 text-center md:text-left">
                        {{ __('auth.login') }}
                    </h1>
                    <p class="text-sm text-slate-500 mb-6 text-center md:text-left">
                        {{ __('auth.email') }} • {{ __('auth.password') }}
                    </p>

                    <!-- Validation Errors -->
                    <x-validation-errors class="mb-4" />

                    <div class="mb-4 text-sm text-red-600">
                        <x-alert-error />
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <x-label for="email">{{ __('auth.email') }}</x-label>
                            <x-input id="email" class="block mt-2 w-full" type="email" name="email" :value="old('email')" required
                                autofocus autocomplete="username" />
                        </div>

                        <!-- Password -->
                        <div>
                            <x-label for="password">{{ __('auth.password') }}</x-label>
                            <x-input id="password" class="block mt-2 w-full" type="password" name="password" required
                                autocomplete="current-password" />
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <!-- Remember Me -->
                            <label for="remember_me" class="flex items-center">
                                <x-checkbox id="remember_me" name="remember" />
                                <span class="ms-2 text-slate-600">{{ __('auth.remember_me') }}</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a class="text-slate-500 hover:text-slate-900" href="{{ route('password.request') }}">
                                    {{ __('auth.forgot_password') }}
                                </a>
                            @endif
                        </div>

                        <!-- Submit Button and Links -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <x-button class="w-full sm:w-auto justify-center">
                                {{ __('auth.login') }}
                            </x-button>

                            <a href="{{ url('/register') }}" class="text-sm text-blue-600 hover:underline text-center">
                                {{ __('auth.register') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>