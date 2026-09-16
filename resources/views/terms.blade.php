<x-web.layout :title="__('web.terms_title')">
    <section class="py-5">
        <div class="container">
            <div class="receipt-card mx-auto" style="max-width: 720px; text-align: start;">
                {!! $terms !!}
            </div>
        </div>
    </section>
</x-web.layout>
