<x-web.layout :seo="$seo ?? null" :title="__('web.terms_title')">

@php
    $termsParagraphs = $branding->legalParagraphs('terms');
    $privacyParagraphs = $branding->legalParagraphs('privacy');
    $cookiesParagraphs = $branding->legalParagraphs('cookies');
    $hasReturns = $branding->hasCartPolicy();
@endphp

<div class="container py-5">
    <div class="legal-layout">
        <nav class="legal-nav">
            <div class="nav flex-column">
                @if ($termsParagraphs !== [])
                    <a class="nav-link active" href="#terms">{{ __('web.terms_title') }}</a>
                @endif
                @if ($hasReturns)
                    <a class="nav-link" href="#returns">{{ __('web.returns_title') }}</a>
                @endif
                @if ($privacyParagraphs !== [])
                    <a class="nav-link" href="#privacy">{{ __('web.privacy_title') }}</a>
                @endif
                @if ($cookiesParagraphs !== [])
                    <a class="nav-link" href="#cookies">{{ __('web.cookies_title') }}</a>
                @endif
            </div>
        </nav>

        <div class="legal-prose">
            @if ($termsParagraphs !== [])
                <h1 class="h2 fw-bold mb-4" id="terms">{{ __('web.terms_title') }}</h1>
                @foreach ($termsParagraphs as $paragraph)
                    <p @class(['mb-5' => $loop->last])>{{ $paragraph }}</p>
                @endforeach
            @endif

            @if ($hasReturns)
                <x-web.return-policy class="mb-5" anchor />
            @endif

            @if ($privacyParagraphs !== [])
                <h2 class="h2 fw-bold mb-4" id="privacy">{{ __('web.privacy_title') }}</h2>
                @foreach ($privacyParagraphs as $paragraph)
                    <p @class(['mb-5' => $loop->last])>{{ $paragraph }}</p>
                @endforeach
            @endif

            @if ($cookiesParagraphs !== [])
                <h2 class="h2 fw-bold mb-4" id="cookies">{{ __('web.cookies_title') }}</h2>
                @foreach ($cookiesParagraphs as $paragraph)
                    <p @class(['mb-5' => $loop->last])>{{ $paragraph }}</p>
                @endforeach
            @endif
        </div>
    </div>
</div>

</x-web.layout>
