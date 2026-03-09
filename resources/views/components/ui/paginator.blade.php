@props(['paginator'])

@if ($paginator instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $paginator->hasPages())
    @php
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();
        $rangeStart = max(1, min($currentPage - 2, $lastPage - 4));
        $rangeEnd = min($lastPage, max($currentPage + 2, min(5, $lastPage)));
    @endphp
    <nav class="flex items-center gap-1" aria-label="Pagination" role="navigation">
        <button class="btn btn-sm" type="button" wire:click="previousPage"
            @if ($paginator->onFirstPage()) disabled aria-disabled="true" @endif aria-label="Previous page">
            <x-heroicon-o-chevron-left class="size-3.5" />
        </button>

        <div class="flex items-center gap-1">
            @for ($page = $rangeStart; $page <= $rangeEnd; $page++)
                <button class="btn btn-sm {{ $page === $currentPage ? 'active' : '' }}" type="button"
                    wire:key="pagination-page-{{ $page }}" wire:click="gotoPage({{ $page }})"
                    @if ($page === $currentPage) aria-current="page" @endif>
                    {{ $page }}
                </button>
            @endfor
        </div>

        <button class="btn btn-sm" type="button" wire:click="nextPage"
            @if ($currentPage === $lastPage) disabled aria-disabled="true" @endif aria-label="Next page">
            <x-heroicon-o-chevron-right class="size-3.5" />
        </button>
    </nav>
@endif
