@if ($paginator->hasPages())
    <nav aria-label="Sayfalama">
        <ul class="pagination justify-content-center">
            {{-- Önceki Sayfa Linki --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">&laquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="javascript:void(0)" data-page="{{ $paginator->currentPage() - 1 }}" rel="prev">&laquo;</a>
                </li>
            @endif

            {{-- Sayfa Numaraları --}}
            @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                @if ($page == $paginator->currentPage())
                    <li class="page-item active">
                        <span class="page-link">{{ $page }}</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="javascript:void(0)" data-page="{{ $page }}">{{ $page }}</a>
                    </li>
                @endif
            @endforeach

            {{-- Sonraki Sayfa Linki --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="javascript:void(0)" data-page="{{ $paginator->currentPage() + 1 }}" rel="next">&raquo;</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">&raquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif 