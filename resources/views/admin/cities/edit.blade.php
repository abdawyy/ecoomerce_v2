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
                <h1 class="mb-1">{{ __('cities.title') }}</h1>
                <x-admin.return-button :href="\App\Support\AdminListReturn::href('cities', 'cities.list')" />
            </div>

            <div class="card">
                <div class="card-body">
                    <form id="prevent" action="{{ route('cities.edit', $model->id ?? null) }}" method="POST">
                        @csrf
                        <input type="hidden" name="return" value="{{ \App\Support\AdminListReturn::fieldValue('cities') }}">
                        <div class="row">
                            <div class="col-lg-12 col-12">
                                <div class="mb-3">
                                    <label for="productName" class="form-label">{{ __('cities.name') }} <x-admin.required /></label>
                                    <input type="text" name="name" placeholder="{{ __('cities.name_placeholder') }}" class="form-control" id="productName" value="{{ old('name', $model->name ?? '') }}" required>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12 col-12">
                                <div class="mb-3">
                                    <label for="citiesPrice" class="form-label">{{ __('cities.price') }} <x-admin.required /></label>
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
