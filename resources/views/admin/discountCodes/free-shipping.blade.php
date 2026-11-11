<x-admin.header />
<x-admin.aside />
<x-admin.navbar />

@php
    $isRtl = app()->getLocale() === 'ar';
    $enabled = (float) ($settings->free_shipping_min_amount ?? 0) > 0;
    $amountValue = old('free_shipping_min_amount', $enabled ? $settings->free_shipping_min_amount : '');
@endphp

<main id="main">
    <div class="container">
        <div class="row pt-4">
            <div class="pagetitle mb-3">
                <h1 class="mb-1">{{ __('discount_codes.free_shipping_title') }}</h1>
                <x-admin.return-button :href="\App\Support\AdminListReturn::href('discountCodes', 'discountCodes.list')" />
            </div>

            <x-admin.discount-tabs />

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                        <div>
                            <h5 class="mb-1">
                                <i class="bi bi-truck me-2"></i>{{ __('discount_codes.free_shipping_heading') }}
                            </h5>
                            <p class="text-muted mb-0">{{ __('discount_codes.free_shipping_intro') }}</p>
                        </div>
                        @if ($enabled)
                            <span class="badge bg-success fs-6">{{ __('discount_codes.free_shipping_enabled') }}</span>
                        @else
                            <span class="badge bg-secondary fs-6">{{ __('discount_codes.free_shipping_disabled') }}</span>
                        @endif
                    </div>

                    <form action="{{ route('discountCodes.freeShipping.update') }}" method="POST" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                        @csrf
                        <div class="mb-3" style="max-width: 360px;">
                            <label for="free_shipping_min_amount" class="form-label">{{ __('discount_codes.free_shipping_amount') }}</label>
                            <div class="input-group">
                                <input type="number"
                                       step="0.01"
                                       min="0"
                                       name="free_shipping_min_amount"
                                       id="free_shipping_min_amount"
                                       class="form-control @error('free_shipping_min_amount') is-invalid @enderror"
                                       value="{{ $amountValue }}"
                                       placeholder="0">
                                <span class="input-group-text">LE</span>
                            </div>
                            @error('free_shipping_min_amount')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <p class="form-text mb-0">{{ __('discount_codes.free_shipping_hint') }}</p>
                        </div>

                        <button type="submit" class="btn btn-dark py-2 px-4">{{ __('discount_codes.submit') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<x-admin.footer />
