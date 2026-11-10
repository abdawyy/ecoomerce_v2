@if ($paginator->hasPages())
    <nav>
        <ul class="pagination justify-content-center">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link text-black" style="color: black;">&laquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link text-black" href="{{ $paginator->previousPageUrl() }}" rel="prev" style="color: black;">&laquo;</a>
                </li>
            @endif

            {{-- Mobile: four page numbers around the current page --}}
            @php
                $windowSize = 4;
                $lastPage = $paginator->lastPage();
                $currentPage = $paginator->currentPage();
                $windowStart = max(1, min($currentPage - 1, $lastPage - $windowSize + 1));
                $windowEnd = min($lastPage, $windowStart + $windowSize - 1);
            @endphp
            @for ($page = $windowStart; $page <= $windowEnd; $page++)
                @if ($page === $currentPage)
                    <li class="page-item active d-inline-flex d-md-none">
                        <span class="page-link text-black" style="color: black; background-color: #e9ecef; border-color: #dee2e6;">{{ $page }}</span>
                    </li>
                @else
                    <li class="page-item d-inline-flex d-md-none">
                        <a class="page-link text-black" href="{{ $paginator->url($page) }}" style="color: black;">{{ $page }}</a>
                    </li>
                @endif
            @endfor

            {{-- Desktop: full page list --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled d-none d-md-inline-flex">
                        <span class="page-link text-black" style="color: black;">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active d-none d-md-inline-flex">
                                <span class="page-link text-black" style="color: black; background-color: #e9ecef; border-color: #dee2e6;">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item d-none d-md-inline-flex">
                                <a class="page-link text-black" href="{{ $url }}" style="color: black;">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link text-black" href="{{ $paginator->nextPageUrl() }}" rel="next" style="color: black;">&raquo;</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link text-black" style="color: black;">&raquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
