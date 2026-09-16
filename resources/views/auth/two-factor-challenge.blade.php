<x-web.layout :title="__('auth.2fa_title')">
    <x-web.auth-card :title="__('auth.2fa_title')" :subtitle="__('auth.2fa_description')">
        <p class="text-muted" data-2fa-copy="auth">{{ __('auth.2fa_instruction') }}</p>
        <p class="text-muted d-none" data-2fa-copy="recovery">{{ __('auth.2fa_recovery_instruction') }}</p>

        <form method="POST" action="{{ route('two-factor.login') }}">
            @csrf

            <div class="mb-4" data-2fa-field="auth">
                <label for="code" class="form-label">{{ __('auth.code') }}</label>
                <input id="code" type="text" inputmode="numeric" name="code"
                       class="form-control form-control-lg" autofocus autocomplete="one-time-code">
            </div>

            <div class="mb-4 d-none" data-2fa-field="recovery">
                <label for="recovery_code" class="form-label">{{ __('auth.recovery_code') }}</label>
                <input id="recovery_code" type="text" name="recovery_code"
                       class="form-control form-control-lg" autocomplete="one-time-code" disabled>
            </div>

            <button type="submit" class="btn btn-dark btn-lg w-100 mb-3">{{ __('auth.login') }}</button>

            <button type="button" class="btn btn-link w-100" data-2fa-toggle>
                {{ __('auth.use_recovery_code') }}
            </button>
        </form>
    </x-web.auth-card>

    @push('scripts')
        <script>
            (function () {
                const toggle = document.querySelector('[data-2fa-toggle]');
                if (!toggle) return;
                let recovery = false;
                const labels = {
                    auth: @json(__('auth.use_recovery_code')),
                    recovery: @json(__('auth.use_auth_code')),
                };
                toggle.addEventListener('click', function () {
                    recovery = !recovery;
                    document.querySelectorAll('[data-2fa-copy]').forEach(function (el) {
                        el.classList.toggle('d-none', el.getAttribute('data-2fa-copy') !== (recovery ? 'recovery' : 'auth'));
                    });
                    document.querySelectorAll('[data-2fa-field]').forEach(function (el) {
                        const show = el.getAttribute('data-2fa-field') === (recovery ? 'recovery' : 'auth');
                        el.classList.toggle('d-none', !show);
                        const input = el.querySelector('input');
                        if (input) input.disabled = !show;
                    });
                    toggle.textContent = recovery ? labels.recovery : labels.auth;
                });
            })();
        </script>
    @endpush
</x-web.layout>
