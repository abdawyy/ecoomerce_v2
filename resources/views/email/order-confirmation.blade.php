@extends('email.layout')

@section('title', __('email.order_number', ['id' => $order->id]))
@section('preheader', __('email.order_preheader', ['id' => $order->id]))

@section('content')
@php
    $align = app()->getLocale() === 'ar' ? 'right' : 'left';
    $end = $align === 'right' ? 'left' : 'right';
    $name = $order->user->name ?? $order->guestUser->name ?? __('email.customer');
    $address = $order->address;
    $city = $order->cities->name ?? null;
    $lines = array_filter([
        $address?->address_line1,
        $address?->address_line2,
        $city,
        $address?->country,
    ]);
    $itemsTotal = $order->orderItems->sum(fn ($item) => (float) $item->price);
    $delivery = (float) $order->resolvedDeliveryFee();
    $money = fn ($amount) => __('email.money', ['amount' => number_format((float) $amount, 2)]);
    $sep = app()->getLocale() === 'ar' ? '، ' : ', ';
    $statusKey = 'email.status_'.($order->status ?: 'Pending');
    $statusLabel = __($statusKey) === $statusKey ? ($order->status ?: 'Pending') : __($statusKey);
@endphp
<p style="margin:0 0 8px;font-size:16px;line-height:1.6;color:#1b2740;">{{ __('email.greeting', ['name' => $name]) }}</p>
<p style="margin:0 0 22px;font-size:15px;line-height:1.7;color:#4a453f;">{{ __('email.order_thanks') }}</p>

<p style="margin:0;font-size:18px;letter-spacing:0.02em;color:#1b2740;"><strong>{{ __('email.order_number', ['id' => $order->id]) }}</strong></p>
<p style="margin:4px 0 22px;font-size:13px;color:#6b645b;">
    {{ __('email.placed', ['date' => optional($order->created_at)->format('Y-m-d H:i')]) }}
    · {{ __('email.status') }}: {{ $statusLabel }}
</p>

<p style="margin:0 0 8px;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#8a8176;">{{ __('email.items') }}</p>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin:0 0 18px;">
    @foreach ($order->orderItems as $item)
        <tr>
            <td align="{{ $align }}" style="padding:12px 0;border-bottom:1px solid #efe8dc;font-family:Tahoma,Arial,sans-serif;font-size:14px;line-height:1.5;color:#1b2740;">
                <strong>{{ $item->product->name ?? '—' }}</strong><br>
                <span style="color:#6b645b;">{{ __('email.size_qty', ['size' => $item->size ?: '—', 'qty' => $item->quantity ?? 0]) }}</span>
            </td>
            <td align="{{ $end }}" valign="top" style="padding:12px 0 12px 12px;border-bottom:1px solid #efe8dc;font-family:Tahoma,Arial,sans-serif;font-size:14px;color:#1b2740;white-space:nowrap;">
                {{ $money($item->price) }}
            </td>
        </tr>
    @endforeach
</table>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin:0 0 22px;">
    <tr>
        <td align="{{ $align }}" style="padding:4px 0;font-family:Tahoma,Arial,sans-serif;font-size:14px;color:#6b645b;">{{ __('email.subtotal') }}</td>
        <td align="{{ $end }}" style="padding:4px 0;font-family:Tahoma,Arial,sans-serif;font-size:14px;color:#1b2740;">{{ $money($itemsTotal) }}</td>
    </tr>
    <tr>
        <td align="{{ $align }}" style="padding:4px 0;font-family:Tahoma,Arial,sans-serif;font-size:14px;color:#6b645b;">{{ __('email.delivery') }}</td>
        <td align="{{ $end }}" style="padding:4px 0;font-family:Tahoma,Arial,sans-serif;font-size:14px;color:#1b2740;">{{ $money($delivery) }}</td>
    </tr>
    @if ($order->discountCodes)
        <tr>
            <td align="{{ $align }}" style="padding:4px 0;font-family:Tahoma,Arial,sans-serif;font-size:14px;color:#6b645b;">{{ __('email.discount') }}</td>
            <td align="{{ $end }}" style="padding:4px 0;font-family:Tahoma,Arial,sans-serif;font-size:14px;color:#1b2740;">{{ $order->discountCodes->code }}</td>
        </tr>
    @endif
    <tr>
        <td align="{{ $align }}" style="padding:12px 0 0;border-top:1px solid #1b2740;font-family:Tahoma,Arial,sans-serif;font-size:16px;color:#1b2740;"><strong>{{ __('email.total') }}</strong></td>
        <td align="{{ $end }}" style="padding:12px 0 0;border-top:1px solid #1b2740;font-family:Tahoma,Arial,sans-serif;font-size:16px;color:#1b2740;"><strong>{{ $money($order->total_amount) }}</strong></td>
    </tr>
</table>

@if ($lines || ($address->phone_number ?? null))
    <p style="margin:0 0 6px;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#8a8176;">{{ __('email.address') }}</p>
    <p style="margin:0 0 22px;font-size:14px;line-height:1.7;color:#1b2740;">
        {{ $name }}<br>
        {{ implode($sep, $lines) }}
        @if ($address?->phone_number)
            <br>{{ __('email.phone') }}: {{ $address->phone_number }}
        @endif
    </p>
@endif

@if (!empty($invoiceUrl))
    <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 0 12px;">
        <tr>
            <td align="center" bgcolor="#1b2740" style="background:#1b2740;">
                <a href="{{ $invoiceUrl }}" style="display:inline-block;padding:12px 22px;font-family:Tahoma,Arial,sans-serif;font-size:14px;color:#ffffff;text-decoration:none;">{{ __('email.invoice') }}</a>
            </td>
        </tr>
    </table>
@endif
@endsection
