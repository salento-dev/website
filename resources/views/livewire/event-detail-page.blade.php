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

    <h1 class="mb-6 text-4xl font-black uppercase leading-tight tracking-tighter text-white sm:text-5xl lg:text-6xl">
        {{ $event->title }}
    </h1>
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
        <x-ui.section-header eyebrow="Materiali" title="Risorse disponibili">
            <div class="overflow-hidden border border-border bg-surface">
                <div class="flex items-center justify-between border-b border-border bg-surface-900 px-4 py-3 gap-4">
                    <div class="flex shrink-0 gap-2">
                        <div class="size-2 rounded-full bg-red-500/50"></div>
                        <div class="size-2 rounded-full bg-yellow-500/50"></div>
                        <div class="size-2 rounded-full bg-green-500/50"></div>
                    </div>
                    <span class="truncate text-2xs font-mono text-primary/60 tracking-widest uppercase">root@salentodev:~/event/{{ $event->id }}/resources</span>
                </div>
                <div class="divide-y divide-border/50 font-mono text-xs">
                    @foreach ($event->resources->where('is_published', true) as $resource)
                        <div class="group flex items-center justify-between gap-4 px-4 py-4 hover:bg-primary/5 transition-colors">
                            <div class="flex min-w-0 items-center gap-3">
                                @if ($resource->source_type === 'file')
                                    @if (str_ends_with($resource->file_path ?? '', '.zip') || str_ends_with($resource->file_path ?? '', '.rar'))
                                        <x-heroicon-o-archive-box class="size-4 shrink-0 text-primary" />
                                    @else
                                        <x-heroicon-o-document-text class="size-4 shrink-0 text-primary" />
                                    @endif
                                @else
                                    <x-heroicon-o-link class="size-4 shrink-0 text-primary" />
                                @endif
                                <span class="truncate text-white">{{ $resource->title }}</span>
                            </div>
                            @if ($resource->source_type === 'file' && $resource->file_path)
                                <a class="shrink-0 text-primary hover:underline uppercase tracking-widest text-2xs font-bold"
                                    href="{{ asset('storage/' . ltrim($resource->file_path, '/')) }}"
                                    download>Download</a>
                            @elseif ($resource->source_type === 'link' && $resource->external_url)
                                <a class="shrink-0 text-primary hover:underline uppercase tracking-widest text-2xs font-bold"
                                    href="{{ $resource->external_url }}" target="_blank"
                                    rel="noreferrer noopener">Apri</a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </x-ui.section-header>
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
