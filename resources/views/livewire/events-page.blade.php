@section('hero')
    <x-sections.page-hero 
        :badge="'Event Archive // Access Protocol'" 
        :description="'Meetup, workshop e momenti di confronto della community. Esplora tutti gli eventi pubblicati.'">
        Eventi <span class="text-glow text-primary">Community</span>
    </x-sections.page-hero>
@endsection

<div class="flex flex-col gap-6">
    <x-ui.input-search wire-model="search" placeholder="Cerca eventi..." sr-label="Cerca eventi per titolo, luogo o descrizione" />

    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-4">
            <x-ui.sort-select :options="$sortOptions" :current-value="$sortBy" wire-property="sortBy" />
            <x-ui.item-count :collection="$events" label="events found" />
        </div>
        <x-ui.paginator :paginator="$events" />
    </div>

    <div class="grid grid-cols-[repeat(auto-fill,minmax(20rem,1fr))] items-start gap-7 transition-opacity duration-300"
        x-init="autoAnimate($el, { duration: 250 })" aria-label="Event list">
        @forelse ($events as $event)
            <x-cards.event :event="$event" />
        @empty
            <div class="py-12 text-center">
                <p class="text-primary/60">Nessun evento trovato con i filtri selezionati.</p>
            </div>
        @endforelse
    </div>
</div>
