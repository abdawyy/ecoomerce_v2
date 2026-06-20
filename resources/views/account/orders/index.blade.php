<x-account.layout :title="__('account.orders')">
    @if ($orders->isEmpty())
        <div class="account-card account-empty">
            <i class="fa-solid fa-box-open d-block"></i>
            <h5 class="fw-bold">{{ __('account.no_orders_yet') }}</h5>
            <a href="{{ route('product.List') }}" class="btn btn-dark mt-2">{{ __('account.shop_now') }}</a>
        </div>
    @else
        <div class="account-card overflow-hidden">
            <div class="table-responsive">
                <table class="table account-table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('account.order_date') }}</th>
                            <th>{{ __('account.order_status') }}</th>
                            <th>{{ __('account.order_total') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td class="fw-semibold">#{{ $order->id }}</td>
                                <td class="text-muted small">{{ $order->created_at?->format('Y-m-d H:i') }}</td>
                                <td><x-account.status-pill :status="$order->status" /></td>
                                <td class="fw-bold">{{ number_format($order->total_amount, 2) }} {{ __('account.currency') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('account.orders.show', $order->id) }}" class="btn btn-sm btn-outline-dark">
                                        {{ __('account.view_order') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    @endif
</x-account.layout>
