<x-admin.header />

<x-admin.aside />

<x-admin.navbar />



<main id="main">

    <div class="container">

        <div class="row pt-4">

            <div class="pagetitle d-flex justify-content-between align-items-center flex-wrap gap-2">

                <h1>{{ __('guides.admin_title') }}</h1>

                <a href="{{ route('guides.admin.edit') }}" class="btn btn-primary btn-sm">{{ __('guides.add') }}</a>

            </div>



            <div class="row g-3 mb-4">

                <div class="col-md-6">

                    <div class="card h-100 border-primary border-opacity-25">

                        <div class="card-body">

                            <h5 class="card-title">{{ __('guides.admin_manual') }}</h5>

                            <p class="text-muted small mb-3">{{ __('admin_guide.description', [], app()->getLocale()) }}</p>

                            <div class="d-flex flex-wrap gap-2">

                                <a href="{{ route('guides.admin.manual', ['lang' => 'en']) }}" class="btn btn-outline-primary btn-sm" target="_blank">{{ __('guides.admin_manual_en') }}</a>

                                <a href="{{ route('guides.admin.manual', ['lang' => 'ar']) }}" class="btn btn-outline-primary btn-sm" target="_blank">{{ __('guides.admin_manual_ar') }}</a>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="card h-100">

                        <div class="card-body">

                            <h5 class="card-title">{{ __('guides.sample_pdf') }}</h5>

                            <p class="text-muted small mb-3">{{ __('pdf_sample.description', [], app()->getLocale()) }}</p>

                            <div class="d-flex flex-wrap gap-2">

                                <a href="{{ route('guides.admin.sample.preview', ['lang' => 'en']) }}" class="btn btn-outline-secondary btn-sm" target="_blank">{{ __('guides.sample_preview_en') }}</a>

                                <a href="{{ route('guides.admin.sample.preview', ['lang' => 'ar']) }}" class="btn btn-outline-secondary btn-sm" target="_blank">{{ __('guides.sample_preview_ar') }}</a>

                                <a href="{{ route('guides.admin.edit') }}?template=sample" class="btn btn-secondary btn-sm">{{ __('guides.load_sample_template') }}</a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            <form method="GET" class="mb-3">

                <input type="text" name="search" class="form-control" placeholder="Search" value="{{ $search }}">

            </form>

            <x-data-table :headers="$headers" :rows="$rows" :url="$url" />

            <div class="mt-3">{{ $data->links() }}</div>

        </div>

    </div>

</main>



<x-admin.footer />

