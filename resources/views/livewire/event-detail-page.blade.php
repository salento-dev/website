@section('hero')
    <div class="mb-6 flex flex-wrap items-center justify-center gap-2">
        <x-ui.status-badge :status="$eventStatus" :label="$eventStatus === 'past' ? 'Evento concluso' : null" />

        @foreach ($event->categories as $category)
            <div class="inline-block rounded-xs border border-primary/20 bg-primary/5 px-4 py-1 text-2xs font-bold uppercase tracking-[0.3em] text-primary">
                {{ $category->name }}
            </div>
        @endforeach
    </div>

    <h1 class="mb-10 text-4xl font-black uppercase leading-tight tracking-tighter text-surface-50 sm:text-5xl lg:text-6xl">
        {{ $event->title }}
    </h1>

    {{-- Info strip: date + location --}}
    <div class="flex flex-wrap justify-center gap-px border border-border/60 bg-border/40 overflow-hidden">

        {{-- Data inizio --}}
        <div class="flex grow basis-56 items-center gap-3 bg-surface-950 px-6 py-5">
            <x-heroicon-o-calendar-days class="size-5 shrink-0 text-primary" />
            <div class="text-left">
                <div class="mb-0.5 font-mono text-2xs uppercase tracking-widest text-surface-400">
                    {{ $event->local_ends_at && !$event->local_starts_at->isSameDay($event->local_ends_at) ? 'Inizio' : 'Data' }}
                </div>
                <div class="font-outfit font-bold text-surface-50">
                    {{ $event->local_starts_at->translatedFormat('j F Y') }}
                </div>
                @if ($event->local_starts_at->format('H:i') !== '00:00')
                    <div class="font-mono text-xs text-primary">{{ $event->local_starts_at->format('H:i') }}</div>
                @endif
            </div>
        </div>

        {{-- Data fine (solo se diversa dall'inizio) --}}
        @if ($event->local_ends_at && !$event->local_starts_at->isSameDay($event->local_ends_at))
            <div class="flex grow basis-56 items-center gap-3 bg-surface-950 px-6 py-5">
                <x-heroicon-o-flag class="size-5 shrink-0 text-primary/60" />
                <div class="text-left">
                    <div class="mb-0.5 font-mono text-2xs uppercase tracking-widest text-surface-400">Fine</div>
                    <div class="font-outfit font-bold text-surface-200">
                        {{ $event->local_ends_at->translatedFormat('j F Y') }}
                    </div>
                    @if ($event->local_ends_at->format('H:i') !== '00:00')
                        <div class="font-mono text-xs text-surface-400">{{ $event->local_ends_at->format('H:i') }}</div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Luogo --}}
        @if ($event->location)
            <div class="flex grow basis-56 items-center gap-3 bg-surface-950 px-6 py-5">
                <x-heroicon-o-map-pin class="size-5 shrink-0 text-primary" />
                <div class="text-left">
                    <div class="mb-0.5 font-mono text-2xs uppercase tracking-widest text-surface-400">Luogo</div>
                    <div class="font-outfit font-bold text-surface-50">{{ $event->location }}</div>
                    @if ($event->address)
                        <div class="text-xs text-surface-400">{{ $event->address }}</div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Organizzatore --}}
        <div class="flex w-full items-center gap-3 bg-surface-950 px-6 py-2.5 border-t border-border/60">
            @if ($event->is_community_event)
                <x-ui.logo class="h-3 w-auto shrink-0 text-primary" />
                <span class="font-mono text-2xs uppercase tracking-widest text-primary">Evento della community</span>
            @else
                <x-heroicon-o-user-group class="size-3.5 shrink-0 text-surface-400" />
                <span class="font-mono text-2xs uppercase tracking-widest text-surface-400">Evento esterno</span>
            @endif
        </div>

    </div>
@endsection

