<x-web.layout :seo="$seo ?? null" :title="__('checkout.payment_title')">

<section class="checkout-page pb-5 mb-5">
    <div class="container pb-5">
        <div class="checkout-hero mb-4">
            <img src="{{ $branding->heroImageUrl() }}" alt="{{ $branding->siteName() }}">
            <div class="checkout-hero-overlay">
                <p class="checkout-hero-kicker mb-1">{{ $branding->tagline() ?: __('web.promo_tagline') }}</p>
                <h1 class="checkout-hero-title mb-0">{{ __('checkout.payment_title') }}</h1>
            </div>
        </div>

        <x-web.checkout-stepper current="payment" />

        <div class="row justify-content-center pt-2">
            <div class="col-12 col-lg-9">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('checkout.order') }}" method="POST" id="checkout-payment-form">
                    @csrf
                    <input type="hidden" name="checkout_token" value="{{ $checkoutToken }}">

                    <div class="card p-4 shadow-sm rounded-3 border-0">
                        <h2 class="h5 fw-bold mb-1">{{ __('checkout.payment_title') }}</h2>
                        <p class="text-muted mb-4">{{ __('checkout.payment_lead') }}</p>

                        <div class="pay-methods">
                            <label class="pay-option">
                                <input type="radio" name="payment_method" value="cash" @checked(old('payment_method') === 'cash') required>
                                <span class="pay-option-logo-wrap">
                                    <img src="{{ asset('assets/images/cash.svg') }}" alt="" class="pay-option-logo">
                                </span>
                                <span class="pay-option-check" aria-hidden="true"><i class="bi bi-check-lg"></i></span>
                                <span class="pay-option-title">{{ __('checkout.pay_cash') }}</span>
                            </label>

                            <label class="pay-option">
                                <input type="radio" name="payment_method" value="instapay" @checked(old('payment_method') === 'instapay') required>
                                <span class="pay-option-logo-wrap">
                                    <img src="{{ asset('assets/images/instapay.svg') }}" alt="" class="pay-option-logo pay-option-logo-instapay">
                                </span>
                                <span class="pay-option-check" aria-hidden="true"><i class="bi bi-check-lg"></i></span>
                                <span class="pay-option-title">{{ __('checkout.pay_instapay') }}</span>
                            </label>

                            <div class="pay-option is-disabled" aria-disabled="true">
                                <span class="pay-option-logo-wrap">
                                    <img src="{{ asset('assets/images/visa.svg') }}" alt="" class="pay-option-logo">
                                </span>
                                <span class="pay-option-title">{{ __('checkout.pay_visa') }}</span>
                                <span class="pay-soon">{{ __('checkout.visa_soon') }}</span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <h6 class="fw-bolder mb-0">{{ __('checkout.total') }}</h6>
                            <h6 class="fw-bolder mb-0">LE {{ number_format($total, 2) }}</h6>
                        </div>

                        <button class="btn btn-dark w-100 mt-3 py-3 fw-bold" type="submit" id="checkout-pay-btn">
                            {{ __('checkout.confirm') }}
                        </button>
                        <p class="small text-muted text-center mt-2 mb-0">{{ __('checkout.placing_hint') }}</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    (function () {
        const form = document.getElementById('checkout-payment-form');
        const submitBtn = document.getElementById('checkout-pay-btn');
        form?.addEventListener('submit', function () {
            if (!submitBtn) return;
            submitBtn.disabled = true;
            submitBtn.textContent = @json(__('checkout.placing_order'));
        });
    })();
</script>

</x-web.layout>
