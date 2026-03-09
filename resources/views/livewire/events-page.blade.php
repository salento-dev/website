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
