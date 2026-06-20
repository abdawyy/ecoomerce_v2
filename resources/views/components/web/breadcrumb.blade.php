@props(['items' => []])

@if (count($items) > 0)
    <nav aria-label="breadcrumb" class="storefront-breadcrumb mb-3">
        <ol class="breadcrumb mb-0 small">
            @foreach ($items as $item)
                @if ($loop->last)
                    <li class="breadcrumb-item active" aria-current="page">{{ $item['label'] }}</li>
                @else
                    <li class="breadcrumb-item">
                        <a href="{{ $item['url'] }}" class="text-decoration-none">{{ $item['label'] }}</a>
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
