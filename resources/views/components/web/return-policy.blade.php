@props([
    'variant' => 'full',
    'tone' => 'paper',
    'anchor' => false,
])

@if ($variant === 'compact')
    <p {{ $attributes->merge(['class' => 'return-policy-compact mb-0']) }}>
        {{ __('web.returns_compact') }}
        <a href="{{ route('legal') }}#returns">{{ __('web.returns_details') }}</a>
    </p>
@else
    <section {{ $attributes->merge(['class' => 'return-policy' . ($tone === 'inverse' ? ' is-inverse' : '')]) }} @if($anchor) id="returns" @endif>
        <h2 class="return-policy-title">{{ __('web.returns_title') }}</h2>
        <p class="return-policy-intro">{{ __('web.returns_intro') }}</p>
        <ol class="return-policy-steps">
            <li>{{ __('web.returns_step_1') }}</li>
            <li>{{ __('web.returns_step_2') }}</li>
            <li>{{ __('web.returns_step_3') }}</li>
        </ol>
        <p class="return-policy-note">{{ __('web.returns_note') }}</p>
    </section>
@endif
