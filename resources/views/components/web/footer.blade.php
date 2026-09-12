<footer class="storefront-footer text-light border-top border-secondary">
    <div class="container py-5">
        <div class="row gy-4">
            <div class="col-6 col-md-3">
                <h6 class="footer-heading">{{ __('web.footer_shop') }}</h6>
                <a href="{{ route('product.List') }}" class="footer-link">{{ __('web.all_products') }}</a>
                @foreach ($categories->take(4) as $category)
                    <a href="{{ route('product.List', ['id' => $category->id]) }}" class="footer-link">{{ $category->name }}</a>
                @endforeach
            </div>

            <div class="col-6 col-md-3">
                <h6 class="footer-heading">{{ __('web.footer_help') }}</h6>
                <a href="{{ route('contact.show') }}" class="footer-link">{{ __('web.title_contact') }}</a>
                <a href="{{ route('guides.index') }}" class="footer-link">{{ __('web.footer_guides') }}</a>
                <a href="{{ route('cart.index') }}" class="footer-link">{{ __('web.footer_cart') }}</a>
                @auth
                    <a href="{{ route('account.orders') }}" class="footer-link">{{ __('account.orders') }}</a>
                @else
                    <a href="{{ route('login') }}" class="footer-link">{{ __('account.login') }}</a>
                @endauth
            </div>

            <div class="col-6 col-md-3">
                <h6 class="footer-heading">{{ __('web.footer_legal') }}</h6>
                <a href="{{ route('legal') }}" class="footer-link">{{ __('web.terms_title') }}</a>
                <a href="{{ route('legal') }}#privacy" class="footer-link">{{ __('web.privacy_title') }}</a>
                <a href="{{ route('legal') }}#cookies" class="footer-link">{{ __('web.footer_cookies') }}</a>
            </div>

            <div class="col-12 col-md-3">
                <a href="{{ route('home') }}" class="d-inline-block mb-3">
                    <x-branding.logo :link="false" :dark="true" style="width: 120px; height: auto; max-height: 48px; object-fit: contain; filter: brightness(0) invert(1);" />
                </a>
                @if ($branding->tagline())
                    <p class="small text-white-50 mb-3">{{ $branding->tagline() }}</p>
                @endif
                @if (count($branding->socialLinks()) > 0)
                    <h6 class="footer-heading">{{ __('web.footer_social') }}</h6>
                    <x-web.social-links class="d-flex gap-3 mb-3" />
                @endif
                <p class="mb-0 small text-white-50">{!! __('web.footer_rights') !!}</p>
            </div>
        </div>
    </div>
</footer>

<style>
    #toast-container > .toast {
        background-color: #111 !important;
        color: #fff !important;
        opacity: 1 !important;
        box-shadow: 0 10px 24px rgba(0,0,0,0.25) !important;
        border-radius: 10px !important;
        background-image: none !important;
    }

    #toast-container > .toast-success { background-color: #1f9d55 !important; background-image: none !important; }
    #toast-container > .toast-error { background-color: #dc3545 !important; background-image: none !important; }
    #toast-container > .toast-warning { background-color: #f59e0b !important; background-image: none !important; }
    #toast-container > .toast-info { background-color: #0ea5e9 !important; background-image: none !important; }
    #toast-container > .toast .toast-message { color: #fff !important; }
    #toast-container > .toast .toast-close-button { color: #fff !important; opacity: 0.8 !important; }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>

<script>
    $(document).ready(function() {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            newestOnTop: true,
            positionClass: "toast-top-center",
            timeOut: 4000
        };
        window.__flashShown = window.__flashShown || {};
        @if(session('success'))
            if (!window.__flashShown.success) {
                toastr.success(@json(session('success')));
                window.__flashShown.success = true;
            }
        @endif

        @if(session('error'))
            if (!window.__flashShown.error) {
                toastr.error(@json(session('error')));
                window.__flashShown.error = true;
            }
        @endif

        @if($errors->any())
            @foreach ($errors->all() as $error)
                if (!window.__flashShown['err_{{ md5($error) }}']) {
                    toastr.error(@json($error));
                    window.__flashShown['err_{{ md5($error) }}'] = true;
                }
            @endforeach
        @endif
    });
</script>
