<x-admin.header />
<x-admin.aside />
<x-admin.navbar />

@php $isRtl = app()->getLocale() === 'ar'; @endphp

<main id="main">
    <div class="container">
        <div class="row pt-4">
            <div class="pagetitle">
                <h1>{{ __('branding.title') }}</h1>
                <nav>
                    <ol class="breadcrumb d-flex {{ $isRtl ? 'text-end' : 'text-start' }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('branding.breadcrumb_main') }}</a></li>
                        <li class="mx-2">-</li>
                        <li class="breadcrumb-item active">{{ __('branding.breadcrumb_active') }}</li>
                    </ol>
                </nav>
            </div>

            <div class="card">
                <div class="card-body p-4">
                    <form action="{{ route('admin.settings.branding.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('branding.site_name') }}</label>
                                <input type="text" name="site_name" class="form-control"
                                       value="{{ old('site_name', $settings->site_name ?? $branding->siteName()) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('branding.primary_color') }}</label>
                                <input type="color" name="primary_color" class="form-control form-control-color"
                                       value="{{ old('primary_color', $settings->primary_color ?? $branding->primaryColor()) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('branding.tagline_en') }}</label>
                                <input type="text" name="tagline_en" class="form-control"
                                       value="{{ old('tagline_en', $settings->tagline_en) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('branding.tagline_ar') }}</label>
                                <input type="text" name="tagline_ar" class="form-control"
                                       value="{{ old('tagline_ar', $settings->tagline_ar) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('branding.logo_alt_en') }}</label>
                                <input type="text" name="logo_alt_en" class="form-control"
                                       value="{{ old('logo_alt_en', $settings->logo_alt_en) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('branding.logo_alt_ar') }}</label>
                                <input type="text" name="logo_alt_ar" class="form-control"
                                       value="{{ old('logo_alt_ar', $settings->logo_alt_ar) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('branding.support_email') }}</label>
                                <input type="email" name="support_email" class="form-control"
                                       value="{{ old('support_email', $settings->support_email) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('branding.support_phone') }}</label>
                                <input type="text" name="support_phone" class="form-control"
                                       value="{{ old('support_phone', $settings->support_phone) }}">
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5>{{ __('branding.social_links') }}</h5>
                        <p class="text-muted small">{{ __('branding.social_links_hint') }}</p>
                        <div class="row g-3">
                            @foreach (config('branding.social_platforms', []) as $platform => $meta)
                                @php
                                    $labelKey = $meta['label_key'] ?? "branding.social_{$platform}";
                                    $label = __($labelKey);
                                    if ($label === $labelKey) {
                                        $label = ucfirst(str_replace('_', ' ', $platform));
                                    }
                                    $placeholder = $platform === 'whatsapp'
                                        ? 'https://wa.me/201234567890'
                                        : 'https://';
                                @endphp
                                <div class="col-md-6">
                                    <label class="form-label d-flex align-items-center gap-2">
                                        <i class="bi {{ $meta['icon'] ?? 'bi-link-45deg' }}"></i>
                                        {{ $label }}
                                    </label>
                                    <input type="text" name="{{ $platform }}_url" class="form-control"
                                           placeholder="{{ $placeholder }}"
                                           value="{{ old($platform.'_url', $settings->{$platform.'_url'}) }}">
                                </div>
                            @endforeach
                        </div>

                        <hr class="my-4">

                        <div class="row g-4">
                            @foreach ([
                                'logo' => __('branding.logo'),
                                'logo_dark' => __('branding.logo_dark'),
                                'favicon' => __('branding.favicon'),
                                'og_image' => __('branding.og_image'),
                                'footer_logo' => __('branding.footer_logo'),
                                'placeholder_product' => __('branding.placeholder_product'),
                            ] as $field => $label)
                                <div class="col-md-4">
                                    <label class="form-label">{{ $label }}</label>
                                    @php
                                        $pathColumn = $field === 'logo' ? 'logo_path' : ($field === 'logo_dark' ? 'logo_dark_path' : $field.'_path');
                                        $pathColumn = match($field) {
                                            'logo' => 'logo_path',
                                            'logo_dark' => 'logo_dark_path',
                                            'favicon' => 'favicon_path',
                                            'og_image' => 'og_image_path',
                                            'footer_logo' => 'footer_logo_path',
                                            'placeholder_product' => 'placeholder_product_path',
                                            default => $field.'_path',
                                        };
                                        $currentPath = $settings->{$pathColumn} ?? null;
                                    @endphp
                                    @if ($currentPath)
                                        <div class="mb-2">
                                            <img src="{{ $branding->assetUrl($currentPath) }}" alt="" class="img-thumbnail" style="max-height:80px">
                                        </div>
                                    @endif
                                    <input type="file" name="{{ $field }}" class="form-control" accept="image/*">
                                </div>
                            @endforeach
                        </div>

                        <hr class="my-4">
                        <h5>{{ __('branding.home_images') }}</h5>
                        <p class="text-muted small">{{ __('branding.home_images_hint') }}</p>
                        <div class="row g-4">
                            @foreach ([
                                'hero_image' => __('branding.hero_image'),
                                'category_image_1' => __('branding.category_image_1'),
                                'category_image_2' => __('branding.category_image_2'),
                            ] as $field => $label)
                                <div class="col-md-4">
                                    <label class="form-label">{{ $label }}</label>
                                    @php
                                        $pathColumn = match($field) {
                                            'hero_image' => 'hero_image_path',
                                            'category_image_1' => 'category_image_1_path',
                                            'category_image_2' => 'category_image_2_path',
                                            default => $field.'_path',
                                        };
                                        $currentPath = $settings->{$pathColumn} ?? null;
                                        $previewUrl = $currentPath
                                            ? $branding->assetUrl($currentPath)
                                            : match($field) {
                                                'hero_image' => $branding->heroImageUrl(),
                                                'category_image_1' => $branding->categoryImage1Url(),
                                                'category_image_2' => $branding->categoryImage2Url(),
                                                default => '',
                                            };
                                    @endphp
                                    @if ($previewUrl)
                                        <div class="mb-2 rounded overflow-hidden" style="height:100px;background:#f5f5f5">
                                            <img src="{{ $previewUrl }}" alt="" style="width:100%;height:100%;object-fit:cover">
                                        </div>
                                    @endif
                                    <input type="file" name="{{ $field }}" class="form-control" accept="image/*">
                                </div>
                            @endforeach
                        </div>

                        <hr class="my-4">
                        <h5>{{ __('branding.invoice_pdf') }}</h5>
                        <p class="text-muted small">{{ __('branding.invoice_notes_hint') }}</p>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">{{ __('branding.accent_color') }}</label>
                                <input type="color" name="accent_color" class="form-control form-control-color"
                                       value="{{ old('accent_color', $settings->accent_color ?? $branding->primaryColor()) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('branding.pdf_footer_en') }}</label>
                                <input type="text" name="pdf_footer_en" class="form-control" value="{{ old('pdf_footer_en', $settings->pdf_footer_en) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('branding.pdf_footer_ar') }}</label>
                                <input type="text" name="pdf_footer_ar" class="form-control" value="{{ old('pdf_footer_ar', $settings->pdf_footer_ar) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('branding.pdf_thank_you_en') }}</label>
                                <input type="text" name="pdf_thank_you_en" class="form-control" value="{{ old('pdf_thank_you_en', $settings->pdf_thank_you_en) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('branding.pdf_thank_you_ar') }}</label>
                                <input type="text" name="pdf_thank_you_ar" class="form-control" value="{{ old('pdf_thank_you_ar', $settings->pdf_thank_you_ar) }}">
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label mb-0">{{ __('branding.invoice_notes_en') }}</label>
                                    <button type="button" class="btn btn-link btn-sm p-0" id="load-invoice-notes-en">{{ __('branding.load_invoice_sample_notes') }} (EN)</button>
                                </div>
                                <textarea name="invoice_notes_en" id="invoice_notes_en" class="form-control" rows="5" placeholder="{{ __('branding.invoice_notes_placeholder') }}">{{ old('invoice_notes_en', $settings->invoice_notes_en ? strip_tags(str_replace(['</p>', '<br>', '<br/>', '<br />', '</li>'], ["\n", "\n", "\n", "\n", "\n"], $settings->invoice_notes_en)) : '') }}</textarea>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label mb-0">{{ __('branding.invoice_notes_ar') }}</label>
                                    <button type="button" class="btn btn-link btn-sm p-0" id="load-invoice-notes-ar">{{ __('branding.load_invoice_sample_notes') }} (AR)</button>
                                </div>
                                <textarea name="invoice_notes_ar" id="invoice_notes_ar" class="form-control" rows="5" dir="rtl" placeholder="{{ __('branding.invoice_notes_placeholder') }}">{{ old('invoice_notes_ar', $settings->invoice_notes_ar ? strip_tags(str_replace(['</p>', '<br>', '<br/>', '<br />', '</li>'], ["\n", "\n", "\n", "\n", "\n"], $settings->invoice_notes_ar)) : '') }}</textarea>
                            </div>
                        </div>
                        <div class="mt-3 d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.settings.pdf.preview', ['lang' => 'en']) }}" class="btn btn-primary btn-sm" target="_blank">{{ __('pdf.preview_sample_invoice_en') }}</a>
                            <a href="{{ route('admin.settings.pdf.preview', ['lang' => 'ar']) }}" class="btn btn-primary btn-sm" target="_blank">{{ __('pdf.preview_sample_invoice_ar') }}</a>
                        </div>

                        <hr class="my-4">
                        <h5>{{ __('branding.cart_policy') }}</h5>
                        <p class="text-muted small">{{ __('branding.cart_policy_hint') }}</p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('branding.cart_policy_title_en') }}</label>
                                <input type="text" name="cart_policy_title_en" class="form-control"
                                       value="{{ old('cart_policy_title_en', $settings->cart_policy_title_en) }}"
                                       placeholder="{{ __('cart.policy_title_default', [], 'en') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('branding.cart_policy_title_ar') }}</label>
                                <input type="text" name="cart_policy_title_ar" class="form-control" dir="rtl"
                                       value="{{ old('cart_policy_title_ar', $settings->cart_policy_title_ar) }}"
                                       placeholder="{{ __('cart.policy_title_default', [], 'ar') }}">
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label mb-0">{{ __('branding.cart_policy_body_en') }}</label>
                                    <button type="button" class="btn btn-link btn-sm p-0" id="load-cart-policy-en">{{ __('branding.load_cart_policy_sample') }} (EN)</button>
                                </div>
                                <textarea name="cart_policy_body_en" id="cart_policy_body_en" class="form-control" rows="5"
                                          placeholder="{{ __('branding.cart_policy_placeholder') }}">{{ old('cart_policy_body_en', $settings->cart_policy_body_en) }}</textarea>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label mb-0">{{ __('branding.cart_policy_body_ar') }}</label>
                                    <button type="button" class="btn btn-link btn-sm p-0" id="load-cart-policy-ar">{{ __('branding.load_cart_policy_sample') }} (AR)</button>
                                </div>
                                <textarea name="cart_policy_body_ar" id="cart_policy_body_ar" class="form-control" rows="5" dir="rtl"
                                          placeholder="{{ __('branding.cart_policy_placeholder') }}">{{ old('cart_policy_body_ar', $settings->cart_policy_body_ar) }}</textarea>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">{{ __('branding.save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
