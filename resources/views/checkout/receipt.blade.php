<x-web.layout :title="__('confirmation.title')">

<section class="checkout-page pb-5">
    <div class="container">
        <div class="checkout-hero mb-4">
            <img src="{{ $branding->heroImageUrl() }}" alt="{{ $branding->siteName() }}">
            <div class="checkout-hero-overlay">
                <p class="checkout-hero-kicker mb-1">{{ $branding->tagline() ?: __('web.promo_tagline') }}</p>
                <h1 class="checkout-hero-title mb-0">{{ __('confirmation.heading') }}</h1>
            </div>
        </div>

        <x-web.checkout-stepper current="confirm" />

        <div class="receipt-card">
            <div class="receipt-icon"><i class="bi bi-check-lg"></i></div>
            <h2 class="h3 fw-bold mb-2">{{ __('confirmation.thank_you') }}</h2>
            <p class="text-muted mb-4">{{ __('confirmation.description') }}</p>

            <div class="text-start receipt-totals rounded-3 p-3 mb-4">
                <p class="mb-2">{{ __('confirmation.order_id') }} <strong>#{{ $orderID }}</strong></p>
                <p class="mb-2">{{ __('confirmation.delivery_fees') }} <strong>{{ $deliveryFees }} LE</strong></p>
                <p class="mb-0">{{ __('confirmation.total_price') }} <strong>{{ $totalPrice }} LE</strong></p>
            </div>

            @if (!empty($emailSent))
                <p class="small text-muted mb-4">{{ __('confirmation.email_sent') }}</p>
            @elseif (!empty($emailPending))
                <p class="small text-muted mb-4">{{ __('confirmation.email_pending') }}</p>
            @endif

            <div class="d-flex flex-wrap gap-2 justify-content-center">
                @auth
                    <a href="{{ route('account.orders.show', $orderID) }}" class="btn btn-dark">{{ __('account.view_in_account') }}</a>
                @else
                    @if (!empty($isGuestCheckout))
                        <p class="w-100 small text-muted mb-2">{{ __('account.guest_checkout_hint') }}</p>
                        <a href="{{ route('register', array_filter(['email' => $guestEmail ?? null, 'name' => $guestName ?? null])) }}"
                           class="btn btn-dark">{{ __('account.create_account_to_track') }}</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-dark">{{ __('account.login') }}</a>
                    @endif
                @endauth

                @if (!empty($invoiceUrl))
                    <a href="{{ $invoiceUrl }}" class="btn btn-outline-dark" target="_blank">
                        <i class="bi bi-file-earmark-pdf me-1"></i>{{ __('confirmation.download_invoice') }}
                    </a>
                @endif

                <a href="{{ route('product.List') }}" class="btn btn-primary">{{ __('confirmation.back_shop') }}</a>
            </div>
        </div>
    </div>
</section>

@if (!empty($notifyUrl))
<script>
    window.addEventListener('load', function () {
        var url = @json($notifyUrl);
        var token = document.querySelector('meta[name="csrf-token"]');
        if (!url || !token) return;
        var body = new FormData();
        body.append('_token', token.getAttribute('content'));
        if (navigator.sendBeacon) {
            navigator.sendBeacon(url, body);
            return;
        }
        fetch(url, { method: 'POST', body: body, credentials: 'same-origin', keepalive: true });
    });
</script>
@endif

<x-slot:scripts>
<script>
    (function () {
        if (!window.HayahPixel) return;
        var orderId = @json((string) $orderID);
        var key = 'hayah_pixel_purchase_' + orderId;
        try {
            if (sessionStorage.getItem(key)) return;
            sessionStorage.setItem(key, '1');
        } catch (e) {}
        window.HayahPixel.track('Purchase', {
            value: {{ number_format((float) $totalPrice, 2, '.', '') }},
            currency: 'EGP',
            content_type: 'product',
            contents: [{ id: orderId, quantity: 1 }],
            content_ids: [orderId]
        });
    })();
</script>
</x-slot:scripts>

</x-web.layout>
