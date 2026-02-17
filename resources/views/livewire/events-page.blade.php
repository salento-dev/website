<div class="relative flex min-h-screen flex-col">
    <x-home.header />

    <main class="flex-1">
        <!-- Hero Section -->
        <section class="relative flex min-h-[40vh] items-center justify-center px-6 py-20 lg:px-20 bg-primary/5 border-b border-border-cyan">
            <div class="relative z-10 mx-auto max-w-[1000px] text-center">
                <div class="inline-block rounded border border-primary/20 bg-primary/5 px-4 py-1 text-[10px] font-bold uppercase tracking-[0.3em] text-primary mb-6">
                    Event Archive // Access Protocol
                </div>
                <h1 class="mb-6 text-5xl font-black uppercase leading-tight tracking-tighter sm:text-6xl lg:text-7xl text-white">
                    Eventi <span class="text-primary text-glow">Community</span>
                </h1>
                <p class="mx-auto max-w-2xl text-lg font-light leading-relaxed text-white/60">
                    Meetup, workshop e momenti di confronto della community. Esplora tutti gli eventi pubblicati.
                </p>
            </div>
        </section>

        <!-- Filters Section -->
        <section class="border-b border-border-cyan bg-background-dark">
            <div class="container py-8">
                <div class="flex flex-col gap-6">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">tune</span>
                        <h2 class="text-sm font-bold tracking-widest text-primary uppercase">Filter_System</h2>
                    </div>

                    <div class="flex gap-8 flex-wrap items-center">
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="SEARCH_DATABASE..."
                            class="w-full border border-border-cyan bg-background-dark px-4 py-3 text-xs tracking-widest text-primary placeholder:text-primary/40 focus:border-primary focus:ring-0"
                        >

                        <div class="flex flex-wrap gap-2">
                            <button
                                type="button"
                                wire:click="$set('period', 'all')"
                                @class([
                                    'px-4 py-2 text-[10px] font-bold tracking-widest uppercase transition-all',
                                    'bg-primary text-background-dark shadow-neon' => $period === 'all',
                                    'border border-border-cyan text-primary hover:bg-primary/10' => $period !== 'all',
                                ])
                            >ALL</button>
                            <button
                                type="button"
                                wire:click="$set('period', 'upcoming')"
                                @class([
                                    'px-4 py-2 text-[10px] font-bold tracking-widest uppercase transition-all',
                                    'bg-primary text-background-dark shadow-neon' => $period === 'upcoming',
                                    'border border-border-cyan text-primary hover:bg-primary/10' => $period !== 'upcoming',
                                ])
                            >UPCOMING</button>
                            <button
                                type="button"
                                wire:click="$set('period', 'past')"
                                @class([
                                    'px-4 py-2 text-[10px] font-bold tracking-widest uppercase transition-all',
                                    'bg-primary text-background-dark shadow-neon' => $period === 'past',
                                    'border border-border-cyan text-primary hover:bg-primary/10' => $period !== 'past',
                                ])
                            >ARCHIVE</button>
                        </div>

                        @if ($hasEventCategoryTables)
                            <div class="flex flex-wrap gap-2">
                                <button
                                    type="button"
                                    wire:click="$set('category', 'all')"
                                    @class([
                                        'px-4 py-2 text-[10px] font-bold tracking-widest uppercase transition-all',
                                        'bg-primary text-background-dark shadow-neon' => $category === 'all',
                                        'border border-border-cyan text-primary hover:bg-primary/10' => $category !== 'all',
                                    ])
                                >ALL_TYPES</button>

                                @foreach ($categories as $eventCategory)
                                    <button
                                        type="button"
                                        wire:click="$set('category', '{{ $eventCategory->slug }}')"
                                        @class([
                                            'px-4 py-2 text-[10px] font-bold tracking-widest uppercase transition-all',
                                            'bg-primary text-background-dark shadow-neon' => $category === $eventCategory->slug,
                                            'border border-border-cyan text-primary hover:bg-primary/10' => $category !== $eventCategory->slug,
                                        ])
                                    >{{ str_replace(' ', '_', strtoupper($eventCategory->name)) }}</button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- Events Grid -->
        <x-home.section-header 
            eyebrow="QUERY_RESULTS" 
            title='Event Database'
            info="{{ $events->count() }} Records"
        >
            <div class="grid grid-cols-1 gap-8">
                @forelse ($events as $event)
                    <div class="group relative flex flex-col md:flex-row bg-surface-dark/40 border border-primary/20 hover:border-primary/60 transition-all rounded-sm overflow-hidden glitch-hover">
                        <!-- Technical Corner Ornaments -->
                        <div class="absolute top-0 right-0 p-1">
                            <span class="material-symbols-outlined text-primary/40 text-xs">close_fullscreen</span>
                        </div>
                        
                        <!-- Event Image -->
                        <div 
                            class="w-full md:w-80 h-48 md:h-auto bg-center bg-cover border-r border-primary/10 grayscale group-hover:grayscale-0 transition-all duration-500"
                            style="background-image: url('{{ $event->image_path ? asset('storage/' . ltrim($event->image_path, '/')) : asset('storage/placeholders/events/event-placeholder.jpg') }}');"
                        >
                            <div class="h-full w-full bg-primary/10 opacity-40"></div>
                        </div>
                        
                        <!-- Event Content -->
                        <div class="flex flex-1 flex-col p-6 gap-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="font-mono text-[10px] text-primary/60 uppercase tracking-widest block mb-1">
                                        ID: LOG_{{ $event->starts_at?->format('Y_m_d') }}_{{ strtoupper(substr($event->slug ?? 'event', 0, 1)) }}
                                    </span>
                                    <h3 class="text-white text-2xl font-bold tracking-tight group-hover:text-primary transition-colors uppercase italic">
                                        {{ $event->title }}
                                    </h3>
                                </div>
                                <div class="text-right">
                                    <span class="font-mono text-xl text-primary font-bold neon-text-glow">
                                        {{ $event->starts_at?->format('Y.m.d') }}
                                    </span>
                                    <p class="text-[10px] text-slate-500 uppercase tracking-tighter">Event_Timestamp</p>
                                </div>
                            </div>
                            
                            <div class="flex flex-col gap-2">
                                <p class="text-slate-400 text-sm leading-relaxed max-w-2xl">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($event->description), 200) }}
                                </p>
                                @if ($event->speaker)
                                    <div class="flex items-center gap-2 mt-2">
                                        <span class="material-symbols-outlined text-sm text-primary">person</span>
                                        <span class="text-xs font-mono text-primary/80 uppercase">SPEAKER: {{ str_replace(' ', '_', strtoupper($event->speaker)) }}</span>
                                    </div>
                                @endif
                                @if ($event->location)
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-sm text-primary">location_on</span>
                                        <span class="text-xs font-mono text-primary/80 uppercase">{{ strtoupper($event->location) }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="mt-auto pt-6 flex flex-wrap items-center justify-between border-t border-primary/10 gap-4">
                                <div class="flex gap-4">
                                    @if (($event->media_count ?? 0) > 0)
                                        <div class="flex flex-col items-center gap-1 group/icon">
                                            <div class="p-2 rounded-sm bg-primary/5 border border-primary/20 group-hover/icon:bg-primary group-hover/icon:text-background-dark transition-all text-primary">
                                                <span class="material-symbols-outlined text-base">description</span>
                                            </div>
                                            <span class="text-[9px] font-mono font-bold uppercase tracking-tighter text-slate-500">Slides</span>
                                        </div>
                                    @endif
                                    
                                    @if (($event->video_media_count ?? 0) > 0)
                                        <div class="flex flex-col items-center gap-1 group/icon">
                                            <div class="p-2 rounded-sm bg-primary/5 border border-primary/20 group-hover/icon:bg-primary group-hover/icon:text-background-dark transition-all text-primary">
                                                <span class="material-symbols-outlined text-base">video_library</span>
                                            </div>
                                            <span class="text-[9px] font-mono font-bold uppercase tracking-tighter text-slate-500">Video</span>
                                        </div>
                                    @endif
                                    
                                    @if (($event->image_media_count ?? 0) > 0)
                                        <div class="flex flex-col items-center gap-1 group/icon">
                                            <div class="p-2 rounded-sm bg-primary/5 border border-primary/20 group-hover/icon:bg-primary group-hover/icon:text-background-dark transition-all text-primary">
                                                <span class="material-symbols-outlined text-base">imagesmode</span>
                                            </div>
                                            <span class="text-[9px] font-mono font-bold uppercase tracking-tighter text-slate-500">Gallery</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <a 
                                    href="{{ route('events.show', $event) }}"
                                    class="flex items-center gap-2 px-6 py-2 bg-primary text-background-dark text-xs font-black uppercase tracking-tighter rounded-sm hover:bg-white transition-all"
                                >
                                    VIEW_FULL_LOG_REPORT <span class="material-symbols-outlined text-sm">arrow_forward_ios</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <p class="text-primary/60">Nessun evento trovato con i filtri selezionati.</p>
                    </div>
                @endforelse
            </div>
        </x-home.section-header>
    </main>

    <x-home.footer />
</div>