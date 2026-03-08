@section('hero')
    <div
        class="mb-6 inline-block rounded border border-primary/20 bg-primary/5 px-4 py-1 text-[10px] font-bold uppercase tracking-[0.3em] text-primary">
        Event Archive // Access Protocol
    </div>
    <h1 class="mb-6 text-5xl font-black uppercase leading-tight tracking-tighter text-white sm:text-6xl lg:text-7xl">
        Eventi <span class="text-glow text-primary">Community</span>
    </h1>
    <p class="mx-auto max-w-2xl text-lg font-light leading-relaxed text-white/60">
        Meetup, workshop e momenti di confronto della community. Esplora tutti gli eventi pubblicati.
    </p>
@endsection

<div class="relative flex min-h-screen flex-col">
    <main class="flex-1">
        <section class="scroll-mt-11 px-5 py-8 xs:px-8 md:p-10">

            <div class="mb-5">
                <h2 class="heading-5">All Events</h2>
                <p class="text-sm text-mist-400">
                    Browse the full collection of community and official events
                </p>
            </div>

            <x-sections.search.filters wire-search="search" :tags="['action', 'analytics', 'developer-tool', 'forms', 'icon-set', 'tables', 'theme', 'widget']" :tag-labels="[
                'action' => 'Action',
                'analytics' => 'Analytics',
                'developer-tool' => 'Developer Tool',
                'forms' => 'Forms',
                'icon-set' => 'Icon Set',
                'tables' => 'Tables',
                'theme' => 'Theme',
                'widget' => 'Widget',
            ]" />

            <div class="custom-top-dashed-border mt-5 flex flex-wrap items-center justify-between gap-4 pt-5">

                <div class="flex flex-wrap items-center gap-4">

                    <div class="flex items-center gap-1 text-xs">
                        <span>Sort by:</span>
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false"
                            @keydown.escape.window="open = false">
                            <label for="sort-select" class="sr-only">
                                Sort articles by
                            </label>
                            <button id="sort-select" type="button" @click="open = !open" :aria-expanded="open"
                                aria-haspopup="listbox" class="btn btn-sm group" aria-expanded="false">
                                <span
                                    x-text="({ @foreach ($sortOptions as $value => $label) '{{ $value }}': '{{ $label }}', @endforeach })[$wire.sortBy]">{{ $sortOptions[$sortBy] }}</span>
                                <div class="transition-transform duration-300 ease-out" :class="open && 'rotate-180'"
                                    aria-hidden="true">
                                    <x-heroicon-o-chevron-down class="size-3.5" />
                                </div>
                            </button>

                            <div x-show="open" x-transition:enter="transition duration-300 ease-out"
                                x-transition:enter-start="-translate-y-1 opacity-0"
                                x-transition:enter-end="translate-y-0 opacity-100"
                                x-transition:leave="transition duration-150 ease-in"
                                x-transition:leave-start="translate-y-0 opacity-100"
                                x-transition:leave-end="-translate-y-1 opacity-0"
                                class="absolute top-full left-0 z-20 mt-1 min-w-35 overflow-hidden rounded-xs border border-black/20 bg-surface-950 shadow-lg"
                                role="listbox" aria-label="Sort options" style="display: none;">
                                @foreach ($sortOptions as $value => $label)
                                    <button type="button" role="option"
                                        :aria-selected="$wire.sortBy === '{{ $value }}'"
                                        @click="$wire.set('sortBy', '{{ $value }}'); open = false"
                                        class="flex w-full items-center gap-2 px-3 py-2 text-left transition-colors duration-150 hover:bg-surface-900/70"
                                        :class="$wire.sortBy === '{{ $value }}' &&
                                            'bg-surface-900 font-medium text-mist-200'">
                                        <span class="size-3.5"
                                            :class="$wire.sortBy === '{{ $value }}' ? 'opacity-100' : 'opacity-0'">
                                            <x-heroicon-o-check class="size-3.5" />
                                        </span>
                                        {{ $label }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>


                    @php
                        $itemCount =
                            $events instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator
                                ? $events->total()
                                : $events->count();
                    @endphp
                    <div class="flex items-center gap-1 text-xs">
                        <span aria-hidden="true">Events found:</span>
                        <span class="item min-w-9 font-medium" aria-label="{{ $itemCount }} events found"
                            role="status">
                            {{ $itemCount }}
                        </span>
                    </div>
                </div>


                @if ($events instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $events->hasPages())
                    @php
                        $currentPage = $events->currentPage();
                        $lastPage = $events->lastPage();
                        $rangeStart = max(1, min($currentPage - 2, $lastPage - 4));
                        $rangeEnd = min($lastPage, max($currentPage + 2, min(5, $lastPage)));
                    @endphp
                    <nav class="flex items-center gap-1" aria-label="Pagination" role="navigation">
                        <button class="btn btn-sm" type="button" wire:click="previousPage"
                            @if ($events->onFirstPage()) disabled aria-disabled="true" @endif
                            aria-label="Previous page">
                            <x-heroicon-o-chevron-left class="size-3.5" />
                        </button>

                        <div class="flex items-center gap-1">
                            @for ($page = $rangeStart; $page <= $rangeEnd; $page++)
                                <button class="btn btn-sm {{ $page === $currentPage ? 'active' : '' }}" type="button"
                                    wire:key="pagination-page-{{ $page }}"
                                    wire:click="gotoPage({{ $page }})"
                                    @if ($page === $currentPage) aria-current="page" @endif>
                                    {{ $page }}
                                </button>
                            @endfor
                        </div>

                        <button class="btn btn-sm" type="button" wire:click="nextPage"
                            @if ($currentPage === $lastPage) disabled aria-disabled="true" @endif
                            aria-label="Next page">
                            <x-heroicon-o-chevron-right class="size-3.5" />
                        </button>
                    </nav>
                @endif
            </div>

            <div class="mt-5 grid grid-cols-[repeat(auto-fill,minmax(20rem,1fr))] items-start gap-7 transition-opacity duration-300"
                x-init="autoAnimate($el, { duration: 250 })" aria-label="Event list">
                @forelse ($events as $event)
                    <x-cards.event :event="$event" :href="route('events.show', $event)" />
                @empty
                    <div class="py-12 text-center">
                        <p class="text-primary/60">Nessun evento trovato con i filtri selezionati.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </main>
</div>
