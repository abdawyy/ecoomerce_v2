<x-web.layout :seo="$seo ?? null" :title="__('web.returns_title')">

<div class="container py-5">
    <div class="legal-layout">
        <nav class="legal-nav">
            <div class="nav flex-column">
                <a class="nav-link active" href="#returns">{{ __('web.returns_title') }}</a>
            </div>
        </nav>

        <div class="legal-prose">
            <x-web.return-policy class="mb-5" anchor />
        </div>
    </div>
</div>

</x-web.layout>
