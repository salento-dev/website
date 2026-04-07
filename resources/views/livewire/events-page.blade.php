@section('hero')
    <x-sections.page-hero
        :badge="'Events // Community'"
        :description="'Meetup, workshop e momenti di confronto. Esplora i prossimi appuntamenti e lo storico degli eventi Salento.dev.'">
        Eventi <span class="text-glow text-primary">Community</span>
    </x-sections.page-hero>
@endsection

<div class="flex flex-col gap-20">

    {{-- ─────────────────────────────────────────────
         SECTION 1 — PROSSIMI EVENTI
    ───────────────────────────────────────────── --}}
    <section>
        <div class="mb-8 flex items-end justify-between">
            <header>
                <span class="mb-2 block font-mono text-xs uppercase tracking-[0.2em] text-primary">Confirmed_Active</span>
                <h2 class="font-outfit text-3xl font-extrabold tracking-tight text-mist-50">Prossimi Eventi</h2>
            </header>
            <div class="hidden gap-3 md:flex">
                <span class="rounded-xs border border-outline-variant/20 bg-surface-800 px-3 py-1 font-mono text-[10px] uppercase tracking-widest text-primary">
                    Upcoming: {{ $upcomingEvents->count() }}
                </span>
            </div>
        </div>

        @if ($upcomingEvents->isNotEmpty())
            <x-ui.slideshow :count="$upcomingEvents->count()">
                @foreach ($upcomingEvents as $event)
                    <x-ui.slideshow-slide :index="$loop->index">
                        <x-cards.event-featured :event="$event" />
                    </x-ui.slideshow-slide>
                @endforeach
            </x-ui.slideshow>
        @else
            <div class="flex flex-col items-center justify-center gap-4 border border-dashed border-surface-700 py-20 text-center">
                <x-heroicon-o-calendar-days class="h-10 text-mist-600" />
                <p class="font-mono text-sm text-mist-500">Nessun evento in programma al momento.</p>
            </div>
        @endif
    </section>

    {{-- ─────────────────────────────────────────────
         SECTION 2 — WORK IN PROGRESS
    ───────────────────────────────────────────── --}}
    @if ($wipEvents->isNotEmpty())
        <section>
            <div class="mb-8 flex items-center gap-6">
                <h2 class="font-outfit text-2xl font-bold text-mist-50">In Planning</h2>
                <div class="h-px flex-1 bg-surface-700"></div>
            </div>

            <div class="grid gap-8 md:grid-cols-2">
                @foreach ($wipEvents as $event)
                    <div class="group relative flex flex-col justify-between border-2 border-dashed border-surface-700 bg-surface-900 p-8 transition-colors hover:border-primary/30">
                        <div class="absolute right-6 top-6">
                            <span class="border border-primary/30 px-2 py-0.5 font-mono text-[9px] uppercase tracking-[0.2em] text-primary">WIP</span>
                        </div>

                        <div>
                            <x-heroicon-o-clock class="mb-5 h-8 text-surface-600" />
                            <h4 class="mb-2 font-outfit text-xl font-bold text-mist-200">{{ $event->title }}</h4>
                            @if ($event->description)
                                <p class="line-clamp-3 text-sm leading-relaxed text-mist-500">
                                    {{ strip_tags($event->description) }}
                                </p>
                            @endif
                        </div>

                        <div class="mt-8 flex items-center justify-between">
                            @if ($event->starts_at)
                                <span class="font-mono text-xs italic text-primary/70">
                                    Target: {{ $event->starts_at->translatedFormat('M Y') }}
                                </span>
                            @else
                                <span class="font-mono text-xs italic text-mist-600">Data TBD</span>
                            @endif

                            @if ($event->location)
                                <span class="flex items-center gap-1 font-mono text-[10px] uppercase tracking-widest text-mist-500">
                                    <x-heroicon-o-map-pin class="h-3" />
                                    {{ $event->location }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ─────────────────────────────────────────────
         SECTION 3 — STORICO EVENTI
    ───────────────────────────────────────────── --}}
    <section>
        <div class="mb-8 flex items-end justify-between">
            <header>
                <h2 class="font-outfit text-2xl font-bold text-mist-50">Storico Eventi</h2>
                <p class="mt-1 font-mono text-[10px] uppercase tracking-widest text-mist-500">Archivio storico delle attività</p>
            </header>
        </div>

        <div class="flex flex-col gap-6">
            <x-ui.input-search wire-model="search" placeholder="Cerca nello storico..." sr-label="Cerca eventi per titolo, luogo o descrizione" />

            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-4">
                    <x-ui.sort-select :options="$sortOptions" :current-value="$sortBy" wire-property="sortBy" />
                    <x-ui.item-count :collection="$pastEvents" label="events found" />
                </div>
                <x-ui.paginator :paginator="$pastEvents" />
            </div>

            <div class="grid grid-cols-[repeat(auto-fill,minmax(20rem,1fr))] items-start gap-7 transition-opacity duration-300"
                x-init="autoAnimate($el, { duration: 250 })" aria-label="Storico eventi">
                @forelse ($pastEvents as $event)
                    <x-cards.event :event="$event" />
                @empty
                    <div class="col-span-full py-12 text-center">
                        <p class="text-primary/60">Nessun evento trovato con i filtri selezionati.</p>
                    </div>
                @endforelse
            </div>

            @if ($pastEvents->hasPages())
                <div class="flex justify-end">
                    <x-ui.paginator :paginator="$pastEvents" />
                </div>
            @endif
        </div>
    </section>

</div>
