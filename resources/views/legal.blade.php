<x-web.layout :seo="$seo ?? null" :title="__('web.terms_title')">

<div class="container py-5">
    <div class="legal-layout">
        <nav class="legal-nav">
            <div class="nav flex-column">
                <a class="nav-link active" href="#terms">{{ __('web.terms_title') }}</a>
                <a class="nav-link" href="#privacy">{{ __('web.privacy_title') }}</a>
                <a class="nav-link" href="#cookies">{{ __('web.cookies_title') }}</a>
            </div>
        </nav>

        <div class="legal-prose">
            <h1 class="h2 fw-bold mb-4" id="terms">{{ __('web.terms_title') }}</h1>
            <p class="mb-5">{{ __('web.msg_terms') }}</p>

            <h2 class="h2 fw-bold mb-4" id="privacy">{{ __('web.privacy_title') }}</h2>
            <p class="mb-5">{{ __('web.msg_privacy') }}</p>

            <h2 class="h2 fw-bold mb-4" id="cookies">{{ __('web.cookies_title') }}</h2>
            <p>{{ __('web.msg_cookies') }}</p>
        </div>
    </div>
</div>

</x-web.layout>
