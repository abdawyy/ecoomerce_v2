@extends('email.layout')

@section('title', __('email.review_subject', ['product' => $productName], 'en'))
@section('preheader', __('email.review_preheader', ['name' => $userName, 'product' => $productName], 'en'))

@section('content')
<p style="margin:0 0 16px;font-size:18px;color:#1b2740;"><strong>{{ __('email.review_title', [], 'en') }}</strong></p>
<p style="margin:0 0 6px;font-size:14px;line-height:1.6;color:#1b2740;"><strong>{{ __('email.review_product', [], 'en') }}:</strong> {{ $productName }}</p>
<p style="margin:0 0 6px;font-size:14px;line-height:1.6;color:#1b2740;"><strong>{{ __('email.review_by', [], 'en') }}:</strong> {{ $userName }} ({{ $userEmail }})</p>
<p style="margin:0 0 16px;font-size:14px;line-height:1.6;color:#1b2740;"><strong>{{ __('email.review_rating', [], 'en') }}:</strong> {{ $rating }}/5</p>
<p style="margin:0 0 6px;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#8a8176;">{{ __('email.review_comment', [], 'en') }}</p>
<p style="margin:0 0 18px;font-size:15px;line-height:1.7;color:#1b2740;white-space:pre-line;">{{ $comment }}</p>
@endsection
