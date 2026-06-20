<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <style>
        /* Basic styling for the email */
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: #333333;
        }

        p {
            color: #555555;
            line-height: 1.6;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn-primary {
            background-color: #007bff;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Order Details</h1>

        <div>
            <h2>Order #{{ $order->id }}</h2>
            <p><strong>User:</strong> {{ $order->user->name ?? 'N/A' }}</p>
            <p><strong>City:</strong> {{ $order->cities->name ?? 'N/A' }}</p>
            <p><strong>Delivery Fees:</strong> {{ $order->cities->price ?? 'N/A' }} LE</p>
            <p><strong>Total Amount:</strong> {{ $order->total_amount ?? 'N/A' }} LE</p>
            <p><strong>Discount Code:</strong> {{ $order->discountCodes->code ?? 'N/A' }}</p>
            <p><strong>Order Date:</strong>
                {{ $order->created_at ? $order->created_at->format('Y-m-d H:i') : 'N/A' }}</p>
            <p><strong>Status:</strong> {{ $order->status ?? 'Pending' }}</p>
        </div>

        <h2>Address</h2>
        <div>
            @if ($order->user && $order->user->address->isNotEmpty())
                @foreach ($order->user->address as $address)
                    <div class="address-section">
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

        <h2>Order Items</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
         @forelse($order->orderItems as $item)
    <tr>
        <td>{{ $item->products_id}}</td>
        <td>{{ $item->product->name ?? 'N/A' }}</td>
        <td>{{ $item->size ?? 'N/A' }}</td>
        <td>{{ $item->quantity ?? 0 }}</td>
        <td>
            @if ($item->product->sale)

                {{ $item->product->price - ($item->product->price * $item->product->sale / 100) }} LE
            @else
                {{ $item->product->price ?? 0 }} LE
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
        <table>
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
                        <td>{{ $payment->created_at ? $payment->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No payments found for this order.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <p><strong>Thank you for managing the orders!</strong></p>
    </div>
</body>

</html>
