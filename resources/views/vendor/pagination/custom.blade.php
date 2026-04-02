@if ($paginator->hasPages())
<nav class="custom-pagination">
    <ul class="pagination-list">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled"><span class="page-link">‹ Trước</span></li>
        @else
            <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}">‹ Trước</a></li>
        @endif

        {{-- Page Numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}">Sau ›</a></li>
        @else
            <li class="page-item disabled"><span class="page-link">Sau ›</span></li>
        @endif
    </ul>
    <p class="pagination-info">Hiển thị {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} / {{ $paginator->total() }} bài viết</p>
</nav>
@endif
