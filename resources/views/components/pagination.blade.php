<nav>
    <ul class="pagination justify-content-end">
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link disabled" aria-hidden="true">&laquo;</span>
            </li>
        @else
            <li class="page-item">
                <a href="{{ $paginator->url(1) }}" class="page-link text-accent">&laquo;</a>
            </li>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="page-item disabled">
                    <a class="page-link active border-accent" href="#">{{ $element }}</a>
                </li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item">
                            <a class="page-link active border-accent" href="#">{{ $page }}</a>
                        </li>
                    @elseif ($page >= $paginator->currentPage() - 3 && $page <= $paginator->currentPage() + 3)
                        <li class="page-item">
                            <a class="page-link text-accent" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a href="{{ $paginator->url($paginator->lastPage()) }}" class="page-link text-accent">&raquo;</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link disabled">&raquo;</span>
            </li>
        @endif
    </ul>
</nav>
