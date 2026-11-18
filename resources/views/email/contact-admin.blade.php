@extends('email.layout')

@section('title', __('email.contact_admin_subject', ['name' => $name], 'en'))
@section('preheader', __('email.contact_admin_preheader', ['name' => $name], 'en'))

@section('content')
<p style="margin:0 0 16px;font-size:18px;color:#1b2740;"><strong>{{ __('email.contact_admin_title', [], 'en') }}</strong></p>
<p style="margin:0 0 6px;font-size:14px;line-height:1.6;color:#1b2740;"><strong>{{ __('email.contact_from', [], 'en') }}:</strong> {{ $name }}</p>
<p style="margin:0 0 6px;font-size:14px;line-height:1.6;color:#1b2740;"><strong>{{ __('email.contact_email', [], 'en') }}:</strong> {{ $email }}</p>
<p style="margin:0 0 16px;font-size:14px;line-height:1.6;color:#1b2740;"><strong>{{ __('email.contact_phone', [], 'en') }}:</strong> {{ $phone }}</p>
<p style="margin:0 0 6px;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#8a8176;">{{ __('email.contact_message', [], 'en') }}</p>
<p style="margin:0 0 18px;font-size:15px;line-height:1.7;color:#1b2740;white-space:pre-line;">{{ $body }}</p>
@endsection
