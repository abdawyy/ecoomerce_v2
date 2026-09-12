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

        h1,
        h2 {
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

        th,
        td {
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
            <h2>Order #<?php echo e($order->id); ?></h2>
            <p><strong>User:</strong> <?php echo e($order->user->name ?? 'N/A'); ?></p>
            <p><strong>City:</strong> <?php echo e($order->cities->name ?? 'N/A'); ?></p>
            <p><strong>Delivery Fees:</strong> <?php echo e($order->cities->price ?? 'N/A'); ?> LE</p>
            <p><strong>Total Amount:</strong> <?php echo e($order->total_amount ?? 'N/A'); ?> LE</p>
            <p><strong>Discount Code:</strong> <?php echo e($order->discountCodes->code ?? 'N/A'); ?></p>
            <p><strong>Order Date:</strong>
                <?php echo e($order->created_at ? $order->created_at->format('Y-m-d H:i') : 'N/A'); ?></p>
            <p><strong>Status:</strong> <?php echo e($order->status ?? 'Pending'); ?></p>
        </div>

        <h2>Address</h2>
        <div>
            <?php if($order->user && $order->user->address->isNotEmpty()): ?>
                <?php $__currentLoopData = $order->user->address; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="address-section">
                        <p><strong>Address Line 1:</strong> <?php echo e($address->address_line1 ?? 'N/A'); ?></p>
                        <p><strong>Address Line 2:</strong> <?php echo e($address->address_line2 ?? 'N/A'); ?></p>
                        <p><strong>City:</strong> <?php echo e($address->city ?? 'N/A'); ?></p>
                        <p><strong>State:</strong> <?php echo e($address->state ?? 'N/A'); ?></p>
                        <p><strong>Postal Code:</strong> <?php echo e($address->postal_code ?? 'N/A'); ?></p>
                        <p><strong>Country:</strong> <?php echo e($address->country ?? 'N/A'); ?></p>
                        <hr>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <p><strong>Address:</strong> N/A</p>
            <?php endif; ?>
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
         <?php $__empty_1 = true; $__currentLoopData = $order->orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <tr>
        <td><?php echo e($item->products_id); ?></td>
        <td><?php echo e($item->product->name ?? 'N/A'); ?></td>
        <td><?php echo e($item->size ?? 'N/A'); ?></td>
        <td><?php echo e($item->quantity ?? 0); ?></td>
        <td>
            <?php if($item->product->sale): ?>

                <?php echo e($item->product->price - ($item->product->price * $item->product->sale / 100)); ?> LE
            <?php else: ?>
                <?php echo e($item->product->price ?? 0); ?> LE
            <?php endif; ?>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <tr>
        <td colspan="5">No items found</td>
    </tr>
<?php endif; ?>

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
                <?php $__empty_1 = true; $__currentLoopData = $order->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($payment->id); ?></td>
                        <td><?php echo e($payment->amount ?? 0); ?> LE</td>
                        <td><?php echo e($payment->created_at ? $payment->created_at->format('Y-m-d H:i') : 'N/A'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="3">No payments found for this order.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <p><strong>Thank you for shopping with us!</strong></p>
        <p class="text-muted small">Your invoice is attached in English and Arabic (invoice-en.pdf, invoice-ar.pdf).</p>
    </div>
</body>

</html>
<?php /**PATH C:\hayah\resources\views/email/order-confirmation.blade.php ENDPATH**/ ?>