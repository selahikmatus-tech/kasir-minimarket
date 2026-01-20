@if ($paginator->hasPages())
    <nav>
        <ul class="pagination justify-content-center">
            @php
                // Tampilkan hanya 3 angka halaman
                $currentPage = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
                $startPage = max(1, $currentPage - 1);
                $endPage = min($lastPage, $startPage + 2);
                
                if ($endPage - $startPage + 1 < 3) {
                    $startPage = max(1, $endPage - 2);
                }
            @endphp
            
            @for ($i = $startPage; $i <= $endPage; $i++)
                <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                    <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                </li>
            @endfor
        </ul>
    </nav>
@endif