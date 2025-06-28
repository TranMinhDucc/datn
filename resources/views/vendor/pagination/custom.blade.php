@if ($paginator->hasPages())
    <ul class="pagination">
        {{-- Previous Page Link --}}
        <li>
            @if ($paginator->onFirstPage())
                <a class="prev disabled"><i class="iconsax" data-icon="chevron-left"></i></a>
            @else
                <a class="prev" href="{{ $paginator->previousPageUrl() }}"><i class="iconsax" data-icon="chevron-left"></i></a>
            @endif
        </li>

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- Dấu "..." --}}
            @if (is_string($element))
                <li><span class="disabled">{{ $element }}</span></li>
            @endif

            {{-- Các trang --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li><a class="active" href="#">{{ $page }}</a></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        <li>
            @if ($paginator->hasMorePages())
                <a class="next" href="{{ $paginator->nextPageUrl() }}"><i class="iconsax" data-icon="chevron-right"></i></a>
            @else
                <a class="next disabled"><i class="iconsax" data-icon="chevron-right"></i></a>
            @endif
        </li>
    </ul>
@endif