<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src=<?php echo e(asset("admin/assets/js/bootstrap.bundle.min.js")); ?>></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src=<?php echo e(asset("admin/assets/js/admin-theme.js")); ?>></script>
<script src=<?php echo e(asset("admin/assets/js/main-Dashboard.js")); ?>></script>

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
	});
</script>
<?php /**PATH C:\hayah\resources\views/components/admin/footer.blade.php ENDPATH**/ ?>