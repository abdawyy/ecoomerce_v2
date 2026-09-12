<?php $__env->startSection('pdf-title', __('pdf.invoice').' #'.$order->id); ?>

<?php $__env->startSection('content'); ?>
    <h1 class="doc-title"><?php echo e(__('pdf.invoice')); ?> #<?php echo e($order->id); ?></h1>
    <p>
        <?php if(!empty($isSamplePreview)): ?>
            <span class="badge" style="background:#6c757d"><?php echo e(__('invoice_sample.sample_badge')); ?></span>
            &nbsp;
        <?php endif; ?>
        <span class="badge"><?php echo e($order->status ?? 'Pending'); ?></span>
        &nbsp; <?php echo e(__('pdf.date')); ?>: <?php echo e($order->created_at?->format('Y-m-d H:i')); ?>

    </p>

    <table class="card-row">
        <tr>
            <td>
                <strong><?php echo e(__('pdf.bill_to')); ?></strong><br>
                <?php echo e($order->user->name ?? $order->guestUser->name ?? __('pdf.guest')); ?><br>
                <?php echo e($order->user->email ?? $order->guestUser->email ?? '—'); ?>

            </td>
            <td>
                <strong><?php echo e(__('pdf.ship_to')); ?></strong><br>
                <?php if($order->address): ?>
                    <?php echo e($order->address->address_line1); ?><br>
                    <?php if($order->address->address_line2): ?><?php echo e($order->address->address_line2); ?><br><?php endif; ?>
                    <?php echo e($order->address->phone_number ?? '—'); ?><br>
                    <?php echo e($order->cities->name ?? $order->address->country ?? '—'); ?>

                <?php else: ?>
                    —
                <?php endif; ?>
            </td>
        </tr>
    </table>

    <h2 class="section"><?php echo e(__('pdf.line_items')); ?></h2>
    <table class="data">
        <thead>
            <tr>
                <th>#</th>
                <th><?php echo e(__('pdf.product')); ?></th>
                <th><?php echo e(__('pdf.size')); ?></th>
                <th><?php echo e(__('pdf.qty')); ?></th>
                <th><?php echo e(__('pdf.unit_price')); ?></th>
                <th><?php echo e(__('pdf.line_total')); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $order->orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $qty = max(1, (int) $item->quantity);
                    $lineTotal = (float) $item->price;
                    $unitPrice = round($lineTotal / $qty, 2);
                ?>
                <tr class="zebra">
                    <td><?php echo e($i + 1); ?></td>
                    <td><?php echo e($item->product->name ?? '—'); ?></td>
                    <td><?php echo e($item->size ?? '—'); ?></td>
                    <td><?php echo e($qty); ?></td>
                    <td><?php echo e(number_format($unitPrice, 2)); ?> <?php echo e(__('pdf.currency')); ?></td>
                    <td><?php echo e(number_format($lineTotal, 2)); ?> <?php echo e(__('pdf.currency')); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6"><?php echo e(__('pdf.no_items')); ?></td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <table class="summary" align="<?php echo e($branding['isRtl'] ? 'left' : 'right'); ?>">
        <tr><td><?php echo e(__('pdf.subtotal')); ?></td><td><?php echo e(number_format($totals['subtotal'], 2)); ?> <?php echo e(__('pdf.currency')); ?></td></tr>
        <?php if($totals['discountAmount'] > 0): ?>
            <tr>
                <td><?php echo e(__('pdf.discount')); ?> <?php if($order->discountCodes): ?>(<?php echo e($order->discountCodes->code); ?> <?php echo e($totals['discountPercent']); ?>%)<?php endif; ?></td>
                <td>-<?php echo e(number_format($totals['discountAmount'], 2)); ?> <?php echo e(__('pdf.currency')); ?></td>
            </tr>
        <?php endif; ?>
        <tr><td><?php echo e(__('pdf.delivery')); ?></td><td><?php echo e(number_format($totals['delivery'], 2)); ?> <?php echo e(__('pdf.currency')); ?></td></tr>
        <tr class="total"><td><?php echo e(__('pdf.grand_total')); ?></td><td><?php echo e(number_format($totals['grandTotal'], 2)); ?> <?php echo e(__('pdf.currency')); ?></td></tr>
    </table>

    <?php if($order->payments->isNotEmpty()): ?>
        <h2 class="section"><?php echo e(__('pdf.payment')); ?></h2>
        <table class="data">
            <thead>
                <tr>
                    <th><?php echo e(__('pdf.amount')); ?></th>
                    <th><?php echo e(__('pdf.date')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $order->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e(number_format($payment->amount ?? 0, 2)); ?> <?php echo e(__('pdf.currency')); ?></td>
                        <td><?php echo e($payment->created_at?->format('Y-m-d H:i')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php if(!empty($branding['invoiceNotes'])): ?>
        <h2 class="section"><?php echo e(__('invoice_sample.notes_section')); ?></h2>
        <div class="guide-body invoice-notes">
            <?php echo $branding['invoiceNotes']; ?>

        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('pdf.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\hayah\resources\views/pdf/invoice.blade.php ENDPATH**/ ?>