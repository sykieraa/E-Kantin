@if ($paginator->hasPages())
    <nav class="flex items-center justify-between gap-3" role="navigation" aria-label="Navigasi halaman">
        @if ($paginator->onFirstPage())
            <span class="btn-secondary cursor-not-allowed opacity-50">Sebelumnya</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="btn-secondary" rel="prev">Sebelumnya</a>
        @endif

        <p class="text-sm text-gray-500">
            Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}
        </p>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="btn-secondary" rel="next">Berikutnya</a>
        @else
            <span class="btn-secondary cursor-not-allowed opacity-50">Berikutnya</span>
        @endif
    </nav>
@endif
