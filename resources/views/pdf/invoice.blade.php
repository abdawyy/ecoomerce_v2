@extends('pdf.layout')

@section('pdf-title', __('pdf.invoice').' #'.$order->id)

@section('content')
    <h1 class="doc-title">{{ __('pdf.invoice') }} #{{ $order->id }}</h1>
    <p>
        @if (!empty($isSamplePreview))
            <span class="badge" style="background:#6c757d">{{ __('invoice_sample.sample_badge') }}</span>
            &nbsp;
        @endif
        <span class="badge">{{ $order->status ?? 'Pending' }}</span>
        &nbsp; {{ __('pdf.date') }}: {{ $order->created_at?->format('Y-m-d H:i') }}
    </p>

    <table class="card-row">
        <tr>
            <td>
                <strong>{{ __('pdf.bill_to') }}</strong><br>
                {{ $order->user->name ?? $order->guestUser->name ?? __('pdf.guest') }}<br>
                {{ $order->user->email ?? $order->guestUser->email ?? '—' }}
            </td>
            <td>
                <strong>{{ __('pdf.ship_to') }}</strong><br>
                @if ($order->address)
                    {{ $order->address->address_line1 }}<br>
                    @if ($order->address->address_line2){{ $order->address->address_line2 }}<br>@endif
                    {{ $order->address->phone_number ?? '—' }}<br>
                    {{ $order->cities->name ?? $order->address->country ?? '—' }}
                @else
                    —
                @endif
            </td>
        </tr>
    </table>

    <h2 class="section">{{ __('pdf.line_items') }}</h2>
    <table class="data">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('pdf.product') }}</th>
                <th>{{ __('pdf.size') }}</th>
                <th>{{ __('pdf.qty') }}</th>
                <th>{{ __('pdf.unit_price') }}</th>
                <th>{{ __('pdf.line_total') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($order->orderItems as $i => $item)
                @php
                    $qty = max(1, (int) $item->quantity);
                    $lineTotal = (float) $item->price;
                    $unitPrice = round($lineTotal / $qty, 2);
                @endphp
                <tr class="zebra">
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->product->name ?? '—' }}</td>
                    <td>{{ $item->size ?? '—' }}</td>
                    <td>{{ $qty }}</td>
                    <td>{{ number_format($unitPrice, 2) }} {{ __('pdf.currency') }}</td>
                    <td>{{ number_format($lineTotal, 2) }} {{ __('pdf.currency') }}</td>
                </tr>
            @empty
                <tr><td colspan="6">{{ __('pdf.no_items') }}</td></tr>
            @endforelse
        </tbody>
    </table>

    <table class="summary" align="{{ $branding['isRtl'] ? 'left' : 'right' }}">
        <tr><td>{{ __('pdf.subtotal') }}</td><td>{{ number_format($totals['subtotal'], 2) }} {{ __('pdf.currency') }}</td></tr>
        @if ($totals['discountAmount'] > 0)
            <tr>
                <td>{{ __('pdf.discount') }} @if($order->discountCodes)({{ $order->discountCodes->code }} {{ $totals['discountPercent'] }}%)@endif</td>
                <td>-{{ number_format($totals['discountAmount'], 2) }} {{ __('pdf.currency') }}</td>
            </tr>
        @endif
        <tr><td>{{ __('pdf.delivery') }}</td><td>{{ number_format($totals['delivery'], 2) }} {{ __('pdf.currency') }}</td></tr>
        <tr class="total"><td>{{ __('pdf.grand_total') }}</td><td>{{ number_format($totals['grandTotal'], 2) }} {{ __('pdf.currency') }}</td></tr>
    </table>

    @if ($order->payments->isNotEmpty())
        <h2 class="section">{{ __('pdf.payment') }}</h2>
        <table class="data">
            <thead>
                <tr>
                    <th>{{ __('pdf.amount') }}</th>
                    <th>{{ __('pdf.date') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->payments as $payment)
                    <tr>
                        <td>{{ number_format($payment->amount ?? 0, 2) }} {{ __('pdf.currency') }}</td>
                        <td>{{ $payment->created_at?->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if (!empty($branding['invoiceNotes']))
        <h2 class="section">{{ __('invoice_sample.notes_section') }}</h2>
        <div class="guide-body invoice-notes">
            {!! $branding['invoiceNotes'] !!}
        </div>
    @endif
@endsection
