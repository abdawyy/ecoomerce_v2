<x-web.layout :seo="$seo ?? null" :title="__('web.title_contact')">

@push('styles')
<style>
    .contact-container {
        max-width: 600px;
        margin: 2rem auto 4rem;
        background: white;
        padding: 30px;
        border-radius: var(--radius-lg, 16px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        border: 1px solid var(--brand-border, #e5e7eb);
    }

    .contact-container h1 {
        text-align: center;
    }

    .contact-info {
        margin: 20px 0;
        text-align: center;
    }

    .social-icons {
        text-align: center;
        margin-top: 20px;
    }

    .social-icons .social-link {
        margin: 0 6px;
    }

    .contact-form {
        margin-top: 30px;
    }

    .contact-form input,
    .contact-form textarea {
        width: 100%;
        padding: 12px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    .contact-form button {
        width: 100%;
        padding: 12px;
        background-color: #111;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }

    .contact-form button:hover {
        background-color: #333;
    }

    [data-bs-theme="dark"] .contact-container {
        background: #161b22;
        border-color: #30363d;
    }
</style>
@endpush

<div class="contact-container">
    <h1>{{ __('web.title_contact') }}</h1>

    <div class="contact-info">
        <p>{{ __('web.location') }}: Cairo, Egypt</p>
    </div>

    <div class="social-icons">
        <x-web.social-links class="d-inline-flex gap-3 justify-content-center" />
    </div>

    <div class="contact-form">
        <x-alert-success />

        <form action="{{ route('contact.send') }}" method="post">
            @csrf
            <input type="text" name="name" placeholder="{{ __('web.name_placeholder') }}" required>
            <input type="email" name="email" placeholder="{{ __('web.email_placeholder') }}" required>
            <input type="tel" name="phone" placeholder="{{ __('web.phone') }}" required>
            <textarea name="message" rows="5" placeholder="{{ __('web.message_placeholder') }}" required></textarea>
            <button type="submit">{{ __('web.send_button') }}</button>
        </form>
    </div>
</div>

</x-web.layout>
