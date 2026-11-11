<div id="storefront-confirm"
    class="storefront-confirm"
    hidden
    role="dialog"
    aria-modal="true"
    aria-labelledby="storefront-confirm-title"
    aria-describedby="storefront-confirm-message"
    data-title="{{ __('web.dialog_title') }}"
    data-confirm="{{ __('web.dialog_confirm') }}"
    data-cancel="{{ __('web.dialog_cancel') }}">
    <div class="storefront-confirm-backdrop" data-storefront-confirm-dismiss></div>
    <div class="storefront-confirm-card" role="document">
        <h2 class="storefront-confirm-title" id="storefront-confirm-title">{{ __('web.dialog_title') }}</h2>
        <p class="storefront-confirm-message" id="storefront-confirm-message"></p>
        <div class="storefront-confirm-actions">
            <button type="button" class="btn storefront-confirm-cancel" id="storefront-confirm-cancel" data-storefront-confirm-dismiss>
                {{ __('web.dialog_cancel') }}
            </button>
            <button type="button" class="btn storefront-confirm-ok" id="storefront-confirm-ok">
                {{ __('web.dialog_confirm') }}
            </button>
        </div>
    </div>
</div>
