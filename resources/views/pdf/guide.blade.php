@extends('pdf.layout')

@section('pdf-title', $title)

@section('content')
    <h1 class="doc-title">{{ $title }}</h1>
    <div class="guide-body">
        {!! $bodyHtml !!}
    </div>
@endsection