<x-ui.detail-layout>

    {{-- Articolo --}}
    <x-slot:article>
        <x-ui.read-stats :read-time="$event->read_time_minutes" :page-views="$event->totalPageViews()" />

        @if (!empty($event->excerpt))
            <p class="mb-8 text-base md:text-lg leading-relaxed text-surface-300 border-l-2 border-primary pl-5">
                {{ strip_tags($event->excerpt) }}
            </p>
        @endif

        <div class="prose prose-invert prose-mist prose-sm md:prose-base max-w-none">
            @if(!empty($event->body))
            {!! \Illuminate\Support\Str::markdown($event->body, [
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
            ]) !!}
            @endif
        </div>
    </x-slot:article>

    {{-- Sidebar --}}
    <x-slot:sidebar>
        <x-ui.lightbox :src="$event->image_url" :alt="$event->title" />

        @if ($event->partners->isNotEmpty())
            <div class="border border-border bg-surface-900 p-5 flex flex-col gap-4">
                <h3 class="font-mono text-2xs uppercase tracking-widest text-primary">Partner</h3>
                @foreach ($event->partners as $partner)
                    <div class="flex items-center gap-4">
                        <div class="size-12 overflow-hidden bg-surface-800 border border-border flex items-center justify-center shrink-0">
                            @if ($partner->logo_url)
                                <img loading="lazy" src="{{ $partner->logo_url }}"
                                    alt="{{ $partner->name }}"
                                    class="size-full object-contain p-1.5">
                            @else
                                <x-heroicon-o-building-storefront class="size-6 text-primary/60" />
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="font-outfit font-bold text-surface-50 truncate">{{ $partner->name }}</p>
                            @if (filled($partner->website_url))
                                <a href="{{ $partner->website_url }}" target="_blank" rel="noreferrer noopener"
                                    class="mt-1 inline-flex items-center gap-1.5 font-mono text-2xs uppercase tracking-widest text-surface-400 transition-colors hover:text-primary">
                                    <x-heroicon-o-globe-alt class="size-3" />
                                    Sito web<span class="sr-only"> – {{ $partner->name }}, si apre in una nuova scheda</span>
                                </a>
                            @endif
                        </div>
                    </div>
                    @if (!$loop->last)
                        <hr class="border-border/50">
                    @endif
                @endforeach
            </div>
        @endif

        @if ($eventStatus === 'past')
            <x-events.past-event-next :next-event="$nextEvent" />
        @endif

        <a href="{{ route('events') }}"
            class="flex items-center justify-center gap-2 border border-border bg-surface px-6 py-3 text-xs font-bold tracking-widest text-primary uppercase transition-all hover:bg-primary/10">
            <x-heroicon-o-arrow-left class="size-4" />
            Tutti gli eventi
        </a>
    </x-slot:sidebar>

    {{-- Sezioni aggiuntive --}}

    @if ($event->media->isNotEmpty())
        <x-ui.section-header title="Galleria">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($event->media as $media)
                    <div class="border border-border bg-surface overflow-hidden">
                        @if ($media->source_type === 'file' && filled($media->file_path))
                            @if ($media->media_type === 'video')
                                <video controls class="w-full aspect-video bg-black object-cover">
                                    <source src="{{ $media->file_url }}">
                                </video>
                            @else
                                <img loading="lazy" src="{{ $media->file_url }}"
                                    alt="{{ $media->caption ?: $event->title }}"
                                    class="w-full aspect-video object-cover">
                            @endif
                        @elseif ($media->source_type === 'url' && filled($media->external_url))
                            @if ($this->toEmbedUrl($media->external_url))
                                <iframe src="{{ $this->toEmbedUrl($media->external_url) }}"
                                    class="w-full aspect-video" loading="lazy"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                            @else
                                <a href="{{ $media->external_url }}" target="_blank" rel="noreferrer noopener"
                                    class="flex aspect-video items-center justify-center px-4 text-center text-sm text-primary transition-colors hover:text-primary/80">
                                    Apri video esterno<span class="sr-only">, si apre in una nuova scheda</span>
                                </a>
                            @endif
                        @endif
                        @if (filled($media->caption))
                            <div class="border-t border-border px-4 py-3 text-sm text-surface-400">
                                {{ $media->caption }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </x-ui.section-header>
    @endif

    @if ($event->resources->isNotEmpty())
        <x-ui.terminal :path="'/resources'">
            <div class="divide-y divide-border/50 font-mono text-xs">
                @foreach ($event->resources->where('is_published', true) as $resource)
                    <x-cards.resource-row :resource="$resource" />
                @endforeach
            </div>
        </x-ui.terminal>
    @endif

    @if ($relatedEvents->isNotEmpty())
        <x-ui.section-header eyebrow="Scopri anche" title="Altri eventi">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($relatedEvents as $relatedEvent)
                    <x-events.event :event="$relatedEvent" :variant="'simple'" />
                @endforeach
            </div>
        </x-ui.section-header>
    @endif

</x-ui.detail-layout>
