@if ($paginator->hasPages())
    @php
        $windowSize = 4;
        $lastPage = $paginator->lastPage();
        $currentPage = $paginator->currentPage();
        $windowStart = max(1, min($currentPage - 1, $lastPage - $windowSize + 1));
        $windowEnd = min($lastPage, $windowStart + $windowSize - 1);
        $linkStyle = 'color: black;';
        $activeStyle = 'color: black; background-color: #e9ecef; border-color: #dee2e6;';
    @endphp
    <nav>
        <ul class="pagination justify-content-center">
            {{-- First Page --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link text-black" style="{{ $linkStyle }}">&laquo;&laquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link text-black" href="{{ $paginator->url(1) }}" style="{{ $linkStyle }}">&laquo;&laquo;</a>
                </li>
            @endif

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link text-black" style="{{ $linkStyle }}">&laquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link text-black" href="{{ $paginator->previousPageUrl() }}" rel="prev" style="{{ $linkStyle }}">&laquo;</a>
                </li>
            @endif

            {{-- Mobile: four page numbers around the current page --}}
            @for ($page = $windowStart; $page <= $windowEnd; $page++)
                @if ($page === $currentPage)
                    <li class="page-item active d-inline-flex d-md-none">
                        <span class="page-link text-black" style="{{ $activeStyle }}">{{ $page }}</span>
                    </li>
                @else
                    <li class="page-item d-inline-flex d-md-none">
                        <a class="page-link text-black" href="{{ $paginator->url($page) }}" style="{{ $linkStyle }}">{{ $page }}</a>
                    </li>
                @endif
            @endfor

            {{-- Desktop: full page list --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled d-none d-md-inline-flex">
                        <span class="page-link text-black" style="{{ $linkStyle }}">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active d-none d-md-inline-flex">
                                <span class="page-link text-black" style="{{ $activeStyle }}">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item d-none d-md-inline-flex">
                                <a class="page-link text-black" href="{{ $url }}" style="{{ $linkStyle }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link text-black" href="{{ $paginator->nextPageUrl() }}" rel="next" style="{{ $linkStyle }}">&raquo;</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link text-black" style="{{ $linkStyle }}">&raquo;</span>
                </li>
            @endif

            {{-- Last Page --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link text-black" href="{{ $paginator->url($lastPage) }}" style="{{ $linkStyle }}">&raquo;&raquo;</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link text-black" style="{{ $linkStyle }}">&raquo;&raquo;</span>
                </li>
            @endif
        </ul>
        <p class="pagination-status text-center small mt-2 mb-0">{{ $currentPage }} / {{ $lastPage }}</p>
    </nav>
@endif
