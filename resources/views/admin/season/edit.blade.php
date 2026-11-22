<x-admin.header />
<x-admin.aside />
<x-admin.navbar />
@php $isRtl = app()->getLocale() === 'ar'; @endphp

<main id="main">
    <div class="container">
        <div class="row pt-4">
            <div class="pagetitle mb-3">
                <h1 class="mb-1">{{ __('seasons.title') }}</h1>
                <x-admin.return-button :href="\App\Support\AdminListReturn::href('seasons', 'season.list', optional($model)->id)" />
            </div>

            <div class="card">
                <div class="card-body">
                    <form id="prevent" action="{{ route('season.edit', $model->id ?? null) }}" method="POST">
                        @csrf
                        <input type="hidden" name="return" value="{{ \App\Support\AdminListReturn::fieldValue('seasons') }}">
                        <div class="row">
                            <div class="col-lg-12 col-12">
                                <div class="mb-3">
                                    <label for="seasonName" class="form-label">{{ __('seasons.form_label_name') }} <x-admin.required /></label>
                                    <input type="text" name="name" class="form-control"
                                           placeholder="{{ __('seasons.form_placeholder_name') }}"
                                           id="seasonName"
                                           value="{{ old('name', $model->name ?? '') }}" required>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-start {{ $isRtl ? 'justify-content-end' : '' }}">
                                <button type="submit" class="btn btn-dark py-2 px-2">
                                    {{ __('seasons.form_submit') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<x-admin.footer />
