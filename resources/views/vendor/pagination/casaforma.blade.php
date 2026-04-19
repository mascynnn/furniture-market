@if ($paginator->hasPages())
<nav class="pagination">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <span class="page-item" style="opacity:0.3">
            <span>←</span>
        </span>
    @else
        <span class="page-item">
            <a href="{{ $paginator->previousPageUrl() }}">←</a>
        </span>
    @endif

    {{-- Pages --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="page-item"><span>{{ $element }}</span></span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="page-item active"><span>{{ $page }}</span></span>
                @else
                    <span class="page-item"><a href="{{ $url }}">{{ $page }}</a></span>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <span class="page-item">
            <a href="{{ $paginator->nextPageUrl() }}">→</a>
        </span>
    @else
        <span class="page-item" style="opacity:0.3">
            <span>→</span>
        </span>
    @endif
</nav>
@endif
