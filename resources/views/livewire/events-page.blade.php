<div class="relative flex min-h-screen flex-col">
    <main class="flex-1">
        <section
            class="relative flex min-h-[40vh] items-center justify-center border-b border-border-cyan bg-primary/5 px-6 py-20 lg:px-20">
            <div class="relative z-10 mx-auto max-w-[1000px] text-center">
                <div
                    class="mb-6 inline-block rounded border border-primary/20 bg-primary/5 px-4 py-1 text-[10px] font-bold uppercase tracking-[0.3em] text-primary">
                    Event Archive // Access Protocol
                </div>
                <h1
                    class="mb-6 text-5xl font-black uppercase leading-tight tracking-tighter text-white sm:text-6xl lg:text-7xl">
                    Eventi <span class="text-glow text-primary">Community</span>
                </h1>
                <p class="mx-auto max-w-2xl text-lg font-light leading-relaxed text-white/60">
                    Meetup, workshop e momenti di confronto della community. Esplora tutti gli eventi pubblicati.
                </p>
            </div>
        </section>

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
