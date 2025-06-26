@if ($paginator->hasPages())
    <nav aria-label="Sayfalama">
        <ul class="pagination justify-content-center">
            {{-- İlk Sayfa Linki --}}
            @if ($paginator->currentPage() > 1)
                <li class="page-item">
                    <a class="page-link" href="javascript:void(0)" data-page="1" title="İlk Sayfa">&laquo;&laquo;</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">&laquo;&laquo;</span>
                </li>
            @endif

            {{-- Önceki Sayfa Linki --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">&laquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="javascript:void(0)" data-page="{{ $paginator->currentPage() - 1 }}" rel="prev" title="Önceki Sayfa">&laquo;</a>
                </li>
            @endif

            {{-- Sayfa Numaraları (Akıllı Sayfalama - Maksimum 10 sayfa göster) --}}
            @php
                $start = max(1, $paginator->currentPage() - 5);
                $end = min($paginator->lastPage(), $start + 9);
                
                // Eğer sondan başladıysak, başlangıcı ayarla
                if ($end - $start < 9) {
                    $start = max(1, $end - 9);
                }
            @endphp

            {{-- Başlangıç noktaları --}}
            @if ($start > 1)
                <li class="page-item">
                    <a class="page-link" href="javascript:void(0)" data-page="1">1</a>
                </li>
                @if ($start > 2)
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                @endif
            @endif

            {{-- Ana sayfa numaraları --}}
            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $paginator->currentPage())
                    <li class="page-item active">
                        <span class="page-link">{{ $page }}</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="javascript:void(0)" data-page="{{ $page }}">{{ $page }}</a>
                    </li>
                @endif
            @endfor

            {{-- Son sayfa noktaları --}}
            @if ($end < $paginator->lastPage())
                @if ($end < $paginator->lastPage() - 1)
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                @endif
                <li class="page-item">
                    <a class="page-link" href="javascript:void(0)" data-page="{{ $paginator->lastPage() }}">{{ $paginator->lastPage() }}</a>
                </li>
            @endif

            {{-- Sonraki Sayfa Linki --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="javascript:void(0)" data-page="{{ $paginator->currentPage() + 1 }}" rel="next" title="Sonraki Sayfa">&raquo;</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">&raquo;</span>
                </li>
            @endif

            {{-- Son Sayfa Linki --}}
            @if ($paginator->currentPage() < $paginator->lastPage())
                <li class="page-item">
                    <a class="page-link" href="javascript:void(0)" data-page="{{ $paginator->lastPage() }}" title="Son Sayfa">&raquo;&raquo;</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">&raquo;&raquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif 