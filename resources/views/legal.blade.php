<x-web.layout :seo="$seo ?? null" :title="__('web.terms_title')">

<div class="container py-5">
    <div class="legal-layout">
        <nav class="legal-nav">
            <div class="nav flex-column">
                <a class="nav-link active" href="#terms">{{ __('web.terms_title') }}</a>
                <a class="nav-link" href="#returns">{{ __('web.returns_title') }}</a>
                <a class="nav-link" href="#privacy">{{ __('web.privacy_title') }}</a>
                <a class="nav-link" href="#cookies">{{ __('web.cookies_title') }}</a>
            </div>
        </nav>

        <div class="legal-prose">
            <h1 class="h2 fw-bold mb-4" id="terms">{{ __('web.terms_title') }}</h1>
            @foreach ($branding->legalParagraphs('terms') as $paragraph)
                <p @class(['mb-5' => $loop->last])>{{ $paragraph }}</p>
            @endforeach

            <x-web.return-policy class="mb-5" anchor />

            <h2 class="h2 fw-bold mb-4" id="privacy">{{ __('web.privacy_title') }}</h2>
            @foreach ($branding->legalParagraphs('privacy') as $paragraph)
                <p @class(['mb-5' => $loop->last])>{{ $paragraph }}</p>
            @endforeach

            <h2 class="h2 fw-bold mb-4" id="cookies">{{ __('web.cookies_title') }}</h2>
            @foreach ($branding->legalParagraphs('cookies') as $paragraph)
                <p @class(['mb-5' => $loop->last])>{{ $paragraph }}</p>
            @endforeach
        </div>
    </div>
</div>

</x-web.layout>
