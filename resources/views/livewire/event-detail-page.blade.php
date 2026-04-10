@section('hero')
    <div
        class="inline-block rounded border border-primary/20 bg-primary/5 px-4 py-1 text-2xs font-bold uppercase tracking-[0.3em] text-primary mb-6">
        Event Detail // ID: {{ $event->id }}
    </div>

    <h1 class="mb-6 text-4xl font-black uppercase leading-tight tracking-tighter text-white sm:text-5xl lg:text-6xl">
        {{ $event->title }}
    </h1>
@endsection

<div class="flex flex-col gap-16">

    {{-- ─────────────────────────────────────────────
         MAIN CONTENT
    ───────────────────────────────────────────── --}}
    <section>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            {{-- Article body --}}
            <article class="lg:col-span-2">

                {{-- Excerpt --}}
                @if (!empty($event->excerpt))
                    <p class="mb-8 text-lg leading-relaxed text-surface-300 border-l-2 border-primary pl-5">
                        {{ strip_tags($event->excerpt) }}
                    </p>
                @endif

                {{-- Body --}}
                <div class="prose prose-invert max-w-none">
                    {!! \Illuminate\Support\Str::markdown($event->body, [
                        'html_input' => 'strip',
                        'allow_unsafe_links' => false,
                    ]) !!}
                </div>
            </article>

            {{-- Sidebar --}}
            <aside class="lg:col-span-1 flex flex-col gap-6">

                {{-- Cover image --}}
                <div class="border border-border bg-surface-900 overflow-hidden">
                    <img src="{{ $event->image_path ? asset('storage/' . ltrim($event->image_path, '/')) : asset('storage/placeholders/events/event-placeholder.jpg') }}"
                        alt="{{ $event->title }}"
                        class="w-full aspect-video object-cover">
                </div>

                {{-- Meta card --}}
                <div class="border border-border bg-surface-900 p-6 flex flex-col gap-3">
                    <h3 class="font-mono text-2xs uppercase tracking-widest text-primary">Dettagli</h3>

                    <div class="flex items-start gap-3">
                        <x-heroicon-o-calendar-days class="size-4 text-primary/50 shrink-0 mt-0.5" />
                        <div class="flex flex-col gap-1.5 text-sm">
                            <div class="flex items-baseline gap-2">
                                <span class="font-mono text-2xs uppercase tracking-widest text-surface-600">Inizio</span>
                                <span class="text-surface-300">{{ $event->starts_at->translatedFormat('j F Y') }}</span>
                                @if ($event->starts_at->format('H:i') !== '00:00')
                                    <span class="font-mono text-xs text-primary/70">{{ $event->starts_at->format('H:i') }}</span>
                                @endif
                            </div>
                            @if ($event->ends_at)
                                <div class="flex items-baseline gap-2">
                                    <span class="font-mono text-2xs uppercase tracking-widest text-surface-600">Fine&nbsp;&nbsp;</span>
                                    <span class="text-surface-400">{{ $event->ends_at->translatedFormat('j F Y') }}</span>
                                    @if ($event->ends_at->format('H:i') !== '00:00')
                                        <span class="font-mono text-xs text-surface-500">{{ $event->ends_at->format('H:i') }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($event->location)
                        <div class="flex items-center gap-3 text-sm text-surface-400">
                            <x-heroicon-o-map-pin class="size-4 text-primary/50 shrink-0" />
                            {{ $event->location }}
                        </div>
                    @endif

                    @if ($event->address)
                        <div class="flex items-center gap-3 text-sm text-surface-400">
                            <x-heroicon-o-building-office class="size-4 text-primary/50 shrink-0" />
                            {{ $event->address }}
                        </div>
                    @endif

                    @if ($event->read_time_minutes)
                        <div class="flex items-center gap-3 text-sm text-surface-400">
                            <x-heroicon-o-book-open class="size-4 text-primary/50 shrink-0" />
                            {{ $event->read_time_minutes }} minuti di lettura
                        </div>
                    @endif

                    @if ($event->categories->isNotEmpty())
                        <div class="flex items-start gap-3 text-sm text-surface-400">
                            <x-heroicon-o-tag class="size-4 text-primary/50 shrink-0 mt-0.5" />
                            <div class="flex flex-wrap gap-1.5">
                                @foreach ($event->categories as $category)
                                    <x-ui.category-tag :name="$category->name" />
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Partners card --}}
                @if ($event->partners->isNotEmpty())
                    <div class="border border-border bg-surface-900 p-6 flex flex-col gap-4">
                        <h3 class="font-mono text-2xs uppercase tracking-widest text-primary">
                            {{ $event->partners->count() > 1 ? 'Partner' : 'Partner' }}
                        </h3>
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
                                    <p class="font-outfit font-bold text-surface-50">{{ $partner->name }}</p>
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

                {{-- Back link --}}
                <a href="{{ route('events') }}"
                    class="flex items-center justify-center gap-2 border border-border bg-surface px-6 py-3 text-xs font-bold tracking-widest text-primary uppercase transition-all hover:bg-primary/10">
                    <x-heroicon-o-arrow-left class="size-4" />
                    Tutti gli eventi
                </a>

            </aside>
        </div>
    </section>

    {{-- ─────────────────────────────────────────────
         GALLERY
    ───────────────────────────────────────────── --}}
    @if ($event->media->isNotEmpty())
        <x-ui.section-header eyebrow="Media" title="Galleria">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
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
                            <div class="border-t border-border px-6 py-3 text-sm text-white/60">
                                {{ $media->caption }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </x-ui.section-header>
    @endif

    {{-- ─────────────────────────────────────────────
         RESOURCES
    ───────────────────────────────────────────── --}}
    @if ($event->resources->isNotEmpty())
        <x-ui.section-header eyebrow="Materiali" title="Risorse disponibili">
            <div class="overflow-hidden border border-border bg-surface">
                <div class="flex items-center justify-between border-b border-border bg-surface-900 px-6 py-3">
                    <div class="flex gap-2">
                        <div class="size-2 rounded-full bg-red-500/50"></div>
                        <div class="size-2 rounded-full bg-yellow-500/50"></div>
                        <div class="size-2 rounded-full bg-green-500/50"></div>
                    </div>
                    <span class="text-2xs font-mono text-primary/60 tracking-widest uppercase">root@salentodev:~/event/{{ $event->id }}/resources</span>
                </div>
                <div class="divide-y divide-border/50 font-mono text-xs">
                    @foreach ($event->resources->where('is_published', true) as $resource)
                        <div class="group flex items-center justify-between px-6 py-4 hover:bg-primary/5 transition-colors">
                            <div class="flex items-center gap-4">
                                @if ($resource->source_type === 'file')
                                    @if (str_ends_with($resource->file_path ?? '', '.zip') || str_ends_with($resource->file_path ?? '', '.rar'))
                                        <x-heroicon-o-archive-box class="size-4 text-primary" />
                                    @else
                                        <x-heroicon-o-document-text class="size-4 text-primary" />
                                    @endif
                                @else
                                    <x-heroicon-o-link class="size-4 text-primary" />
                                @endif
                                <span class="text-white">{{ $resource->title }}</span>
                                <span class="hidden sm:inline text-white/20">|</span>
                                <span class="hidden md:inline text-white/20">{{ $resource->source_type === 'file' ? 'File' : 'Link' }}</span>
                            </div>
                            @if ($resource->source_type === 'file' && $resource->file_path)
                                <a class="text-primary hover:underline uppercase tracking-widest text-2xs font-bold"
                                    href="{{ asset('storage/' . ltrim($resource->file_path, '/')) }}"
                                    download>Download</a>
                            @elseif ($resource->source_type === 'link' && $resource->external_url)
                                <a class="text-primary hover:underline uppercase tracking-widest text-2xs font-bold"
                                    href="{{ $resource->external_url }}" target="_blank"
                                    rel="noreferrer noopener">Apri</a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </x-ui.section-header>
    @endif

    {{-- ─────────────────────────────────────────────
         RELATED EVENTS
    ───────────────────────────────────────────── --}}
    @if ($relatedEvents->isNotEmpty())
        <x-ui.section-header eyebrow="Scopri anche" title="Altri eventi">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($relatedEvents as $relatedEvent)
                    <x-events.event :event="$relatedEvent" :variant="'simple'" />
                @endforeach
            </div>
        </x-ui.section-header>
    @endif

</div>
