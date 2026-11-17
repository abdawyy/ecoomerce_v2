@props([
    'variant' => 'full',
    'tone' => 'paper',
    'anchor' => false,
])

@php
    $policyTitle = $branding->cartPolicyTitle();
    $paragraphs = $branding->cartPolicyParagraphs();
    $intro = null;
    $steps = [];
    $notes = [];
    foreach ($paragraphs as $line) {
        if (preg_match('/^\d+\.\s*(.+)$/u', $line, $match)) {
            $steps[] = $match[1];
        } elseif ($steps === [] && $intro === null) {
            $intro = $line;
        } else {
            $notes[] = $line;
        }
    }
@endphp

@if ($variant === 'compact')
    <p {{ $attributes->merge(['class' => 'return-policy-compact mb-0']) }}>
        {{ $intro ?: $policyTitle }}
        <a href="{{ route('legal') }}#returns">{{ __('web.returns_details') }}</a>
    </p>
@else
    <section {{ $attributes->merge(['class' => 'return-policy' . ($tone === 'inverse' ? ' is-inverse' : '')]) }} @if($anchor) id="returns" @endif>
        <h2 class="return-policy-title">{{ $policyTitle }}</h2>
        @if ($intro)
            <p class="return-policy-intro">{{ $intro }}</p>
        @endif
        @if ($steps !== [])
            <ol class="return-policy-steps">
                @foreach ($steps as $step)
                    <li>{{ $step }}</li>
                @endforeach
            </ol>
        @endif
        @foreach ($notes as $note)
            <p class="return-policy-note">{{ $note }}</p>
        @endforeach
    </section>
@endif
