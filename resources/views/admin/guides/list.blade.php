<x-admin.header />
<x-admin.aside />
<x-admin.navbar />

@php $isRtl = app()->getLocale() === 'ar'; @endphp

<main id="main">
    <div class="container">
        <div class="row pt-4">
            <div class="pagetitle mb-3">
                <h1 class="mb-1">{{ __('guides.chart_page_title') }}</h1>
                <p class="text-muted mb-0">{{ __('guides.chart_intro') }}</p>
            </div>

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card border-0 shadow-sm size-chart-admin">
                <div class="card-body p-4" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                    <form action="{{ route('guides.admin.sizeChart') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="size-chart-stage mb-4">
                            @if ($chartUrl)
                                <p class="size-chart-kicker">{{ __('guides.chart_current') }}</p>
                                <img src="{{ $chartUrl }}" alt="{{ __('guides.size_guide') }}" class="size-chart-preview">
                            @else
                                <div class="size-chart-empty">
                                    <i class="bi bi-rulers" aria-hidden="true"></i>
                                    <p class="mb-0">{{ __('guides.chart_empty') }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="mb-3" style="max-width: 420px;">
                            <label for="size_chart" class="form-label">{{ __('guides.chart_label') }}</label>
                            <input type="file"
                                   name="size_chart"
                                   id="size_chart"
                                   class="form-control @error('size_chart') is-invalid @enderror"
                                   accept="image/jpeg,image/png,image/webp">
                            @error('size_chart')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <p class="form-text mb-0">{{ __('guides.chart_hint') }}</p>
                        </div>

                        <button type="submit" class="btn btn-dark py-2 px-4">{{ __('guides.chart_save') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .size-chart-admin .size-chart-stage {
        background: #f6f3ee;
        border: 1px solid #e6e0d8;
        border-radius: 16px;
        padding: 20px;
    }
    .size-chart-kicker {
        font-size: 0.72rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #6b7280;
        font-weight: 700;
        margin-bottom: 12px;
    }
    .size-chart-preview {
        display: block;
        width: 100%;
        max-height: 460px;
        object-fit: contain;
        background: #fff;
        border-radius: 12px;
    }
    .size-chart-empty {
        min-height: 220px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        text-align: center;
        color: #6b7280;
        border: 1px dashed #d5cec4;
        border-radius: 12px;
        padding: 28px 18px;
        background: #fff;
    }
    .size-chart-empty i {
        font-size: 1.6rem;
    }
</style>

<x-admin.footer />
