<x-admin.header />
<x-admin.aside />
<x-admin.navbar />
@php
    $isRtl = app()->getLocale() === 'ar';
@endphp
<main id="main">
    <div class="container">
        <div class="row pt-4">
            <div class="pagetitle">
                <h1>{{ __('cities.title') }}</h1>
                <nav>
      <ol class="breadcrumb d-flex {{ $isRtl ? 'text-end' : 'text-start' }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
    <li class="breadcrumb-item">
        <a href="#">{{ __('cities.breadcrumb_main') }}</a>
    </li>
    <li class="mx-2">-</li>
    <li class="breadcrumb-item active">
        {{ __('cities.breadcrumb_active') }}
    </li>
</ol>

                </nav>
            </div><!-- End Breadcrumbs with a page title -->

            <div class="card">
                <div class="card-body">
                    <form id="prevent" action="{{ route('cities.edit', $model->id ?? null) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 col-12">
                                <div class="mb-3">
                                    <label for="productName" class="form-label">{{ __('cities.name') }}</label>
                                    <input type="text" name="name" placeholder="{{ __('cities.name_placeholder') }}" class="form-control" id="productName" value="{{ old('name', $model->name ?? '') }}" required>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12 col-12">
                                <div class="mb-3">
                                    <label for="citiesPrice" class="form-label">{{ __('cities.price') }}</label>
                                    <input type="text" name="price" placeholder="{{ __('cities.price_placeholder') }}" class="form-control" id="productprice" value="{{ old('price', $model->price ?? '') }}" required>
                                    @error('price')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex justify-content-start">
                                <button type="submit" class="btn btn-dark py-2 px-2">{{ __('cities.submit') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

<x-admin.footer />
