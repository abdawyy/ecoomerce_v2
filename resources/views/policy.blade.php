<x-web.layout :title="__('web.privacy_title')">
    <section class="py-5">
        <div class="container">
            <div class="receipt-card mx-auto" style="max-width: 720px; text-align: start;">
                {!! $policy !!}
            </div>
        </div>
    </section>
</x-web.layout>
