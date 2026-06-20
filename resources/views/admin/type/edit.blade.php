<x-admin.header />
<x-admin.aside />
<x-admin.navbar />
@php $isRtl = app()->getLocale() === 'ar'; @endphp


<main id="main">
    <div class="container">
        <div class="row pt-4">
            <div class="pagetitle">
                <h1>{{ __('types.title') }}</h1>
                <nav>
                    <ol class="breadcrumb d-flex {{ $isRtl ? 'text-end' : 'text-start' }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                        <li class="breadcrumb-item">
                            <a href="#">{{ __('types.breadcrumb_main') }}</a>
                        </li>
                        <li class="mx-2">-</li>
                        <li class="breadcrumb-item active">
                            {{ __('types.breadcrumb_edit') }}
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="card">
                <div class="card-body">
                    <form id="prevent" action="{{ route('type.edit',  $model->id ?? null) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 col-12">
                                <div class="mb-3">
                                    <label for="productName" class="form-label">{{ __('types.form_label_name') }}</label>
                                    <input type="text" name="name" class="form-control"
                                           placeholder="{{ __('types.form_placeholder_name') }}"
                                           id="productName"
                                           value="{{ old('name', $model->name ?? '') }}" required>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-start">
                                <button type="submit" class="btn btn-dark py-2 px-2">
                                    {{ __('types.form_submit') }}
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
