<x-admin.confirm-dialog />

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src={{ asset("admin/assets/js/bootstrap.bundle.min.js")}}></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="{{ asset('admin/assets/js/admin-theme.js') }}?v={{ filemtime(public_path('admin/assets/js/admin-theme.js')) }}"></script>
<script src="{{ asset('admin/assets/js/admin-confirm.js') }}?v={{ filemtime(public_path('admin/assets/js/admin-confirm.js')) }}"></script>
<script src="{{ asset('admin/assets/js/main-Dashboard.js') }}?v={{ filemtime(public_path('admin/assets/js/main-Dashboard.js')) }}"></script>
@auth('admin')
@php
    $broadcastConn = config('broadcasting.connections.'.config('broadcasting.default'), config('broadcasting.connections.pusher'));
@endphp
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
<script>
    window.AdminNotifyConfig = {
        enabled: @json(in_array(config('broadcasting.default'), ['pusher', 'reverb'], true)),
        key: @json($broadcastConn['key'] ?? ''),
        cluster: @json(data_get($broadcastConn, 'options.cluster', 'mt1')),
        host: @json(data_get($broadcastConn, 'options.host', '127.0.0.1')),
        port: @json((int) data_get($broadcastConn, 'options.port', 6001)),
        forceTLS: @json(data_get($broadcastConn, 'options.scheme') === 'https'),
        authEndpoint: @json(url('/broadcasting/auth')),
        countsUrl: @json(route('admin.notifications.counts')),
        feedUrl: @json(route('admin.notifications.feed')),
        emptyText: @json(__('dashboard.no_activity')),
    };
</script>
<script src="{{ asset('admin/assets/js/admin-notifications.js') }}?v={{ filemtime(public_path('admin/assets/js/admin-notifications.js')) }}"></script>
@endauth

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
	});
</script>
