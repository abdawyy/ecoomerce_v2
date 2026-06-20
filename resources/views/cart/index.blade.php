@php
    $locale = app()->getLocale();

    function getProductFromCartItem($item)
    {
        if ($item instanceof \App\Models\shoppingCart) {
            return $item->product;
        } elseif (is_array($item)) {
            return \App\Models\products::find($item['product_id']);
        } elseif (is_object($item)) {
            return \App\Models\products::find($item->product->id ?? null);
        }

        return null;
    }

    function getProductItemSize($item) {
        $sizeId = is_array($item) ? ($item['size_id'] ?? null) : ($item->size_id ?? null);
        $productItem = \App\Models\productItems::find($sizeId);
        return $productItem?->size ?? '-';
    }

    function getCartQuantity($item) {
        return $item instanceof \App\Models\shoppingCart
            ? $item->quantity
            : ($item->quantity ?? 1);
    }

    function getCartItemPrice($product, $quantity) {
        $price = $product->price ?? 0;
        $sale = $product->sale ?? 0;
        $discounted = $price - ($price * $sale / 100);
        return $discounted * $quantity;
    }

    $isGuestCart = ! auth()->check();
@endphp

<x-web.layout :seo="$seo ?? null" :title="__('cart.title')">

<section id="cart-page" class="pb-5">
    <div class="container pb-5">
        <x-web.checkout-stepper current="cart" />

        @if ($isGuestCart && count($cartItems) > 0)
            <div class="alert alert-light border small mb-4">
                {{ __('cart.guest_cart_hint') }}
                <a href="{{ route('login') }}" class="fw-bold">{{ __('cart.login_to_save') }}</a>
            </div>
        @endif

        <div class="row pt-2">
            <div class="col-12 col-lg-7">
                @if (count($cartItems) > 0)
                    <h1 class="fw-bolder fs-3">
                        {{ __('cart.your_cart') }}
                        <span class="fc-gray fw-normal fs-4">({{ count($cartItems) }} {{ __('cart.items') }})</span>
                    </h1>
                @else
                    <h1 class="fw-bolder fs-3">{{ __('cart.empty') }}</h1>
                    <p class="text-muted">{{ __('cart.empty_cta') }}</p>
                @endif

                @foreach ($cartItems as $key => $item)
                    @php
                        $isGuestItem = !($item instanceof \App\Models\shoppingCart);
                        $product = getProductFromCartItem($item);
                        $quantity = getCartQuantity($item);
                        $priceTotal = getCartItemPrice($product, $quantity);
                        $size = getProductItemSize($item);
                    @endphp
                    <div class="card mb-3 shadow-sm rounded-3 border-0">
                        <div class="card-body p-3">
                            <div class="d-flex gap-3 align-items-start">
                                <div class="cart-img rounded-3 overflow-hidden bg-light d-flex align-items-center justify-content-center"
                                     style="width:110px; height:110px; flex-shrink:0;">
                                    <x-branding.product-image :product="$product" class="img-fluid" style="max-height:100%; max-width:100%;" />
                                </div>

                                <div class="flex-grow-1 w-100">
                                    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                                        <div class="me-2">
                                            <h5 class="card-title mb-1">{{ $product->name ?? 'Product' }}</h5>
                                            <div class="text-muted small">
                                                <span>{{ __('cart.color') }}: {{ $product->color ?? '-' }}</span>
                                                &middot;
                                                <span>{{ __('cart.size') }}: {{ $size }}</span>
                                            </div>
                                        </div>
                                        <div class="text-end mt-2 mt-md-0">
                                            <h5 class="fw-bolder mb-0">LE {{ number_format($priceTotal, 2) }}</h5>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div>
                                            @if (!$isGuestItem)
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center gap-1 qty-update-form">
                                                    @csrf
                                                    <button type="button" class="btn btn-outline-secondary cart-qty-btn qty-minus" data-target="qty-{{ $item->id }}">−</button>
                                                    <input type="number" name="quantity" id="qty-{{ $item->id }}" value="{{ $quantity }}" min="1" class="form-control form-control-sm text-center" style="width:52px">
                                                    <button type="button" class="btn btn-outline-secondary cart-qty-btn qty-plus" data-target="qty-{{ $item->id }}">+</button>
                                                    <button type="submit" class="btn btn-sm btn-outline-dark ms-1">{{ __('cart.update') }}</button>
                                                </form>
                                            @else
                                                <form action="{{ route('cart.guest.update', $item->key) }}" method="POST" class="d-flex align-items-center gap-1 qty-update-form">
                                                    @csrf
                                                    <button type="button" class="btn btn-outline-secondary cart-qty-btn qty-minus" data-target="qty-g-{{ $loop->index }}">−</button>
                                                    <input type="number" name="quantity" id="qty-g-{{ $loop->index }}" value="{{ $quantity }}" min="1" class="form-control form-control-sm text-center" style="width:52px">
                                                    <button type="button" class="btn btn-outline-secondary cart-qty-btn qty-plus" data-target="qty-g-{{ $loop->index }}">+</button>
                                                    <button type="submit" class="btn btn-sm btn-outline-dark ms-1">{{ __('cart.update') }}</button>
                                                </form>
                                            @endif
                                        </div>
                                        <div>
                                            @if (!$isGuestItem)
                                                <form action="{{ route('cart.delete', $item->id) }}" method="POST" class="d-inline cart-delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger p-0 border-0" aria-label="{{ __('cart.delete') }}">
                                                        <i class="fa-regular fa-trash-can fs-5"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('cart.guest.delete', $item->key) }}" method="POST" class="d-inline cart-delete-form">
                                                    @csrf
                                                    <button type="submit" class="btn btn-link text-danger p-0 border-0" aria-label="{{ __('cart.delete') }}">
                                                        <i class="fa-regular fa-trash-can fs-5"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if (count($cartItems) === 0 && ($featuredProducts ?? collect())->isNotEmpty())
                    <div class="row g-3 g-md-4 mt-2">
                        @foreach ($featuredProducts as $product)
                            <div class="col-6 col-lg-3">
                                <x-web.product-card :product="$product" />
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-4">
                        <a href="{{ route('product.List') }}" class="btn btn-dark">{{ __('web.shop_now') }}</a>
                    </div>
                @endif
            </div>

            @if ($total > 0)
                <div class="col-12 col-lg-5 mb-4">
                    <div style="position: sticky; top: 100px;">
                        <h5 class="fw-bolder fs-4 mb-3">{{ __('cart.summary') }}</h5>
                        <div class="card shadow-sm rounded-3 border-0">
                            <div class="card-body p-4">
                                <form action="{{ route('cart.promo') }}" method="POST" class="mb-3">
                                    @csrf
                                    <label class="form-label small">{{ __('cart.promo_code') }}</label>
                                    <div class="input-group">
                                        <input type="text" name="promo_code" class="form-control" value="{{ $appliedPromo ?? '' }}" placeholder="{{ __('checkout.enter_promo') }}">
                                        <button class="btn btn-outline-dark" type="submit">{{ __('cart.apply_promo') }}</button>
                                    </div>
                                </form>

                                <h6 class="fw-semibold text-muted mb-3">{{ __('cart.your_order') }}</h6>
                                <div class="d-flex justify-content-between align-items-center pb-2">
                                    <p class="mb-0 text-muted small">{{ __('cart.subtotal') }}</p>
                                    <p class="fw-bolder mb-0">LE {{ number_format($subtotal, 2) }}</p>
                                </div>

                                <hr>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bolder mb-0">{{ __('cart.total') }}</h6>
                                    <h6 class="fw-bolder mb-0">LE {{ number_format($total, 2) }}</h6>
                                </div>

                                <a href="{{ route('checkout') }}" class="btn btn-primary w-100 mt-3 py-2">
                                    {{ __('cart.checkout') }}
                                </a>
                                <p class="cart-trust-line mb-0">{{ __('cart.trust_line') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.querySelectorAll('.qty-minus, .qty-plus').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.getElementById(btn.dataset.target);
            if (!input) return;
            const val = parseInt(input.value, 10) || 1;
            input.value = Math.max(1, val + (btn.classList.contains('qty-plus') ? 1 : -1));
        });
    });
    document.querySelectorAll('.cart-delete-form').forEach(form => {
        form.addEventListener('submit', e => {
            if (!confirm(@json(__('cart.remove_confirm')))) e.preventDefault();
        });
    });
</script>
@endpush

</x-web.layout>
