<div class="relative flex min-h-screen flex-col overflow-x-hidden bg-grid">
    <x-home.header />

    <main class="mx-auto flex w-full max-w-7xl flex-1 flex-col gap-10 px-6 py-10 lg:px-20">
        <section class="relative overflow-hidden border border-primary/20 bg-cyber-card/50 p-8">
            <h1 class="mb-3 text-4xl font-black tracking-tighter text-primary uppercase md:text-5xl">Eventi</h1>
            <p class="max-w-3xl text-sm leading-relaxed text-primary/70 md:text-base">
                Meetup, workshop e momenti di confronto della community. Qui trovi tutti gli eventi pubblicati, ordinati per data.
            </p>
        </section>

        <section class="border-l-4 border-primary bg-primary/5 py-3 pr-4 pl-4">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">tune</span>
                    <h2 class="text-sm font-black tracking-widest text-primary uppercase">System_Archive_Filters</h2>
                </div>

                <div class="grid grid-cols-1 gap-3 md:grid-cols-4 md:items-center">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="SEARCH_EVENT"
                        class="w-full border border-primary/30 bg-background-dark px-3 py-2 text-xs font-bold tracking-wider text-primary uppercase placeholder:text-primary/40 focus:border-primary focus:ring-primary/30"
                    >

                    <div class="flex flex-wrap gap-2">
                        <button
                            type="button"
                            wire:click="$set('period', 'all')"
                            @class([
                                'px-3 py-1 text-[10px] font-bold tracking-widest uppercase border transition-colors',
                                'border-primary bg-primary text-background-dark' => $period === 'all',
                                'border-primary/30 text-primary hover:bg-primary/10' => $period !== 'all',
                            ])
                        >ALL</button>
                        <button
                            type="button"
                            wire:click="$set('period', 'upcoming')"
                            @class([
                                'px-3 py-1 text-[10px] font-bold tracking-widest uppercase border transition-colors',
                                'border-primary bg-primary text-background-dark' => $period === 'upcoming',
                                'border-primary/30 text-primary hover:bg-primary/10' => $period !== 'upcoming',
                            ])
                        >UPCOMING</button>
                        <button
                            type="button"
                            wire:click="$set('period', 'past')"
                            @class([
                                'px-3 py-1 text-[10px] font-bold tracking-widest uppercase border transition-colors',
                                'border-primary bg-primary text-background-dark' => $period === 'past',
                                'border-primary/30 text-primary hover:bg-primary/10' => $period !== 'past',
                            ])
                        >ARCHIVE</button>
                    </div>

                    @if ($hasEventCategoryTables)
                        <div class="flex flex-wrap gap-2">
                            <button
                                type="button"
                                wire:click="$set('category', 'all')"
                                @class([
                                    'px-3 py-1 text-[10px] font-bold tracking-widest uppercase border transition-colors',
                                    'border-primary bg-primary text-background-dark' => $category === 'all',
                                    'border-primary/30 text-primary hover:bg-primary/10' => $category !== 'all',
                                ])
                            >ALL_TYPES</button>

                            @foreach ($categories as $eventCategory)
                                <button
                                    type="button"
                                    wire:click="$set('category', '{{ $eventCategory->slug }}')"
                                    @class([
                                        'px-3 py-1 text-[10px] font-bold tracking-widest uppercase border transition-colors',
                                        'border-primary bg-primary text-background-dark' => $category === $eventCategory->slug,
                                        'border-primary/30 text-primary hover:bg-primary/10' => $category !== $eventCategory->slug,
                                    ])
                                >{{ str_replace(' ', '_', strtoupper($eventCategory->name)) }}</button>
                            @endforeach
                        </div>
                    @endif

                    @if ($hasEventMediaTable)
                        <div class="flex flex-wrap gap-2">
                            <button
                                type="button"
                                wire:click="$set('mediaFilter', 'all')"
                                @class([
                                    'px-3 py-1 text-[10px] font-bold tracking-widest uppercase border transition-colors',
                                    'border-primary bg-primary text-background-dark' => $mediaFilter === 'all',
                                    'border-primary/30 text-primary hover:bg-primary/10' => $mediaFilter !== 'all',
                                ])
                            >ALL_MEDIA</button>
                            <button
                                type="button"
                                wire:click="$set('mediaFilter', 'with_media')"
                                @class([
                                    'px-3 py-1 text-[10px] font-bold tracking-widest uppercase border transition-colors',
                                    'border-primary bg-primary text-background-dark' => $mediaFilter === 'with_media',
                                    'border-primary/30 text-primary hover:bg-primary/10' => $mediaFilter !== 'with_media',
                                ])
                            >HAS_GALLERY</button>
                            <button
                                type="button"
                                wire:click="$set('mediaFilter', 'with_video')"
                                @class([
                                    'px-3 py-1 text-[10px] font-bold tracking-widest uppercase border transition-colors',
                                    'border-primary bg-primary text-background-dark' => $mediaFilter === 'with_video',
                                    'border-primary/30 text-primary hover:bg-primary/10' => $mediaFilter !== 'with_video',
                                ])
                            >HAS_VIDEO</button>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <section>
            <div class="mb-8 flex items-center justify-between">
                <h2 class="flex items-center gap-2 text-2xl font-black tracking-tighter text-primary uppercase">
                    <span class="material-symbols-outlined">event_note</span>
                    Lista Eventi
                </h2>
                <div class="text-[10px] font-bold tracking-[0.2em] text-primary/40 uppercase">
                    RESULTS: {{ $events->count() }}
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($events as $event)
                    <x-home.event-card :event="$event" :href="route('events.show', $event)" variant="archive" />
                @empty
                    <p class="text-sm text-primary/60">Nessun evento trovato con i filtri selezionati.</p>
                @endforelse
            </div>
        </section>
    </main>

    <x-home.footer />
</div>