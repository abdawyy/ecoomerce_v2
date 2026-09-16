<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        

    <title><?php echo e(__('web.title')); ?></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

        <!-- Styles -->
        <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

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
            <?php echo e($slot); ?>

        </div>

        <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

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

            <?php if(session('success')): ?>
                if (!window.__flashShown.success) {
                    toastr.success("<?php echo e(session('success')); ?>");
                    window.__flashShown.success = true;
                }
            <?php endif; ?>

            <?php if(session('error')): ?>
                if (!window.__flashShown.error) {
                    toastr.error("<?php echo e(session('error')); ?>");
                    window.__flashShown.error = true;
                }
            <?php endif; ?>

            <?php if(session('status') === 'verification-link-sent'): ?>
                if (!window.__flashShown.status) {
                    toastr.success("<?php echo e(__('auth.verification_link_resent')); ?>");
                    window.__flashShown.status = true;
                }
            <?php elseif(session('status')): ?>
                if (!window.__flashShown.status) {
                    toastr.success("<?php echo e(session('status')); ?>");
                    window.__flashShown.status = true;
                }
            <?php endif; ?>
        });
    </script>

</html>
<?php /**PATH C:\hayah\resources\views/layouts/guest.blade.php ENDPATH**/ ?>