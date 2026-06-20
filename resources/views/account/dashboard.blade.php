<x-account.layout :title="__('account.dashboard')">
    <p class="text-muted mb-4">{{ __('account.welcome', ['name' => $user->name]) }}</p>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="account-card account-kpi h-100">
                <div class="small text-muted text-uppercase fw-semibold mb-1">{{ __('account.total_orders') }}</div>
                <div class="account-kpi-value">{{ $orderCount }}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="account-card account-kpi h-100">
                <div class="small text-muted text-uppercase fw-semibold mb-1">{{ __('account.latest_order') }}</div>
                @if ($latestOrder)
                    <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap mt-2">
                        <div>
                            <div class="fw-bold">{{ __('account.order_number', ['id' => $latestOrder->id]) }}</div>
                            <x-account.status-pill :status="$latestOrder->status" class="mt-1" />
                        </div>
                        <a href="{{ route('account.orders.show', $latestOrder->id) }}" class="btn btn-dark btn-sm">
                            {{ __('account.view_order') }}
                        </a>
                    </div>
                @else
                    <p class="text-muted mb-0 mt-2">{{ __('account.no_orders_yet') }}</p>
                @endif
            </div>
        </div>
    </div>

    @if ($orderCount === 0)
        <div class="account-card account-empty">
            <i class="fa-solid fa-bag-shopping d-block"></i>
            <h5 class="fw-bold">{{ __('account.no_orders_yet') }}</h5>
            <a href="{{ route('product.List') }}" class="btn btn-dark mt-2">{{ __('account.shop_now') }}</a>
        </div>
    @else
        <div class="d-flex justify-content-end">
            <a href="{{ route('account.orders') }}" class="btn btn-outline-dark">{{ __('account.view_all_orders') }}</a>
        </div>
    @endif
</x-account.layout>
