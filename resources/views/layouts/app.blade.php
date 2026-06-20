<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('web.title') }}</title>

        <!-- Fonts -->
            <link rel="stylesheet" href="https://hayahfashion.net/public/build/assets/app-B6b7m2dX.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">


        <!-- Scripts -->

        <!-- Styles -->
        @livewireStyles
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
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    </body>
</html>
