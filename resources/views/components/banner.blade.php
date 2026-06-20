@props(['style' => session('flash.bannerStyle', 'success'), 'message' => session('flash.banner')])

<script>
    window.__flashShown = window.__flashShown || {};

    function showBannerToast(style, message) {
        if (typeof toastr === "undefined" || !message) return;

        toastr.options = {
            closeButton: true,
            progressBar: true,
            newestOnTop: true,
            positionClass: "toast-top-center",
            timeOut: 4000
        };

        if (style === 'danger') {
            toastr.error(message);
        } else if (style === 'success') {
            toastr.success(message);
        } else {
            toastr.info(message);
        }
    }

    window.addEventListener('load', function () {
        if (!window.__flashShown.banner && "{{ $message }}") {
            showBannerToast("{{ $style }}", "{{ $message }}");
            window.__flashShown.banner = true;
        }
    });

    window.addEventListener('banner-message', function (event) {
        if (!event || !event.detail) return;
        showBannerToast(event.detail.style, event.detail.message);
    });
</script>
