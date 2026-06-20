<x-admin.header />


<div class="container">
    <div class="row">
        <div class="col-12 text-end">
            <button id="printButton" class="btn btn-secondary" onclick="printReceipt()">Print
                Receipt</button>
        </div>
    </div>
    <h1>Order Details</h1>

    <div class="card mb-3">
        <div class="card-header">
            Order #{{ $order->id }}
        </div>
        <div class="card-body">
            <p><strong>User:</strong> {{ $order->user->name ?? 'N/A' }}</p>
            <p><strong>City:</strong> {{ $order->cities->name ?? 'N/A' }}</p>
            <p><strong>Delivery Fees:</strong> {{ $order->cities->price ?? 'N/A' }}</p>
            <p><strong>Total Amount:</strong> {{ $order->total_amount ?? 'N/A' }} LE</p>


            <p><strong>Discount Code:</strong> {{ $order->discountCodes->code ?? 'N/A' }}</p>
            <p><strong>Order Date:</strong>
                {{ $order->created_at ? $order->created_at->format('Y-m-d H:i') : 'N/A' }}</p>
            <p><strong>Status:</strong> {{ $order->status ?? 'Pending' }}</p>
        </div>
    </div>
    <h1>Address</h1>

    <div class="card mb-3">
        <div class="card-body">
            @if ($order->user && $order->user->address->isNotEmpty())
                @foreach ($order->user->address as $address)
                    <div class="address-section mb-3">
                        <p><strong>Address Line 1:</strong> {{ $address->address_line1 ?? 'N/A' }}</p>
                        <p><strong>Address Line 2:</strong> {{ $address->address_line2 ?? 'N/A' }}</p>
                        <p><strong>City:</strong> {{ $address->city ?? 'N/A' }}</p>
                        <p><strong>State:</strong> {{ $address->state ?? 'N/A' }}</p>
                        <p><strong>Postal Code:</strong> {{ $address->postal_code ?? 'N/A' }}</p>
                                                <p><strong>Mobile:</strong> {{ $address->phone_number ?? 'N/A' }}</p>

                        <p><strong>Country:</strong> {{ $address->country ?? 'N/A' }}</p>
                        <hr>
                    </div>
                @endforeach
            @else
                <p><strong>Address:</strong> N/A</p>
            @endif
        </div>
    </div>

    <h2>Order Items</h2>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Size</th>

                <th>Quantity</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>

@forelse($order->orderItems as $item)
    <tr>
        <td>{{ $item->products_id }}</td>
        <td>{{ $item->product->name ?? 'N/A' }}</td>
        <td>{{ $item->size ?? 'N/A' }}</td>
        <td>{{ $item->quantity ?? 0 }}</td>
        <td>
            @if ($item->sale)
                <span class="mx-3 text-decoration-line-through fw-normal fst-italic fc-gray">
                    {{ $item->price }} LE
                </span>
                {{ $item->price - ($item->price * $item->sale / 100) }} LE
            @else
                {{ $item->price ?? 0 }} LE
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5">No items found</td>
    </tr>
@endforelse

        </tbody>
    </table>

    <h2>Payments</h2>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Amount</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>

            @forelse($order->payments as $payment)
                <tr>
                    <td>{{ $payment->id }}</td>
                    <td>{{ $payment->amount ?? 0 }} LE</td>
                    <td>{{ $payment->created_at ? $payment->created_at->format('Y-m-d H:i') : 'N/A' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No payments found for this order.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- <form id="form" action="{{ route('order.changeStatus', $order->id) }}" method="POST"
        class="mt-4">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="status">Change Status:</label>
            <select name="status" id="status" class="form-control">
                <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending
                </option>
                <option value="Processing" {{ $order->status == 'Processing' ? 'selected' : '' }}>
                    Processing</option>
                <option value="Completed" {{ $order->status == 'Completed' ? 'selected' : '' }}>
                    Completed
                </option>
                <option value="Cancelled" {{ $order->status == 'Cancelled' ? 'selected' : '' }}>
                    Cancelled
                </option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Status</button>
    </form>

</div>

<!-- Receipt Section -->
<div id="receipt" style="display: none;">
    <h2>Receipt</h2>
    <p><strong>Order ID:</strong> <span id="receiptOrderId"></span></p>
    <p><strong>User Name:</strong> <span id="receiptUserName"></span></p>
    <p><strong>City:</strong> <span id="receiptCity"></span></p>
    <p><strong>Order Date:</strong> <span id="receiptOrderDate"></span></p>
    <p><strong>Status:</strong> <span id="receiptOrderStatus"></span></p>
</div>
</div>
<!-- CSS for Print -->
<style>
@media print {

    #navbar,
    #sidebar,
    #printButton,
    #receipt,
    #form * {
        visibility: hidden;
    }

    #receipt,
    #receipt * {
        visibility: visible;
    }

    #receipt {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
    }
}
</style>

<!-- JavaScript -->
<script>
function generateReceipt() {
    // Get values from input fields
    const orderId = document.getElementById('orderId').value;
    const userName = document.getElementById('userName').value;
    const city = document.getElementById('city').value;
    const orderDate = document.getElementById('orderDate').value;
    const orderStatus = document.getElementById('orderStatus').value;

    // Populate receipt section
    document.getElementById('receiptOrderId').textContent = orderId;
    document.getElementById('receiptUserName').textContent = userName;
    document.getElementById('receiptCity').textContent = city;
    document.getElementById('receiptOrderDate').textContent = orderDate;
    document.getElementById('receiptOrderStatus').textContent = orderStatus;

    // Show the receipt section
    document.getElementById('receipt').style.display = 'block';
}

function printReceipt() {
    window.print();
}
</script> --}}
