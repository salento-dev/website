@section('hero')
    <x-sections.page-hero :badge="'Events // Community'" :description="'Meetup, workshop e momenti di confronto. Esplora i prossimi appuntamenti e lo storico degli eventi Salento.dev.'">
        Eventi <span class="text-glow text-primary">Community</span>
    </x-sections.page-hero>
@endsection

<div class="flex flex-col gap-20">

    {{-- ─────────────────────────────────────────────
         SECTION 1 — PROSSIMI EVENTI
    ───────────────────────────────────────────── --}}
    @if ($upcomingEvents->isNotEmpty())
        <x-ui.section-header eyebrow="Confirmed_Active" title="Prossimi Eventi"
            tag="Upcoming: {{ $upcomingEvents->count() }}">
            <x-ui.slideshow :count="$upcomingEvents->count()">
                @foreach ($upcomingEvents as $event)
                    <x-ui.slideshow-slide :index="$loop->index">
                        <x-cards.event-featured :event="$event" />
                    </x-ui.slideshow-slide>
                @endforeach
            </x-ui.slideshow>
        </x-ui.section-header>
    @endif

    {{-- ─────────────────────────────────────────────
         SECTION 2 — WORK IN PROGRESS
    ───────────────────────────────────────────── --}}
    @if ($wipEvents->isNotEmpty())
        <x-ui.section-header title="In Planning" size="small">
            <div class="grid gap-8 md:grid-cols-2">
                @foreach ($wipEvents as $event)
                    <div
                        class="group relative flex flex-col justify-between border-2 border-dashed border-surface-700 bg-surface-900 p-8 transition-colors hover:border-primary/30">
                        <div class="absolute right-6 top-6">
                            <x-ui.status-badge status="wip" label="WIP" />
                        </div>

                        <div>
                            <x-heroicon-o-clock class="mb-5 h-8 text-surface-600" />
                            <h4 class="mb-2 font-outfit text-xl font-bold text-surface-200">{{ $event->title }}</h4>
                            @if ($event->description)
                                <p class="line-clamp-3 text-sm leading-relaxed text-surface-500">
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
                                <span class="font-mono text-xs italic text-surface-600">Data TBD</span>
                            @endif

                            @if ($event->location)
                                <span
                                    class="flex items-center gap-1 font-mono text-2xs uppercase tracking-widest text-surface-500">
                                    <x-heroicon-o-map-pin class="h-3" />
                                    {{ $event->location }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </x-ui.section-header>
    @endif

    {{-- ─────────────────────────────────────────────
         SECTION 3 — STORICO EVENTI
    ───────────────────────────────────────────── --}}
    @if ($pastEvents->isNotEmpty())
        <x-ui.section-header title="Storico Eventi" tag="Passed: {{ $pastEvents->count() }}" size="small">
            <div class="flex flex-col gap-6">
                <x-ui.input-search wire-model="search" placeholder="Cerca nello storico..."
                    sr-label="Cerca eventi per titolo, luogo o descrizione" />

                <div class="flex flex-wrap items-center justify-between gap-4">
                    <x-ui.sort-select :options="$sortOptions" :current-value="$sortBy" wire-property="sortBy" />
                    <x-ui.paginator :paginator="$pastEvents" />
                </div>

                <div class="grid grid-cols-[repeat(auto-fill,minmax(20rem,1fr))] items-start gap-7 transition-opacity duration-300"
                    x-init="autoAnimate($el, { duration: 250 })" aria-label="Storico eventi">
                    @forelse ($pastEvents as $event)
                        <x-cards.event :event="$event" />
                    @empty
                        <x-ui.empty-state class="col-span-full"
                            message="Nessun evento trovato con i filtri selezionati." />
                    @endforelse
                </div>

                @if ($pastEvents->hasPages())
                    <div class="flex justify-end">
                        <x-ui.paginator :paginator="$pastEvents" />
                    </div>
                @endif
            </div>
        </x-ui.section-header>
    @endif

</div>
