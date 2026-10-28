<?php if(session('error')): ?>
    <script>
        window.addEventListener("load", function () {
            window.__flashShown = window.__flashShown || {};
            if (!window.__flashShown.error && typeof toastr !== "undefined") {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    newestOnTop: true,
                    positionClass: "toast-top-center",
                    timeOut: 4000
                };
                toastr.error("<?php echo e(session('error')); ?>");
                window.__flashShown.error = true;
            }
        });
    </script>
<?php endif; ?>
<?php /**PATH C:\hayah\resources\views/components/alert-error.blade.php ENDPATH**/ ?>