@props([
    'title',
    'subtitle' => null,
])

<section class="storefront-auth">
    <div class="container">
        <div class="storefront-auth-card">
            <div class="storefront-auth-brand">
                <x-branding.logo
                    compact
                    dark
                    :link="false"
                    style="width: auto; height: auto; max-height: 52px; max-width: 180px; object-fit: contain;"
                />
            </div>
            <div class="storefront-auth-form-wrap">
                <h1 class="storefront-auth-title text-center">{{ $title }}</h1>
                @if ($subtitle)
                    <p class="storefront-auth-subtitle text-center">{{ $subtitle }}</p>
                @endif

                <x-web.auth-errors />

                {{ $slot }}
            </div>
        </div>
    </div>
</section>

@once
    @push('scripts')
        <script>
            document.querySelectorAll('[data-password-toggle]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const input = document.querySelector(btn.getAttribute('data-password-toggle'));
                    if (!input) return;
                    const hidden = input.type === 'password';
                    input.type = hidden ? 'text' : 'password';
                    const icon = btn.querySelector('i');
                    if (icon) {
                        icon.className = hidden ? 'bi bi-eye-slash' : 'bi bi-eye';
                    }
                });
            });
        </script>
    @endpush
@endonce
