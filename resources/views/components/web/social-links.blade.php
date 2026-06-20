@props([
    'class' => 'd-flex gap-3 flex-wrap',
    'linkClass' => 'social-link',
])

@php $links = $branding->socialLinks(); @endphp

@if (count($links) > 0)
    <div {{ $attributes->merge(['class' => $class]) }}>
        @foreach ($links as $link)
            <a href="{{ $link['url'] }}"
               target="_blank"
               rel="noopener noreferrer"
               class="{{ $linkClass }}"
               aria-label="{{ $link['label'] }}"
               title="{{ $link['label'] }}">
                <i class="bi {{ $link['icon'] }}" aria-hidden="true"></i>
            </a>
        @endforeach
    </div>
@endif
