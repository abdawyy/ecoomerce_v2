@extends('email.layout')

@section('title', __('email.order_number', ['id' => $order->id]))
@section('preheader', __('email.status_preheader', [
    'id' => $order->id,
    'status' => $statusLabel,
]))

@section('content')
@php
    $name = $order->user->name ?? $order->guestUser->name ?? __('email.customer');
@endphp
<p style="margin:0 0 8px;font-size:16px;line-height:1.6;color:#1b2740;">{{ __('email.greeting', ['name' => $name]) }}</p>
<p style="margin:0 0 22px;font-size:15px;line-height:1.7;color:#4a453f;">{{ __('email.status_body', ['status' => $statusLabel]) }}</p>

<p style="margin:0;font-size:18px;letter-spacing:0.02em;color:#1b2740;"><strong>{{ __('email.order_number', ['id' => $order->id]) }}</strong></p>
<p style="margin:4px 0 0;font-size:13px;color:#6b645b;">
    {{ __('email.status') }}: {{ $statusLabel }}
</p>
@endsection
