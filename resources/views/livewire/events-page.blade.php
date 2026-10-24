@section('hero')
    <x-sections.page-hero 
        :badge="'Events // Community'" 
        description="Meetup, lightning talk, AperiDev e workshop, ogni volta in un comune diverso del Salento. Qui trovi i prossimi e tutti quelli passati.">
        Eventi <span class="text-primary">Community</span>
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
                        <x-events.featured-event :event="$event" />
                    </x-ui.slideshow-slide>
                @endforeach
            </x-ui.slideshow>
        </x-ui.section-header>
    @else
        <x-events.no-upcoming />
    @endif

    {{-- ─────────────────────────────────────────────
         SECTION 2 — WORK IN PROGRESS
    ───────────────────────────────────────────── --}}
    @if ($wipEvents->isNotEmpty())
        <x-ui.section-header title="In pianificazione" size="small">
            <div class="grid gap-8 md:grid-cols-2">
                @foreach ($wipEvents as $event)
                    <x-events.wip-event :event="$event" />
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
                        <x-events.event :event="$event" />
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
