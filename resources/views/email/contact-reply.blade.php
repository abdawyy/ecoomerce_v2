@extends('email.layout')

@section('title', __('email.contact_reply_subject', ['site' => $siteName]))
@section('preheader', __('email.contact_reply_preheader', ['site' => $siteName]))

@section('content')
<p style="margin:0 0 8px;font-size:16px;line-height:1.6;color:#1b2740;">{{ __('email.greeting', ['name' => $name]) }}</p>
<p style="margin:0 0 22px;font-size:15px;line-height:1.7;color:#4a453f;">{{ __('email.contact_reply_body') }}</p>
<p style="margin:0 0 12px;font-size:15px;color:#1b2740;">{{ $siteName }}</p>
@endsection
