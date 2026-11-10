@php
    $siteName = isset($branding) ? $branding->siteName() : config('app.name', 'Admin');
@endphp
<div id="admin-confirm"
    class="admin-confirm"
    hidden
    role="dialog"
    aria-modal="true"
    aria-labelledby="admin-confirm-title"
    aria-describedby="admin-confirm-message"
    data-site-name="{{ $siteName }}"
    data-title="{{ __('admin.confirm_title') }}"
    data-alert-title="{{ __('admin.alert_title') }}"
    data-confirm="{{ __('admin.confirm_btn') }}"
    data-cancel="{{ __('admin.cancel_btn') }}"
    data-ok="{{ __('admin.ok_btn') }}"
    data-msg-save="{{ __('admin.confirm_save') }}"
    data-msg-logout="{{ __('admin.confirm_logout') }}"
    data-msg-status="{{ __('admin.confirm_status') }}"
    data-msg-delete="{{ __('admin.confirm_delete') }}"
    data-msg-order="{{ __('admin.confirm_order_status') }}"
    data-msg-add-size="{{ __('admin.confirm_add_size') }}">
    <div class="admin-confirm-backdrop" data-admin-confirm-dismiss></div>
    <div class="admin-confirm-card" role="document">
        <p class="admin-confirm-kicker" id="admin-confirm-kicker">{{ $siteName }}</p>
        <h2 class="admin-confirm-title" id="admin-confirm-title">{{ __('admin.confirm_title') }}</h2>
        <p class="admin-confirm-message" id="admin-confirm-message"></p>
        <div class="admin-confirm-actions">
            <button type="button" class="btn btn-outline-secondary" id="admin-confirm-cancel" data-admin-confirm-dismiss>
                {{ __('admin.cancel_btn') }}
            </button>
            <button type="button" class="btn btn-primary" id="admin-confirm-ok">
                {{ __('admin.confirm_btn') }}
            </button>
        </div>
    </div>
</div>
