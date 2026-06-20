@if(session('error'))
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
                toastr.error("{{ session('error') }}");
                window.__flashShown.error = true;
            }
        });
    </script>
@endif
