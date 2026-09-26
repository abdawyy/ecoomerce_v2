@php $locale = app()->getLocale(); @endphp

<x-web.layout :seo="$seo ?? null" :title="__('checkout.shipping_address')">

<section id="cart-page" class="checkout-page pb-5 mb-5">
    <div class="container pb-5">
        <div class="checkout-hero mb-4">
            <img src="{{ $branding->heroImageUrl() }}" alt="{{ $branding->siteName() }}">
            <div class="checkout-hero-overlay">
                <p class="checkout-hero-kicker mb-1">{{ $branding->tagline() ?: __('web.promo_tagline') }}</p>
                <h1 class="checkout-hero-title mb-0">{{ __('checkout.shipping_address') }}</h1>
            </div>
        </div>

        <x-web.checkout-stepper current="details" />

        <div class="row pt-2">
            <div class="col-12">
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
            </div>

            <div class="col-12">
                <form action="{{ route('checkout.order') }}" method="POST" id="checkout-order-form">
                    @csrf
                    <input type="hidden" name="checkout_token" value="{{ $checkoutToken }}">
                    @if (session('promo_code'))
                        <input type="hidden" name="promo_code" value="{{ session('promo_code') }}">
                    @endif

                    <div class="row g-4">
                        <div class="col-12 col-lg-7">
                            <div class="card p-4 shadow-sm rounded-3 border-0">
                                @auth
                                    @if (($savedAddresses ?? collect())->isNotEmpty())
                                        <div class="mb-4">
                                            <label for="saved_address" class="form-label">{{ __('checkout.select_saved_address') }}</label>
                                            <select id="saved_address" class="form-select">
                                                <option value="">{{ __('checkout.saved_address') }}…</option>
                                                @foreach ($savedAddresses as $address)
                                                    <option value="{{ $address->id }}"
                                                        data-line1="{{ $address->address_line1 }}"
                                                        data-line2="{{ $address->address_line2 }}"
                                                        data-city="{{ $address->city }}"
                                                        data-country="{{ $address->country }}"
                                                        data-phone="{{ $address->phone_number }}">
                                                        {{ $address->address_line1 }}@if($address->is_default) ★@endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                @endauth

                                <div class="row g-3">
                                    @guest
                                        <div class="col-12 col-md-6">
                                            <label for="email" class="form-label">{{ __('checkout.email') }}</label>
                                            <input type="email" name="email" id="email"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   value="{{ old('email') }}" required>
                                            @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="full_name" class="form-label">{{ __('checkout.full_name') }}</label>
                                            <input type="text" name="full_name" id="full_name"
                                                   class="form-control @error('full_name') is-invalid @enderror"
                                                   value="{{ old('full_name') }}" required>
                                            @error('full_name')<div class="text-danger small">{{ $message }}</div>@enderror
                                        </div>
                                    @else
                                        <div class="col-12">
                                            <label for="full_name" class="form-label">{{ __('checkout.full_name') }}</label>
                                            <input type="text" name="full_name" id="full_name"
                                                   class="form-control @error('full_name') is-invalid @enderror"
                                                   value="{{ old('full_name', Auth::user()->name ?? '') }}" required>
                                            @error('full_name')<div class="text-danger small">{{ $message }}</div>@enderror
                                        </div>
                                    @endguest

                                    <div class="col-12">
                                        <label for="address_line_1" class="form-label">{{ __('checkout.address_line1') }}</label>
                                        <input type="text" name="address_line1" id="address_line_1"
                                               class="form-control @error('address_line1') is-invalid @enderror"
                                               value="{{ old('address_line1') }}" required>
                                        @error('address_line1')<div class="text-danger small">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="address_line_2" class="form-label">{{ __('checkout.address_line2') }}</label>
                                        <input type="text" name="address_line2" id="address_line_2"
                                               class="form-control @error('address_line2') is-invalid @enderror"
                                               value="{{ old('address_line2') }}">
                                        @error('address_line2')<div class="text-danger small">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="city" class="form-label">{{ __('checkout.city') }}</label>
                                        <select name="city" id="city" class="form-select @error('city') is-invalid @enderror" required>
                                            <option value="" disabled {{ old('city') ? '' : 'selected' }}>{{ __('checkout.select_city') }}</option>
                                            @foreach ($cities as $cityOption)
                                                <option value="{{ $cityOption->id }}" data-fee="{{ $cityOption->price }}"
                                                    {{ old('city') == $cityOption->id ? 'selected' : '' }}>
                                                    {{ $cityOption->name . ' - ' . $cityOption->price . ' LE ' . __('checkout.delivery_fees') }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('city')<div class="text-danger small">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="country" class="form-label">{{ __('checkout.country') }}</label>
                                        <input type="text" name="country" id="country"
                                               class="form-control @error('country') is-invalid @enderror"
                                               value="{{ old('country') }}" required>
                                        @error('country')<div class="text-danger small">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="phone_number" class="form-label">{{ __('checkout.phone_number') }}</label>
                                        <input type="text" name="phone_number" id="phone_number"
                                               class="form-control @error('phone_number') is-invalid @enderror"
                                               value="{{ old('phone_number') }}" required>
                                        @error('phone_number')<div class="text-danger small">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="promo_code" class="form-label">{{ __('checkout.promo_code') }}</label>
                                        <input type="text" name="promo_code" id="promo_code"
                                               class="form-control @error('promo_code') is-invalid @enderror"
                                               value="{{ old('promo_code', session('promo_code')) }}" placeholder="{{ __('checkout.enter_promo') }}" />
                                        @error('promo_code')<div class="text-danger small">{{ $message }}</div>@enderror
                                    </div>

                                    @guest
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="create_account" value="1" id="create_account" {{ old('create_account') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="create_account">{{ __('checkout.create_account_after') }}</label>
                                            </div>
                                        </div>
                                    @endguest
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-5">
                            <div class="checkout-summary-card">
                                <h5 class="fw-bolder fs-4 mb-3">{{ __('checkout.summary') }}</h5>
                                <div class="card shadow-sm rounded-3 border-0">
                                    <div class="card-body p-4">
                                        <h6 class="fw-semibold text-muted mb-3">{{ __('checkout.your_order') }}</h6>

                                        @foreach ($cartItems as $item)
                                            @php
                                                $product = $item->product ?? null;
                                                $qty = $item->quantity ?? 1;
                                                $unit = $product
                                                    ? ($product->price - ($product->price * ($product->sale ?? 0) / 100))
                                                    : 0;
                                            @endphp
                                            @if ($product)
                                                <div class="d-flex justify-content-between small mb-2">
                                                    <span>{{ $product->name }} × {{ $qty }}</span>
                                                    <span>LE {{ number_format($unit * $qty, 2) }}</span>
                                                </div>
                                            @endif
                                        @endforeach

                                        <hr>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>{{ __('checkout.subtotal') }}</span>
                                            <span id="checkout-subtotal">LE {{ number_format($subtotal, 2) }}</span>
                                        </div>
                                        @if (($discountPercent ?? 0) > 0)
                                            <div class="d-flex justify-content-between mb-2 text-success">
                                                <span>{{ __('checkout.discount') }} ({{ $discountPercent }}%)</span>
                                                <span id="checkout-discount">- LE {{ number_format($discountAmount ?? 0, 2) }}</span>
                                            </div>
                                        @endif
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>{{ __('checkout.delivery_fees') }}</span>
                                            <span id="checkout-delivery">LE {{ number_format($deliveryFee ?? 0, 2) }}</span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="fw-bolder mb-0">{{ __('checkout.total') }}</h6>
                                            <h6 class="fw-bolder mb-0" id="checkout-total">LE {{ number_format($total, 2) }}</h6>
                                        </div>
                                        <button class="btn btn-dark w-100 mt-3 py-3 fw-bold" type="submit" id="checkout-submit-btn">
                                            {{ __('checkout.confirm') }}
                                        </button>
                                        <p class="small text-muted text-center mt-2 mb-0">{{ __('checkout.placing_hint') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    (function () {
        const citySelect = document.getElementById('city');
        const subtotal = {{ (float) $subtotal }};
        const discountPercent = {{ (float) ($discountPercent ?? 0) }};
        const subtotalEl = document.getElementById('checkout-subtotal');
        const deliveryEl = document.getElementById('checkout-delivery');
        const totalEl = document.getElementById('checkout-total');
        const form = document.getElementById('checkout-order-form');
        const submitBtn = document.getElementById('checkout-submit-btn');

        function format(n) {
            return 'LE ' + n.toFixed(2);
        }

        function recalc() {
            const fee = parseFloat(citySelect?.selectedOptions[0]?.dataset.fee || 0);
            const discount = subtotal * (discountPercent / 100);
            const total = subtotal - discount + fee;
            if (deliveryEl) deliveryEl.textContent = format(fee);
            if (totalEl) totalEl.textContent = format(total);
        }

        citySelect?.addEventListener('change', recalc);

        const savedSelect = document.getElementById('saved_address');
        savedSelect?.addEventListener('change', () => {
            const opt = savedSelect.selectedOptions[0];
            if (!opt || !opt.value) return;
            document.getElementById('address_line_1').value = opt.dataset.line1 || '';
            document.getElementById('address_line_2').value = opt.dataset.line2 || '';
            document.getElementById('country').value = opt.dataset.country || '';
            document.getElementById('phone_number').value = opt.dataset.phone || '';
            if (opt.dataset.city && citySelect) {
                citySelect.value = opt.dataset.city;
                recalc();
            }
        });

        form?.addEventListener('submit', function () {
            if (!submitBtn) return;
            submitBtn.disabled = true;
            submitBtn.textContent = @json(__('checkout.placing_order'));
        });
    })();
</script>

<x-slot:scripts>
<script>
    if (window.HayahPixel) {
        window.HayahPixel.track('InitiateCheckout', {
            value: {{ number_format((float) ($total ?? $subtotal ?? 0), 2, '.', '') }},
            currency: 'EGP',
            num_items: {{ (int) (is_countable($cartItems ?? null) ? count($cartItems) : 0) }},
            content_type: 'product',
            content_ids: @json(collect($cartItems ?? [])->map(function ($item) {
                return (string) (is_object($item) ? ($item->product_id ?? $item->product->id ?? '') : ($item['product_id'] ?? ''));
            })->filter()->values())
        });
    }
</script>
</x-slot:scripts>

</x-web.layout>