(function () {
    const notesEn = @json(__('invoice_sample.notes_plain', [], 'en'));
    const notesAr = @json(__('invoice_sample.notes_plain', [], 'ar'));
    const policyTitleEn = @json(__('cart.policy_title_default', [], 'en'));
    const policyTitleAr = @json(__('cart.policy_title_default', [], 'ar'));
    const policyBodyEn = @json(__('cart.policy_body_default', [], 'en'));
    const policyBodyAr = @json(__('cart.policy_body_default', [], 'ar'));

    document.getElementById('load-invoice-notes-en')?.addEventListener('click', () => {
        if (confirm('Replace English invoice notes with the sample?')) {
            document.getElementById('invoice_notes_en').value = notesEn;
        }
    });
    document.getElementById('load-invoice-notes-ar')?.addEventListener('click', () => {
        if (confirm('Replace Arabic invoice notes with the sample?')) {
            document.getElementById('invoice_notes_ar').value = notesAr;
        }
    });
    document.getElementById('load-cart-policy-en')?.addEventListener('click', () => {
        if (confirm('Replace English cart policy with the sample?')) {
            document.querySelector('input[name="cart_policy_title_en"]').value = policyTitleEn;
            document.getElementById('cart_policy_body_en').value = policyBodyEn;
        }
    });
    document.getElementById('load-cart-policy-ar')?.addEventListener('click', () => {
        if (confirm('Replace Arabic cart policy with the sample?')) {
            document.querySelector('input[name="cart_policy_title_ar"]').value = policyTitleAr;
            document.getElementById('cart_policy_body_ar').value = policyBodyAr;
        }
    });
})();
</script>

<x-admin.footer />
