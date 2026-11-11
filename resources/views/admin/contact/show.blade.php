<x-admin.header />
<x-admin.aside />
<x-admin.navbar />

<main id="main">
    <div class="container">
        <div class="row pt-4">
            @php $isRtl = app()->getLocale() === 'ar'; @endphp

            <div class="pagetitle mb-3">
                <h1 class="mb-1">{{ __('messages.title') }}</h1>
                <x-admin.return-button :href="\App\Support\AdminListReturn::href('messages', 'admin.contact.list')" />
            </div>

            <div class="card">
                <div class="card-body">
                    <p><strong>{{ __('messages.from') }}:</strong> {{ $message->name }}</p>
                    <p><strong>{{ __('messages.email') }}:</strong> {{ $message->email }}</p>
                    <p><strong>{{ __('messages.phone') }}:</strong> {{ $message->phone ?: '—' }}</p>
                    <p><strong>{{ __('messages.received') }}:</strong> {{ $message->created_at?->format('Y-m-d H:i') }}</p>
                    <p class="mb-0"><strong>{{ __('messages.body') }}:</strong></p>
                    <p class="mt-2 mb-0" style="white-space: pre-wrap;">{{ $message->message }}</p>
                </div>
            </div>
        </div>
    </div>
</main>

<x-admin.footer />
