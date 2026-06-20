<x-admin.header />

<x-admin.aside />

<x-admin.navbar />



@php

    $useSample = request('template') === 'sample' && empty($model);

    $defaultEn = $useSample ? __('pdf_sample.html', [], 'en') : ($model->html_content_en ?? __('pdf_sample.html', [], 'en'));

    $defaultAr = $useSample ? __('pdf_sample.html', [], 'ar') : ($model->html_content_ar ?? '');

    $titleEn = $useSample ? __('pdf_sample.title', [], 'en') : old('title_en', $model->title_en ?? '');

    $titleAr = $useSample ? __('pdf_sample.title', [], 'ar') : old('title_ar', $model->title_ar ?? '');

    $descEn = $useSample ? __('pdf_sample.description', [], 'en') : old('description_en', $model->description_en ?? '');

    $descAr = $useSample ? __('pdf_sample.description', [], 'ar') : old('description_ar', $model->description_ar ?? '');

    $slugDefault = $useSample ? 'sample-editable-guide' : old('slug', $model->slug ?? '');

@endphp



<main id="main">

    <div class="container">

        <div class="row pt-4">

            <div class="pagetitle d-flex flex-wrap justify-content-between align-items-center gap-2">

                <h1>{{ __('guides.admin_title') }}</h1>

                <div class="d-flex flex-wrap gap-2">

                    <a href="{{ route('guides.admin.sample.preview', ['lang' => 'en']) }}" class="btn btn-outline-secondary btn-sm" target="_blank">{{ __('guides.sample_preview_en') }}</a>

                    <a href="{{ route('guides.admin.sample.preview', ['lang' => 'ar']) }}" class="btn btn-outline-secondary btn-sm" target="_blank">{{ __('guides.sample_preview_ar') }}</a>

                    @if ($model?->id)

                        <a href="{{ route('guides.admin.preview', ['id' => $model->id, 'lang' => 'en']) }}" class="btn btn-outline-primary btn-sm" target="_blank">{{ __('guides.preview_saved') }} (EN)</a>

                        <a href="{{ route('guides.admin.preview', ['id' => $model->id, 'lang' => 'ar']) }}" class="btn btn-outline-primary btn-sm" target="_blank">{{ __('guides.preview_saved') }} (AR)</a>

                    @endif

                </div>

            </div>

            <div class="card">

                <div class="card-body">

                    <p class="text-muted small">{{ __('guides.edit_hint') }}</p>

                    <form action="{{ route('guides.admin.edit', $model->id ?? null) }}" method="POST" enctype="multipart/form-data" id="guide-form">

                        @csrf

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="form-label">Title (EN) *</label>

                                <input type="text" name="title_en" class="form-control" value="{{ $titleEn }}" required>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">Title (AR)</label>

                                <input type="text" name="title_ar" class="form-control" value="{{ $titleAr }}">

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">Slug</label>

                                <input type="text" name="slug" class="form-control" value="{{ $slugDefault }}">

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">Sort</label>

                                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $model->sort_order ?? 0) }}">

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">Content type</label>

                                <select name="content_type" class="form-select">

                                    <option value="html" @selected(old('content_type', $model->content_type ?? 'html') === 'html')>{{ __('guides.content_html') }}</option>

                                    <option value="upload" @selected(old('content_type', $model->content_type ?? '') === 'upload')>{{ __('guides.content_upload') }}</option>

                                </select>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">Description (EN)</label>

                                <textarea name="description_en" class="form-control" rows="2">{{ $descEn }}</textarea>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">Description (AR)</label>

                                <textarea name="description_ar" class="form-control" rows="2">{{ $descAr }}</textarea>

                            </div>

                            <div class="col-12">

                                <div class="d-flex justify-content-between align-items-center mb-1">

                                    <label class="form-label mb-0">HTML (EN)</label>

                                    <button type="button" class="btn btn-link btn-sm p-0" id="load-sample-en">{{ __('guides.load_sample_template') }} (EN)</button>

                                </div>

                                <textarea name="html_content_en" id="html_content_en" class="form-control font-monospace" rows="12">{{ old('html_content_en', $defaultEn) }}</textarea>

                            </div>

                            <div class="col-12">

                                <div class="d-flex justify-content-between align-items-center mb-1">

                                    <label class="form-label mb-0">HTML (AR)</label>

                                    <button type="button" class="btn btn-link btn-sm p-0" id="load-sample-ar">{{ __('guides.load_sample_template') }} (AR)</button>

                                </div>

                                <textarea name="html_content_ar" id="html_content_ar" class="form-control font-monospace" rows="12">{{ old('html_content_ar', $defaultAr) }}</textarea>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">{{ __('guides.content_upload') }}</label>

                                <input type="file" name="pdf_file" class="form-control" accept="application/pdf">

                                @if (!empty($model->file_path))

                                    <small class="text-muted">{{ $model->file_path }}</small>

                                @endif

                            </div>

                            <div class="col-md-6 d-flex flex-column gap-2 justify-content-center">

                                <label class="form-check">

                                    <input type="checkbox" name="is_active" value="1" class="form-check-input" @checked(old('is_active', $model->is_active ?? true))> Active

                                </label>

                                <label class="form-check">

                                    <input type="checkbox" name="requires_auth" value="1" class="form-check-input" @checked(old('requires_auth', $model->requires_auth ?? false))> {{ __('guides.requires_auth') }}

                                </label>

                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary mt-3">{{ __('branding.save') }}</button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</main>



<script>

(function () {

    const sampleEn = @json(__('pdf_sample.html', [], 'en'));

    const sampleAr = @json(__('pdf_sample.html', [], 'ar'));

    document.getElementById('load-sample-en')?.addEventListener('click', () => {

        if (confirm('Replace English HTML with the sample template?')) {

            document.getElementById('html_content_en').value = sampleEn;

        }

    });

    document.getElementById('load-sample-ar')?.addEventListener('click', () => {

        if (confirm('Replace Arabic HTML with the sample template?')) {

            document.getElementById('html_content_ar').value = sampleAr;

        }

    });

})();

</script>



<x-admin.footer />

