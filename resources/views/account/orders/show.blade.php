<x-account.layout :title="__('account.order_details')">
    <div class="mb-3">
        <a href="{{ route('account.orders') }}" class="text-decoration-none text-muted small">
            <i class="fa-solid fa-arrow-left me-1"></i>{{ __('account.back_to_orders') }}
        </a>
    </div>

    <div class="account-card mb-4">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2 py-3 px-4">
            <span class="fw-bold">{{ __('account.order_number', ['id' => $order->id]) }}</span>
            <div class="d-flex gap-2">
                <a href="{{ route('account.orders.invoice', $order->id) }}" class="btn btn-sm btn-dark" target="_blank">
                    <i class="fa-solid fa-file-pdf me-1"></i>{{ __('account.download_invoice') }}
                </a>
            </div>
        </div>
        <div class="card-body p-4">
            <x-account.order-stepper :status="$order->status" />

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="small text-muted">{{ __('account.order_date') }}</div>
                    <div class="fw-semibold">{{ $order->created_at?->format('Y-m-d H:i') }}</div>
                </div>
                <div class="col-md-4">
                    <div class="small text-muted">{{ __('account.delivery_city') }}</div>
                    <div class="fw-semibold">{{ $order->cities->name ?? '—' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="small text-muted">{{ __('account.order_total') }}</div>
                    <div class="fw-bold fs-5">{{ number_format($order->total_amount, 2) }} {{ __('account.currency') }}</div>
                </div>
            </div>

            @if ($order->discountCodes)
                <p class="mb-0 small text-muted">
                    {{ __('account.discount') }}: <strong>{{ $order->discountCodes->code }}</strong>
                </p>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="account-card">
                <div class="card-header py-3 px-4">{{ __('account.order_items') }}</div>
                <div class="table-responsive">
                    <table class="table account-table mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('account.product') }}</th>
                                <th>{{ __('account.size') }}</th>
                                <th>{{ __('account.quantity') }}</th>
                                <th>{{ __('account.price') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderItems as $item)
                                <tr>
                                    <td>{{ $item->product->name ?? '—' }}</td>
                                    <td>{{ $item->size ?? '—' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="fw-semibold">{{ number_format($item->price, 2) }} {{ __('account.currency') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="account-card">
                <div class="card-header py-3 px-4">{{ __('account.shipping_address') }}</div>
                <div class="card-body p-4">
                    @if ($order->address)
                        <p class="mb-1">{{ $order->address->address_line1 }}</p>
                        @if ($order->address->address_line2)
                            <p class="mb-1 text-muted">{{ $order->address->address_line2 }}</p>
                        @endif
                        <p class="mb-1">{{ $order->address->city }} {{ $order->address->postal_code }}</p>
                        <p class="mb-1">{{ $order->address->country }}</p>
                        <p class="mb-0"><i class="fa-solid fa-phone me-1"></i>{{ $order->address->phone_number }}</p>
                    @else
                        <p class="text-muted mb-0">—</p>
                    @endif

                    @if ($order->cities)
                        <hr>
                        <p class="mb-0 small text-muted">
                            {{ __('account.delivery_fee') }}:
                            <strong>{{ number_format($order->cities->price ?? 0, 2) }} {{ __('account.currency') }}</strong>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-account.layout>
