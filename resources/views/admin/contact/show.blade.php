<x-admin.header />
<x-admin.aside />
<x-admin.navbar />

<main id="main">
    <div class="container">
        <div class="row pt-4">
            @php $isRtl = app()->getLocale() === 'ar'; @endphp

            <div class="pagetitle">
                <h1>{{ __('messages.title') }}</h1>
                <nav>
                    <ol class="breadcrumb d-flex {{ $isRtl ? 'text-end' : 'text-start' }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.contact.list') }}">{{ __('messages.breadcrumb_active') }}</a>
                        </li>
                        <li class="mx-2">-</li>
                        <li class="breadcrumb-item active">{{ __('messages.breadcrumb_show') }}</li>
                    </ol>
                </nav>
            </div>

            <div class="card">
                <div class="card-body">
                    <p><strong>{{ __('messages.from') }}:</strong> {{ $message->name }}</p>
                    <p><strong>{{ __('messages.email') }}:</strong> {{ $message->email }}</p>
                    <p><strong>{{ __('messages.phone') }}:</strong> {{ $message->phone ?: '—' }}</p>
                    <p><strong>{{ __('messages.received') }}:</strong> {{ $message->created_at?->format('Y-m-d H:i') }}</p>
                    <p class="mb-0"><strong>{{ __('messages.body') }}:</strong></p>
                    <p class="mt-2" style="white-space: pre-wrap;">{{ $message->message }}</p>
                    <a href="{{ route('admin.contact.list') }}" class="btn btn-outline-dark">{{ __('messages.breadcrumb_active') }}</a>
                </div>
            </div>
        </div>
    </div>
</main>

<x-admin.footer />
