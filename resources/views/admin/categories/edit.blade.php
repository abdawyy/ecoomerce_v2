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
                <h1>{{ __('category_list.title') }}</h1>
                <nav>
                    <ol class="breadcrumb d-flex {{ $isRtl ? 'text-end' : 'text-start' }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                        <li class="breadcrumb-item">
                            <a href="#">{{ __('category_list.breadcrumb_main') }}</a>
                        </li>
        <li class="mx-2">-</li>
                        <li class="breadcrumb-item active">
                            {{ __('category_list.breadcrumb_active') }}
                        </li>
                    </ol>
                </nav>
            </div><!-- End Breadcrumbs with a page title -->

            <div class="card">
                <div class="card-body">
                    <form id="prevent" action="{{ route('categories.edit', $model->id ?? null) }}" method="POST">
                        @csrf
                        <div class="row">
                            <!-- Name Field -->
                            <div class="col-lg-12 col-12">
                                <div class="mb-3">
                                    <label for="productName" class="form-label">{{ __('category_list.form_name') }}</label>
                                    <input type="text" name="name" placeholder="{{ __('category_list.form_placeholder') }}" class="form-control" id="productName" value="{{ old('name', $model->name ?? '') }}" required>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Type Select -->
                            <div class="col-lg-12 col-12">
                                <div class="mb-3">
                                    <label for="typeSelect" class="form-label">{{ __('category_list.form_type') }}</label>
                                    <select name="type_id" id="typeSelect" class="form-control" required>
                                        <option value="">{{ __('category_list.form_choose') }}</option>
                                        @foreach($types as $type)
                                            <option value="{{ $type->id }}" {{ old('type_id', $model->type_id ?? '') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12">
                            <div class="d-flex justify-content-start {{ $isRtl ? 'justify-content-end' : '' }}">
                                <button type="submit" class="btn btn-dark py-2 px-2">
                                    {{ __('category_list.form_submit') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

<x-admin.footer />
