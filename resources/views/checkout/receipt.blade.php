
<x-web.layout :title="__('confirmation.title')">

<div class="container">
    <div class="receipt-card">
        <div class="receipt-icon"><i class="bi bi-check-lg"></i></div>
        <h1 class="h3 fw-bold mb-2">{{ __('confirmation.heading') }}</h1>
        <p class="text-muted mb-4">{{ __('confirmation.thank_you') }}</p>

        <div class="text-start bg-light rounded-3 p-3 mb-4">
            <p class="mb-2">{{ __('confirmation.order_id') }} <strong>#{{ $orderID }}</strong></p>
            <p class="mb-2">{{ __('confirmation.delivery_fees') }} <strong>{{ $deliveryFees }} LE</strong></p>
            <p class="mb-0">{{ __('confirmation.total_price') }} <strong>{{ $totalPrice }} LE</strong></p>
        </div>

        @if (!empty($emailSent))
            <p class="small text-muted mb-4">{{ __('confirmation.email_sent') }}</p>
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

</x-web.layout>
