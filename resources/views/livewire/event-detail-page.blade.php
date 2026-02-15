<div class="relative flex min-h-screen flex-col overflow-x-hidden bg-grid">
    <x-home.header />

    <main class="mx-auto flex w-full max-w-7xl flex-1 flex-col gap-10 px-6 py-10 lg:px-20">
        <section class="relative overflow-hidden border border-primary/20 bg-cyber-card/50 p-8">
            <p class="mb-3 text-xs font-bold tracking-[0.2em] text-primary/50 uppercase">EVENT_DETAIL</p>
            <h1 class="mb-4 text-3xl font-black tracking-tighter text-primary uppercase md:text-5xl">{{ $event->title }}</h1>

            <div class="grid grid-cols-1 gap-3 text-xs text-primary/70 uppercase md:grid-cols-3">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-base">event</span>
                    {{ $event->starts_at?->format('d/m/Y H:i') }}
                </div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-base">location_on</span>
                    {{ $event->location }}
                </div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-base">pin_drop</span>
                    {{ $event->address ?: 'Indirizzo da definire' }}
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            <article class="border border-cyber-border bg-cyber-card p-6 lg:col-span-8">
                <h2 class="mb-4 text-xl font-black tracking-tighter text-primary uppercase">Descrizione</h2>
                <p class="leading-relaxed text-primary/80 whitespace-pre-line">{{ $event->description }}</p>
            </article>

            <aside class="lg:col-span-4">
                <div class="overflow-hidden border border-cyber-border bg-cyber-card">
                    <img
                        src="{{ $event->image_path ? asset('storage/' . ltrim($event->image_path, '/')) : asset('storage/placeholders/events/event-placeholder.jpg') }}"
                        alt="{{ $event->title }}"
                        class="h-64 w-full object-cover"
                    >
                </div>

                <a
                    href="{{ route('events') }}"
                    class="mt-4 inline-flex items-center gap-2 border border-primary px-4 py-2 text-xs font-bold tracking-widest text-primary uppercase transition-colors hover:bg-primary/10"
                >
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                    Torna agli eventi
                </a>
            </aside>
        </section>

        <section>
            <div class="mb-8 flex items-center justify-between">
                <h2 class="flex items-center gap-2 text-2xl font-black tracking-tighter text-primary uppercase">
                    <span class="material-symbols-outlined">hub</span>
                    Altri Eventi
                </h2>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($relatedEvents as $relatedEvent)
                    <x-home.event-card :event="$relatedEvent" :href="route('events.show', $relatedEvent)" />
                @empty
                    <p class="text-sm text-primary/60">Nessun altro evento disponibile.</p>
                @endforelse
            </div>
        </section>
    </main>

    <x-home.footer />
</div>