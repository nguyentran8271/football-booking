@if ($paginator->hasPages())
<nav class="custom-pagination">
    <ul class="pagination-list">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled"><span class="page-link">‹ Trước</span></li>
        @else
            <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}">‹ Trước</a></li>
        @endif

        {{-- Page Numbers with ellipsis --}}
        @php
            $current = $paginator->currentPage();
            $last    = $paginator->lastPage();
            $pages   = [];
            // always show first 3
            for ($i = 1; $i <= min(3, $last); $i++) $pages[] = $i;
            // window around current
            for ($i = max(4, $current - 1); $i <= min($last - 2, $current + 1); $i++) $pages[] = $i;
            // always show last 2
            for ($i = max($last - 1, 4); $i <= $last; $i++) $pages[] = $i;
            $pages = array_unique($pages);
            sort($pages);
        @endphp

        @php $prev = null; @endphp
        @foreach ($pages as $page)
            @if ($prev !== null && $page - $prev > 1)
                <li class="page-item disabled"><span class="page-link">…</span></li>
            @endif
            @if ($page == $current)
                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a></li>
            @endif
            @php $prev = $page; @endphp
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}">Sau ›</a></li>
        @else
            <li class="page-item disabled"><span class="page-link">Sau ›</span></li>
        @endif
    </ul>
    @if($paginator->total())
    <p class="pagination-info">Hiển thị {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} / {{ $paginator->total() }}</p>
    @endif
</nav>
@endif
