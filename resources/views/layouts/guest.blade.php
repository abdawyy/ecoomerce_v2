<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        

    <title>{{ __('web.title') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
                    <link rel="stylesheet" href="https://hayahfashion.net/public/build/assets/app-B6b7m2dX.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

        <style>
            /* Global Toastr Styling */
            #toast-container > .toast {
                background-color: #111 !important;
                color: #fff !important;
                opacity: 1 !important;
                box-shadow: 0 10px 24px rgba(0,0,0,0.25) !important;
                border-radius: 10px !important;
                background-image: none !important;
            }

            #toast-container > .toast-success {
                background-color: #1f9d55 !important;
                background-image: none !important;
            }

            #toast-container > .toast-error {
                background-color: #dc3545 !important;
                background-image: none !important;
            }

            #toast-container > .toast-warning {
                background-color: #f59e0b !important;
                background-image: none !important;
            }

            #toast-container > .toast-info {
                background-color: #0ea5e9 !important;
                background-image: none !important;
            }

            #toast-container > .toast .toast-message {
                color: #fff !important;
            }

            #toast-container > .toast .toast-close-button {
                color: #fff !important;
                opacity: 0.8 !important;
            }
        </style>

    </head>
    <body>
        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>

        @livewireScripts
    </body>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://hayahfashion.net/public/build/assets/app-z-Rg4TxU.js"></script>

    <script>
        $(document).ready(function() {
            if (typeof toastr === "undefined") return;

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
                    toastr.success("{{ session('success') }}");
                    window.__flashShown.success = true;
                }
            @endif

            @if(session('error'))
                if (!window.__flashShown.error) {
                    toastr.error("{{ session('error') }}");
                    window.__flashShown.error = true;
                }
            @endif

            @if(session('status') === 'verification-link-sent')
                if (!window.__flashShown.status) {
                    toastr.success("{{ __('auth.verification_link_resent') }}");
                    window.__flashShown.status = true;
                }
            @elseif(session('status'))
                if (!window.__flashShown.status) {
                    toastr.success("{{ session('status') }}");
                    window.__flashShown.status = true;
                }
            @endif
        });
    </script>

</html>
