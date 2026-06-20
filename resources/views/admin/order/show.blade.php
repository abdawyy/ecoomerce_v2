<x-admin.header />
<x-admin.aside />
<x-admin.navbar />

<main id="main">
    <div class="container-fluid">
        <div class="row pt-4">
            @php $isRtl = app()->getLocale() === 'ar'; @endphp
            <div class="pagetitle">
                <h1>{{ __('orders.title') }}</h1>
                <nav>
                    <ol class="breadcrumb d-flex {{ $isRtl ? 'text-end' : 'text-start' }}"
                        dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                        <li class="breadcrumb-item"><a href="#">{{ __('orders.breadcrumb_main') }}</a></li>
                        <li class="mx-2">-</li>
                        <li class="breadcrumb-item active">{{ __('orders.breadcrumb_show') }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="container" id="receipt">
            <div class="row">
                <div class="col-12 text-end d-flex gap-2 justify-content-end flex-wrap">
                    <a href="{{ route('order.invoice', $order->id) }}" class="btn btn-primary" target="_blank">
                        {{ __('pdf.download_invoice') }}
                    </a>
                    <button id="printButton" class="btn btn-secondary" onclick="printReceipt()">
                        {{ __('orders.print_receipt') }}
                    </button>
                </div>
            </div>

            <h1>{{ __('orders.order_details') }}</h1>
            <div class="card mb-3">
                <div class="card-header">
                    {{ __('orders.order_number') }} #{{ $order->id }}
                </div>
                <div class="card-body">
                    <p><strong>{{ __('orders.user') }}:</strong> {{ $order->user->name ?? $order->guestUser->name ?? 'N/A' }}</p>
                    <p><strong>{{ __('orders.city') }}:</strong> {{ $order->cities->name ?? 'N/A' }}</p>
                    <p><strong>{{ __('orders.delivery_fees') }}:</strong> {{ $order->cities->price ?? 'N/A' }} LE</p>
                    <p><strong>{{ __('orders.total_amount') }}:</strong> {{ $order->total_amount }} LE</p>
                    <p><strong>{{ __('orders.discount_code') }}:</strong> {{ $order->discountCodes->code ?? 'N/A' }}</p>
                    <p><strong>{{ __('orders.date') }}:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
                    <p><strong>{{ __('orders.status') }}:</strong> {{ $order->status ?? 'Pending' }}</p>
                </div>
            </div>

            <h2>{{ __('orders.address') }}</h2>
            <div class="card mb-3">
                <div class="card-body">
                    @if ($order->address)
                        <div class="mb-3">
                            <p><strong>{{ __('orders.address_line1') }}:</strong>
                                {{ $order->address->address_line1 ?? 'N/A' }}</p>
                            <p><strong>{{ __('orders.address_line2') }}:</strong>
                                {{ $order->address->address_line2 ?? 'N/A' }}</p>

                            <p><strong>{{ __('orders.phone') }}:</strong> {{ $order->address->phone_number ?? 'N/A' }}</p>
                            
                             <p><strong>{{ __('checkout.country') }}:</strong> {{ $order->address->country ?? 'N/A' }}
                            </p>
                        </div>
                    @else
                        <p><strong>{{ __('orders.address') }}:</strong> N/A</p>
                    @endif
                </div>
            </div>


            <h2>{{ __('orders.order_items') }}</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('orders.product') }}</th>
                        <th>{{ __('orders.size') }}</th>
                        <th>{{ __('orders.quantity') }}</th>
                        <th>{{ __('orders.price') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($order->orderItems as $item)
                        <tr>
                            <td>{{ $item->products_id  }}</td>
                            <td>{{ $item->product->name ?? 'N/A' }}</td>
                            <td>{{ $item->size ?? 'N/A' }}</td>
                            <td>{{ $item->quantity ?? 0 }}</td>
                            <td>
                 
                                    {{ number_format(($item->quantity ?? 1) > 0 ? $item->price / $item->quantity : $item->price, 2) }} LE
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">{{ __('orders.no_items') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <h2>{{ __('orders.payments') }}</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('orders.amount') }}</th>
                        <th>{{ __('orders.date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($order->payments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>
                            <td>{{ $payment->amount }} LE</td>
                            <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">{{ __('orders.no_payments') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Status Update --}}
            <form action="{{ route('order.changeStatus', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="status">{{ __('orders.change_status') }}</label>
                    <select name="status" class="form-control">
                        <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Processing" {{ $order->status == 'Processing' ? 'selected' : '' }}>Processing
                        </option>
                        <option value="Completed" {{ $order->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Cancelled" {{ $order->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('orders.update') }}</button>
            </form>
        </div>
    </div>
</main>

{{-- Print Styles --}}
<style>
    @media print {

        #navbar,
        #sidebar,
        #printButton,
        form,
        .btn {
            display: none;
        }

        #receipt,
        #receipt * {
            visibility: visible;
        }

        #receipt {
            position: absolute;
            top: 10%;
            left: 10%;
            width: 100%;
        }
    }
</style>

{{-- Print Logic --}}
<script>
    function printReceipt() {
        window.print();
    }
</script>

<x-admin.footer />