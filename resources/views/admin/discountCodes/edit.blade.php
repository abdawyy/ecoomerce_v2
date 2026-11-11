<x-admin.header />
<x-admin.aside />
<x-admin.navbar />

@php
    $isRtl = app()->getLocale() === 'ar';
@endphp

<main id="main">
    <div class="container">
        <div class="row pt-4">
            <div class="pagetitle mb-3">
                <h1 class="mb-1">{{ __('discount_codes.title') }}</h1>
                <x-admin.return-button :href="\App\Support\AdminListReturn::href('discountCodes', 'discountCodes.list')" />
            </div>

            <x-admin.discount-tabs />

            <div class="card">
                <div class="card-body">
                    <form id="prevent" action="{{ route('discountCodes.edit', $model->id ?? null) }}" method="POST" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                        @csrf
                        <input type="hidden" name="return" value="{{ \App\Support\AdminListReturn::fieldValue('discountCodes') }}">

                        <div class="row">
                            <!-- Code -->
                            <div class="col-lg-12 col-12">
                                <div class="mb-3">
                                    <label for="code" class="form-label">{{ __('discount_codes.code') }} <x-admin.required /></label>
                                    <input type="text" name="code" class="form-control"
                                           placeholder="{{ __('discount_codes.code_placeholder') }}"
                                           value="{{ old('code', $model->code ?? '') }}" required>
                                    @error('code')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Discount Percentage -->
                            <div class="col-lg-12 col-12">
                                <div class="mb-3">
                                    <label for="discount_percentage" class="form-label">{{ __('discount_codes.discount_percentage') }} <x-admin.required /></label>
                                    <input type="number" name="discount_percentage" min="1" max="100"
                                           class="form-control"
                                           placeholder="{{ __('discount_codes.discount_percentage_placeholder') }}"
                                           value="{{ old('discount_percentage', $model->discount_percentage ?? '') }}" required>
                                    @error('discount_percentage')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Expiry Date -->
                            <div class="col-lg-12 col-12">
                                <div class="mb-3">
                                    <label for="expiry_date" class="form-label">{{ __('discount_codes.expiry_date') }} <x-admin.required /></label>
                                    <input type="date" name="expiry_date" class="form-control"
                                           value="{{ old('expiry_date', $model->expiry_date ?? '') }}" required>
                                    @error('expiry_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="col-12">
                            <div class="d-flex justify-content-{{ $isRtl ? 'end' : 'start' }}">
                                <button type="submit" class="btn btn-dark py-2 px-2">{{ __('discount_codes.submit') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</main>

<x-admin.footer />
