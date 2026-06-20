<x-admin.header />
<x-admin.aside />
<x-admin.navbar />

@php $isRtl = app()->getLocale() === 'ar'; @endphp

<main id="main">
    <div class="container">
        <div class="row pt-4">
            <div class="pagetitle">
                <h1>{{ __('seo.title') }}</h1>
                <nav>
                    <ol class="breadcrumb d-flex {{ $isRtl ? 'text-end' : 'text-start' }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('seo.breadcrumb_main') }}</a></li>
                        <li class="mx-2">-</li>
                        <li class="breadcrumb-item active">{{ __('seo.breadcrumb_active') }}</li>
                    </ol>
                </nav>
            </div>

            <div class="card">
                <div class="card-body p-4">
                    <form action="{{ route('admin.settings.seo.update') }}" method="POST">
                        @csrf
                        <h5 class="mb-3">{{ __('seo.global') }}</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('seo.meta_title_en') }}</label>
                                <input type="text" name="meta_title_en" class="form-control" value="{{ old('meta_title_en', $settings->meta_title_en) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('seo.meta_title_ar') }}</label>
                                <input type="text" name="meta_title_ar" class="form-control" value="{{ old('meta_title_ar', $settings->meta_title_ar) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('seo.meta_description_en') }}</label>
                                <textarea name="meta_description_en" class="form-control" rows="2">{{ old('meta_description_en', $settings->meta_description_en) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('seo.meta_description_ar') }}</label>
                                <textarea name="meta_description_ar" class="form-control" rows="2">{{ old('meta_description_ar', $settings->meta_description_ar) }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('seo.robots') }}</label>
                                <input type="text" name="robots" class="form-control" value="{{ old('robots', $settings->robots ?? 'index, follow') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('seo.canonical_url') }}</label>
                                <input type="url" name="canonical_url" class="form-control" value="{{ old('canonical_url', $settings->canonical_url) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('seo.twitter_card') }}</label>
                                <select name="twitter_card" class="form-select">
                                    <option value="summary_large_image" @selected(old('twitter_card', $settings->twitter_card) === 'summary_large_image')>summary_large_image</option>
                                    <option value="summary" @selected(old('twitter_card', $settings->twitter_card) === 'summary')>summary</option>
                                </select>
                            </div>
                        </div>

                        @if ($pages->isNotEmpty())
                            <h5 class="mb-3">{{ __('seo.per_page') }}</h5>
                            @foreach ($pages as $page)
                                <div class="border rounded p-3 mb-3">
                                    <strong>{{ $page->page_key }}</strong>
                                    <div class="row g-2 mt-2">
                                        <div class="col-md-6">
                                            <input type="text" name="pages[{{ $page->page_key }}][meta_title_en]" class="form-control form-control-sm" placeholder="Title EN"
                                                   value="{{ old('pages.'.$page->page_key.'.meta_title_en', $page->meta_title_en) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="pages[{{ $page->page_key }}][meta_title_ar]" class="form-control form-control-sm" placeholder="Title AR"
                                                   value="{{ old('pages.'.$page->page_key.'.meta_title_ar', $page->meta_title_ar) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <textarea name="pages[{{ $page->page_key }}][meta_description_en]" class="form-control form-control-sm" rows="2" placeholder="Description EN">{{ old('pages.'.$page->page_key.'.meta_description_en', $page->meta_description_en) }}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <textarea name="pages[{{ $page->page_key }}][meta_description_ar]" class="form-control form-control-sm" rows="2" placeholder="Description AR">{{ old('pages.'.$page->page_key.'.meta_description_ar', $page->meta_description_ar) }}</textarea>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-check">
                                                <input type="checkbox" class="form-check-input" name="pages[{{ $page->page_key }}][is_indexable]" value="1" @checked($page->is_indexable)>
                                                {{ __('seo.indexable') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        <button type="submit" class="btn btn-primary">{{ __('seo.save') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<x-admin.footer />
