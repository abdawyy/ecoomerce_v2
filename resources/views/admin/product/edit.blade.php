<x-admin.header />
<x-admin.aside />

<x-admin.navbar />


@php $isRtl = app()->getLocale() === 'ar'; @endphp

<main id="main">
    <div class="container-fluid">
        <div class="row pt-4">
            <div class="pagetitle">
                <h1>{{ __('products.title') }}</h1>
                <nav>
                    <ol class="breadcrumb d-flex align-items-center justify-content-between flex-wrap {{ $isRtl ? 'text-end' : 'text-start' }}"
                        dir="{{ $isRtl ? 'rtl' : 'ltr' }}">

                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <li class="breadcrumb-item">
                                <a href="#">{{ __('products.breadcrumb_main') }}</a>
                            </li>
                            <li class="breadcrumb-separator">-</li>
                            <li class="breadcrumb-item active">
                                {{ __('products.breadcrumb_active') }}
                            </li>
                        </div>

                        @if (optional($model)->id)
                            <div class="ms-auto">
                                <a href="{{ url('admin/reviews/' . $model->id) }}" class="btn btn-warning btn-sm">
                                    {{ __('products.reviews_button') }}
                                </a>
                            </div>
                        @endif

                    </ol>

                </nav>
            </div>

            <div class="col-12 pb-4">
                <div class="card">
                    <div class="card-body">

                        {{-- Validation Messages --}}
                        <div class="container">
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <form id="prevent" action="{{ route('products.edit', $model->id ?? null) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-12">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label for="productName"
                                                class="form-label">{{ __('products.name') }}</label>
                                            <input type="text" placeholder="{{ __('products.name_placeholder') }}"
                                                value="{{ old('name', $model->name ?? '') }}"
                                                class="form-control @error('name') is-invalid @enderror"
                                                id="productName" name="name">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label for="Description"
                                                class="form-label">{{ __('products.description') }}</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror"
                                                placeholder="{{ __('products.description_placeholder') }}"
                                                id="Description" rows="3"
                                                name="description">{{ old('description', $model->description ?? '') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        @if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'slug'))
                                        <div class="col-12 mb-3 border-top pt-3">
                                            <h6>{{ __('seo.title') }}</h6>
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <label class="form-label">Slug</label>
                                                    <input type="text" name="slug" class="form-control" value="{{ old('slug', $model->slug ?? '') }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-check mt-4">
                                                        <input type="checkbox" name="is_indexable" value="1" class="form-check-input" @checked(old('is_indexable', $model->is_indexable ?? true))>
                                                        {{ __('seo.indexable') }}
                                                    </label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" name="meta_title_en" class="form-control" placeholder="{{ __('seo.meta_title_en') }}"
                                                           value="{{ old('meta_title_en', $model->meta_title_en ?? '') }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" name="meta_title_ar" class="form-control" placeholder="{{ __('seo.meta_title_ar') }}"
                                                           value="{{ old('meta_title_ar', $model->meta_title_ar ?? '') }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <textarea name="meta_description_en" class="form-control" rows="2" placeholder="{{ __('seo.meta_description_en') }}">{{ old('meta_description_en', $model->meta_description_en ?? '') }}</textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <textarea name="meta_description_ar" class="form-control" rows="2" placeholder="{{ __('seo.meta_description_ar') }}">{{ old('meta_description_ar', $model->meta_description_ar ?? '') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        @if (isset($guides) && $guides->isNotEmpty())
                                        <div class="col-12 mb-3">
                                            <label class="form-label">{{ __('guides.product_link') }}</label>
                                            <select name="guide_id" class="form-select">
                                                <option value="">—</option>
                                                @foreach ($guides as $g)
                                                    <option value="{{ $g->id }}" @selected(old('guide_id', $model->guide_id ?? '') == $g->id)>{{ $g->title_en }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @endif

                                        <div class="col-12 mb-3">
                                            <label class="form-label">{{ __('products.category') }}</label>
                                            <select name="category_id"
                                                class="form-select @error('category_id') is-invalid @enderror">
                                                <option disabled {{ !$model || !$model->category ? 'selected' : '' }}>
                                                    {{ __('products.choose') }}
                                                </option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ $model && $model->category_id === $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label class="form-label">{{ __('products.type') }}</label>
                                            <select name="type_id"
                                                class="form-select @error('type_id') is-invalid @enderror">
                                                <option disabled {{ !$model || !$model->type ? 'selected' : '' }}>
                                                    {{ __('products.choose') }}
                                                </option>
                                                @foreach($types as $type)
                                                    <option value="{{ $type->id }}" {{ $model && $model->type_id === $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('type_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label class="form-label">{{ __('products.color') }}</label>
                                            <input type="text" name="color" class="form-control"
                                                placeholder="{{ __('products.color_placeholder') }}"
                                                value="{{ old('color', $model->color ?? '') }}">
                                        </div>

                                        <div class="col-12 mb-3">
                                            @php $sizes = ['S', 'M', 'L', 'XL', 'XXL', 'XXXL','4XL','5XL','6XL' ,'S/M', 'L/XL','10','12','14']; @endphp
                                            @foreach($sizes as $size)
                                                @php $item = isset($model) && $model->productItems ? $model->productItems->firstWhere('size', $size) : null; @endphp
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="inlineCheckbox{{ $size }}" name="sizes[{{ $size }}]"
                                                        value="{{ $size }}" {{ $item && $item->quantity > 0 ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="inlineCheckbox{{ $size }}">{{ $size }}</label>
                                                    <input type="number" class="form-control ms-2"
                                                        name="quantities[{{ $size }}]"
                                                        value="{{ $item ? $item->quantity : '' }}" placeholder="Qty" min="0"
                                                        style="width: 120px;">
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="col-6 mb-3">
                                            <label class="form-label">{{ __('products.regular_price') }}</label>
                                            <input type="number" placeholder="80"
                                                class="form-control @error('price') is-invalid @enderror" name="price"
                                                value="{{ old('price', $model->price ?? '') }}">
                                            @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-6 mb-3">
                                            <label class="form-label">{{ __('products.sale_price') }}</label>
                                            <input type="number" placeholder="60"
                                                class="form-control @error('sale') is-invalid @enderror" name="sale"
                                                value="{{ old('sale', $model->sale ?? '') }}">
                                            @error('sale')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('products.upload_image') }}</label>
                                        <div
                                            class="uploadDiv d-flex align-items-center justify-content-center flex-column form-control">
                                            <i class='bx bx-image fs-1'></i>
                                            <p class="mb-0">{{ __('products.drop_or_browse') }}</p>
                                            <p>{{ __('products.allowed_images') }}</p>
                                            <input type="file" multiple name="images[]" accept="image/*">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <h4>{{ __('products.product_images') }}</h4>
                                        <div class="d-flex flex-wrap gap-3">
                                            @if($model && $model->productImages)
                                                @foreach($model->productImages as $image)
                                                    <div class="text-center">
                                                        <img src="{{ asset('storage/' . $image->images) }}"
                                                            class="imgDisplay my-2" style="width: 150px; height: auto;"><br>
                                                        <button type="submit" class="btn btn-danger mt-2"
                                                            form="delete-image-{{ $image->id }}">
                                                            {{ __('products.delete') }}
                                                        </button>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary"
                                id="submitId">{{ __('products.submit') }}</button>

                        </form>

                        @if($model && $model->productImages)
                            @foreach($model->productImages as $image)
                                <form id="delete-image-{{ $image->id }}" method="POST" action="{{ route('image.delete', $image->id) }}" class="d-none">
                                    @csrf
                                </form>
                            @endforeach
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- toast -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="error-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto text-danger">Error</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <!-- The error message will go here -->
        </div>
    </div>
</div>

<!-- Modal -->
<!-- Modal -->
<div class="modal fade" id="previeweModal" tabindex="-1" aria-labelledby="previeweModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="previeweModalLabel">Preview</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                            aria-selected="true">Card</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile"
                            aria-selected="false">Product Details</button>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                        aria-labelledby="pills-home-tab" tabindex="0">
                        <div class="row justify-content-center">
                            <div class="col-6">
                                <div class="card card-view">
                                    <img class="img-fluid ImagePreview0">
                                    <div class="card-body pt-2">
                                        <h5 class="fc-black card-title text-truncate mb-0" id="productNameCard">
                                            Product Name</h5>
                                        <p class="card-text text-truncate my-lg-1 my-0 fc-gray" id="DescriptionCard">
                                            Description</p>
                                        <span class="fw-bold fs-5"><span id="SalePriceCard2">60</span> LE</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"
                        tabindex="0">
                        <div class="row ">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12 px-2 image-display-dev" style="margin-bottom: 12px;">
                                        <img id="mainImage" class="ImagePreview0  img-fluid img-oneProduct pointer"
                                            alt="" onclick="openImage(this)">

                                    </div>
                                    <div class="col-3 px-2">
                                        <img class="ImagePreview0 img-fluid img-oneProduct-sub pointer img-selected"
                                            alt="" onclick="changeMainImage(this)">
                                    </div>
                                    <div class="col-3 px-2">
                                        <img class="ImagePreview1 img-fluid img-oneProduct-sub pointer" alt=""
                                            onclick="changeMainImage(this)">
                                    </div>
                                    <div class="col-3 px-2">
                                        <img class="ImagePreview2 img-fluid img-oneProduct-sub pointer" alt=""
                                            onclick="changeMainImage(this)">
                                    </div>
                                    <div class="col-3 px-2">
                                        <img class="ImagePreview3 img-fluid img-oneProduct-sub pointer" alt=""
                                            onclick="changeMainImage(this)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 pt-4 pt-lg-0">

                                <div class="d-flex justify-content-between align-items-center">
                                    <h1 class="fw-semibold mt-3 fs-3" id="productNameCard2">Product Name</h1>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fs-2 text-decoration-line-through fw-normal fst-italic fc-gray"><span
                                            id="RegularPriceCard">80</span> LE</span>
                                    <span class="fw-bold fs-2"><span id="SalePriceCard">60</span> LE</span>
                                </div>
                                <p class="fs-5 fw-bolder mb-0 pt-2">Description:</p>
                                <p class="lh-sm" id="DescriptionCard2">Description</p>

                                <div class="d-flex gap-2 flex-column">
                                    <span class="fs-5 fw-bolder">Color:</span>
                                    <div class="d-flex align-items-center gap-2 warp">
                                        <input type="radio" class="btn-check" name="color" id="black" autocomplete="off"
                                            checked>
                                        <label class="color-checked px-3 py-1 fw-semibold pointer"
                                            for="black">Black</label>

                                        <input type="radio" class="btn-check" name="color" id="pink" autocomplete="off">
                                        <label class="color-checked px-3 py-1 fw-semibold pointer"
                                            for="pink">Pink</label>

                                        <input type="radio" class="btn-check" name="color" id="babyBlue"
                                            autocomplete="off">
                                        <label class="color-checked px-3 py-1 fw-semibold pointer" for="babyBlue">Baby
                                            Blue</label>

                                        <input type="radio" class="btn-check" name="color" id="red" autocomplete="off">
                                        <label class="color-checked px-3 py-1 fw-semibold pointer" for="red">Red</label>

                                        <input type="radio" class="btn-check" name="color" id="white"
                                            autocomplete="off">
                                        <label class="color-checked px-3 py-1 fw-semibold pointer"
                                            for="white">White</label>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 flex-column pt-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="fs-5 fw-bolder">Size:</span>
                                        <span class="fs-6 fw-normal pointer" data-bs-toggle="modal"
                                            data-bs-target="#sizeModal"
                                            style="text-decoration: underline; text-underline-offset: 2px;">What's
                                            my size?</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 warp">
                                        <input type="radio" class="btn-check" name="size" id="S/M" autocomplete="off">
                                        <label class="color-checked px-3 py-1 fw-semibold pointer" for="S/M">S/M</label>
                                        <input type="radio" class="btn-check" name="size" id="L/XL" autocomplete="off">
                                        <label class="color-checked px-3 py-1 fw-semibold pointer"
                                            for="L/XL">L/XL</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function changeMainImage(smallImage) {
        const mainImage = document.getElementById('mainImage');
        mainImage.src = smallImage.src;

        // Optional: Add active class to the clicked thumbnail
        const thumbnails = document.querySelectorAll('.img-oneProduct-sub');
        thumbnails.forEach(thumb => thumb.classList.remove('img-selected'));
        smallImage.classList.add('img-selected');
    }

    function openImage(img) {
        // Create a div element for the overlay
        var overlay = document.createElement('div');
        overlay.style.position = 'fixed';
        overlay.style.top = '0';
        overlay.style.left = '0';
        overlay.style.width = '100%';
        overlay.style.height = '100%';
        overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
        overlay.style.display = 'flex';
        overlay.style.alignItems = 'center';
        overlay.style.justifyContent = 'center';
        overlay.style.zIndex = '999999999999';

        // Create an image element to display the clicked image
        var largeImg = document.createElement('img');
        largeImg.src = img.src;
        largeImg.style.maxWidth = '90%';
        largeImg.style.maxHeight = '90%';

        // Add the image to the overlay
        overlay.appendChild(largeImg);

        // Add the overlay to the body
        document.body.appendChild(overlay);

        // Close the overlay when clicking outside the image
        overlay.onclick = function (event) {
            if (event.target === overlay) {
                document.body.removeChild(overlay);
            }
        };
    }
</script>





<x-admin.footer />