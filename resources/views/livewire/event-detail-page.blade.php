@section('hero')
    @if ($event->categories->isNotEmpty())
        <div class="mb-6 flex flex-wrap justify-center gap-2">
            @foreach ($event->categories as $category)
                <div class="inline-block rounded border border-primary/20 bg-primary/5 px-4 py-1 text-2xs font-bold uppercase tracking-[0.3em] text-primary">
                    {{ $category->name }}
                </div>
            @endforeach
        </div>
    @endif

    <h1 class="mb-10 text-4xl font-black uppercase leading-tight tracking-tighter text-white sm:text-5xl lg:text-6xl">
        {{ $event->title }}
    </h1>

    {{-- Info strip: date + location --}}
    <div class="flex flex-wrap justify-center gap-px border border-border/60 bg-border/40 overflow-hidden">

        {{-- Data inizio --}}
        <div class="flex grow basis-48 items-center justify-center gap-3 bg-surface-950 px-6 py-5">
            <x-heroicon-o-calendar-days class="size-5 shrink-0 text-primary" />
            <div class="text-left">
                <div class="mb-0.5 font-mono text-2xs uppercase tracking-widest text-mist-600">
                    {{ $event->ends_at && !$event->starts_at->isSameDay($event->ends_at) ? 'Inizio' : 'Data' }}
                </div>
                <div class="font-outfit font-bold text-mist-50">
                    {{ $event->starts_at->translatedFormat('j F Y') }}
                </div>
                @if ($event->starts_at->format('H:i') !== '00:00')
                    <div class="font-mono text-xs text-primary">{{ $event->starts_at->format('H:i') }}</div>
                @endif
            </div>
        </div>

        {{-- Data fine (solo se diversa dall'inizio) --}}
        @if ($event->ends_at && !$event->starts_at->isSameDay($event->ends_at))
            <div class="flex grow basis-48 items-center justify-center gap-3 bg-surface-950 px-6 py-5">
                <x-heroicon-o-flag class="size-5 shrink-0 text-primary/60" />
                <div class="text-left">
                    <div class="mb-0.5 font-mono text-2xs uppercase tracking-widest text-mist-600">Fine</div>
                    <div class="font-outfit font-bold text-mist-200">
                        {{ $event->ends_at->translatedFormat('j F Y') }}
                    </div>
                    @if ($event->ends_at->format('H:i') !== '00:00')
                        <div class="font-mono text-xs text-mist-400">{{ $event->ends_at->format('H:i') }}</div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Luogo --}}
        @if ($event->location)
            <div class="flex grow basis-48 items-center justify-center gap-3 bg-surface-950 px-6 py-5">
                <x-heroicon-o-map-pin class="size-5 shrink-0 text-primary" />
                <div class="text-left">
                    <div class="mb-0.5 font-mono text-2xs uppercase tracking-widest text-mist-600">Luogo</div>
                    <div class="font-outfit font-bold text-mist-50">{{ $event->location }}</div>
                    @if ($event->address)
                        <div class="text-xs text-mist-400">{{ $event->address }}</div>
                    @endif
                </div>
            </div>
        @endif

    </div>
@endsection

<x-ui.detail-layout>

    {{-- Meta mobile --}}
    <x-slot:mobile-meta>
        @include('livewire.partials.event-meta-card')
    </x-slot:mobile-meta>

    {{-- Articolo --}}
    <x-slot:article>
        @if (!empty($event->excerpt))
            <p class="mb-8 text-base md:text-lg leading-relaxed text-surface-300 border-l-2 border-primary pl-5">
                {{ strip_tags($event->excerpt) }}
            </p>
        @endif

        <div class="prose prose-invert prose-sm md:prose-base max-w-none">
            {!! \Illuminate\Support\Str::markdown($event->body, [
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
            ]) !!}
        </div>
    </x-slot:article>

    {{-- Sidebar --}}
    <x-slot:sidebar>
        @php $coverUrl = $event->image_path ? asset('storage/' . ltrim($event->image_path, '/')) : asset('storage/placeholders/events/event-placeholder.jpg'); @endphp
        <x-ui.lightbox :src="$coverUrl" :alt="$event->title" />

        @include('livewire.partials.event-meta-card', ['classes' => 'hidden lg:flex'])

        @if ($event->partners->isNotEmpty())
            <div class="border border-border bg-surface-900 p-5 flex flex-col gap-4">
                <h3 class="font-mono text-2xs uppercase tracking-widest text-primary">Partner</h3>
                @foreach ($event->partners as $partner)
                    <div class="flex items-center gap-4">
                        <div class="size-12 overflow-hidden bg-surface-800 border border-border flex items-center justify-center shrink-0">
                            @if ($partner->logo_path)
                                <img src="{{ asset('storage/' . ltrim($partner->logo_path, '/')) }}"
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
                                    class="mt-1 inline-flex items-center gap-1.5 font-mono text-2xs uppercase tracking-widest text-surface-500 transition-colors hover:text-primary">
                                    <x-heroicon-o-globe-alt class="size-3" />
                                    Sito web
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

        <a href="{{ route('events') }}"
            class="flex items-center justify-center gap-2 border border-border bg-surface px-6 py-3 text-xs font-bold tracking-widest text-primary uppercase transition-all hover:bg-primary/10">
            <x-heroicon-o-arrow-left class="size-4" />
            Tutti gli eventi
        </a>
    </x-slot:sidebar>

    {{-- Sezioni aggiuntive --}}

    @if ($event->media->isNotEmpty())
        <x-ui.section-header eyebrow="Media" title="Galleria">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($event->media as $media)
                    <div class="border border-border bg-surface overflow-hidden">
                        @if ($media->source_type === 'file' && filled($media->file_path))
                            @if ($media->media_type === 'video')
                                <video controls class="w-full aspect-video bg-black object-cover">
                                    <source src="{{ asset('storage/' . ltrim($media->file_path, '/')) }}">
                                </video>
                            @else
                                <img src="{{ asset('storage/' . ltrim($media->file_path, '/')) }}"
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
                                    Apri video esterno
                                </a>
                            @endif
                        @endif
                        @if (filled($media->caption))
                            <div class="border-t border-border px-4 py-3 text-sm text-white/60">
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
