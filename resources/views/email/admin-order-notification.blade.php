@extends('email.layout')

@section('title', __('email.admin_order_subject', ['id' => $order->id], 'en'))
@section('preheader', __('email.admin_order_preheader', ['id' => $order->id, 'name' => $order->user->name ?? $order->guestUser->name ?? 'Guest'], 'en'))

@section('content')
@php
    $name = $order->user->name ?? $order->guestUser->name ?? 'Guest';
    $email = $order->user->email ?? $order->guestUser->email ?? null;
    $address = $order->address;
    $city = $order->cities->name ?? null;
    $lines = array_filter([
        $address?->address_line1,
        $address?->address_line2,
        $city,
        $address?->country,
    ]);
    $money = fn ($amount) => number_format((float) $amount, 2).' LE';
@endphp
<p style="margin:0 0 6px;font-size:13px;letter-spacing:0.08em;text-transform:uppercase;color:#8a8176;">{{ __('email.admin_order_title', [], 'en') }}</p>
<p style="margin:0 0 18px;font-size:20px;color:#1b2740;"><strong>{{ __('email.order_number', ['id' => $order->id], 'en') }}</strong></p>

<p style="margin:0 0 4px;font-size:14px;line-height:1.6;color:#1b2740;"><strong>{{ $name }}</strong></p>
@if ($email)
    <p style="margin:0 0 4px;font-size:14px;color:#4a453f;">{{ $email }}</p>
@endif
@if ($address?->phone_number)
    <p style="margin:0 0 4px;font-size:14px;color:#4a453f;">{{ $address->phone_number }}</p>
@endif
@if ($lines)
    <p style="margin:0 0 20px;font-size:14px;line-height:1.6;color:#4a453f;">{{ implode(', ', $lines) }}</p>
@endif

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin:0 0 16px;">
    @foreach ($order->orderItems as $item)
        <tr>
            <td align="left" style="padding:10px 0;border-bottom:1px solid #efe8dc;font-family:Tahoma,Arial,sans-serif;font-size:14px;color:#1b2740;">
                <strong>{{ $item->product->name ?? '—' }}</strong><br>
                <span style="color:#6b645b;">{{ $item->size ?: '—' }} · {{ $item->quantity ?? 0 }}</span>
            </td>
            <td align="right" valign="top" style="padding:10px 0;border-bottom:1px solid #efe8dc;font-family:Tahoma,Arial,sans-serif;font-size:14px;color:#1b2740;white-space:nowrap;">
                {{ $money($item->price) }}
            </td>
        </tr>
    @endforeach
    <tr>
        <td align="left" style="padding:14px 0 0;font-family:Tahoma,Arial,sans-serif;font-size:16px;color:#1b2740;"><strong>Total</strong></td>
        <td align="right" style="padding:14px 0 0;font-family:Tahoma,Arial,sans-serif;font-size:16px;color:#1b2740;"><strong>{{ $money($order->total_amount) }}</strong></td>
    </tr>
</table>

@if (!empty($invoiceUrl))
    <table role="presentation" cellpadding="0" cellspacing="0" style="margin:8px 0 12px;">
        <tr>
            <td align="center" bgcolor="#1b2740" style="background:#1b2740;">
                <a href="{{ $invoiceUrl }}" style="display:inline-block;padding:12px 22px;font-family:Tahoma,Arial,sans-serif;font-size:14px;color:#ffffff;text-decoration:none;">{{ __('email.invoice', [], 'en') }}</a>
            </td>
        </tr>
    </table>
@endif
@endsection
