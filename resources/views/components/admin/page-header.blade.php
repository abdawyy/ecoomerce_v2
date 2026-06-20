@props([
    'title',
    'breadcrumb' => null,
    'activeBreadcrumb' => null,
])

@php
    $isRtl = app()->getLocale() === 'ar';
@endphp

<div {{ $attributes->merge(['class' => 'admin-page-header pagetitle d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4']) }}>
    <div>
        <h1>{{ $title }}</h1>
        @if ($breadcrumb || $activeBreadcrumb)
            <nav>
                <ol class="breadcrumb d-flex {{ $isRtl ? 'text-end' : 'text-start' }} mb-0" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                    @if ($breadcrumb)
                        <li class="breadcrumb-item">{{ $breadcrumb }}</li>
                    @endif
                    @if ($activeBreadcrumb)
                        <li class="breadcrumb-item active">{{ $activeBreadcrumb }}</li>
                    @endif
                </ol>
            </nav>
        @endif
    </div>
    @if (isset($actions))
        <div class="d-flex flex-wrap gap-2 align-items-center">
            {{ $actions }}
        </div>
    @endif
</div>
