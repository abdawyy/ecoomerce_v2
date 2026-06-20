@if(session('success'))
    <script>
        window.addEventListener("load", function () {
            window.__flashShown = window.__flashShown || {};
            if (!window.__flashShown.success && typeof toastr !== "undefined") {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    newestOnTop: true,
                    positionClass: "toast-top-center",
                    timeOut: 4000
                };
                toastr.success("{{ session('success') }}");
                window.__flashShown.success = true;
            }
        });
    </script>
@endif
