<div class="relative flex min-h-screen flex-col">
    <main class="flex-1">
        <!-- Hero Section -->
        <section class="relative bg-primary/5 border-b border-border-cyan px-6 py-20 lg:px-20">
            <div class="container">
                <div class="inline-block rounded border border-primary/20 bg-primary/5 px-4 py-1 text-[10px] font-bold uppercase tracking-[0.3em] text-primary mb-6">
                    Event Detail // ID: {{ $event->id }}
                </div>
                
                <h1 class="mb-6 text-4xl font-black uppercase leading-tight tracking-tighter sm:text-5xl lg:text-6xl text-white">
                    {{ $event->title }}
                </h1>

                @if ($event->categories->isNotEmpty())
                    <div class="mb-6 flex flex-wrap gap-2">
                        @foreach ($event->categories as $category)
                            <span class="px-3 py-1 rounded bg-white/5 border border-white/10 text-[10px] uppercase text-white/60 font-bold tracking-widest">
                                {{ $category->name }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-white/60">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">event</span>
                        <span>{{ $event->starts_at?->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">location_on</span>
                        <span>{{ $event->location }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">pin_drop</span>
                        <span>{{ $event->address ?: 'Indirizzo da definire' }}</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Content Section -->
        <section class="container py-24">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Main Content -->
                <article class="lg:col-span-2">
                    <h2 class="text-[10px] font-bold uppercase tracking-[0.5em] text-primary mb-4">EVENT_DESCRIPTION</h2>
                    <div class="prose prose-invert max-w-none prose-p:text-white/80 prose-li:text-white/80 prose-strong:text-primary prose-headings:text-white prose-headings:uppercase prose-headings:tracking-tighter">
                        {!! \Illuminate\Support\Str::markdown(
                            $event->description,
                            [
                                'html_input' => 'strip',
                                'allow_unsafe_links' => false,
                            ],
                        ) !!}
                    </div>
                </article>

                <!-- Sidebar -->
                <aside class="lg:col-span-1">
                    <div class="border border-border-cyan bg-card-dark overflow-hidden mb-6">
                        <img
                            src="{{ $event->image_path ? asset('storage/' . ltrim($event->image_path, '/')) : asset('storage/placeholders/events/event-placeholder.jpg') }}"
                            alt="{{ $event->title }}"
                            class="w-full aspect-video object-cover"
                        >
                    </div>

                    <a
                        href="{{ route('events') }}"
                        class="w-full flex items-center justify-center gap-2 border border-border-cyan bg-background-dark px-6 py-3 text-xs font-bold tracking-widest text-primary uppercase transition-all hover:bg-primary/10"
                    >
                        <span class="material-symbols-outlined text-base">arrow_back</span>
                        Back to Events
                    </a>
                </aside>
            </div>
        </section>

        <!-- Gallery Section -->
        @if ($event->media->isNotEmpty())
            <section class="bg-primary/5 border-y border-border-cyan py-24">
                <div class="container">
                    <div class="mb-12">
                        <h2 class="text-[10px] font-bold uppercase tracking-[0.5em] text-primary mb-4">MEDIA_GALLERY</h2>
                        <h3 class="text-4xl font-bold uppercase tracking-tighter text-white">Event Gallery</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach ($event->media as $media)
                            <div class="border border-border-cyan bg-background-dark overflow-hidden">
                                @if ($media->source_type === 'file' && filled($media->file_path))
                                    @if ($media->media_type === 'video')
                                        <video controls class="w-full aspect-video bg-black object-cover">
                                            <source src="{{ asset('storage/' . ltrim($media->file_path, '/')) }}">
                                        </video>
                                    @else
                                        <img
                                            src="{{ asset('storage/' . ltrim($media->file_path, '/')) }}"
                                            alt="{{ $media->caption ?: $event->title }}"
                                            class="w-full aspect-video object-cover"
                                        >
                                    @endif
                                @elseif ($media->source_type === 'url' && filled($media->external_url))
                                    @if ($this->toEmbedUrl($media->external_url))
                                        <iframe
                                            src="{{ $this->toEmbedUrl($media->external_url) }}"
                                            class="w-full aspect-video"
                                            loading="lazy"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen
                                        ></iframe>
                                    @else
                                        <a
                                            href="{{ $media->external_url }}"
                                            target="_blank"
                                            rel="noreferrer noopener"
                                            class="flex aspect-video items-center justify-center px-4 text-center text-sm text-primary transition-colors hover:text-primary/80"
                                        >
                                            Apri video esterno
                                        </a>
                                    @endif
                                @endif

                                @if (filled($media->caption))
                                    <div class="border-t border-border-cyan px-6 py-3 text-sm text-white/60">
                                        {{ $media->caption }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <!-- Resources Section -->
        @if ($event->resources->isNotEmpty())
        <x-ui.section-header 
            eyebrow="EVENT_RESOURCES" 
            title='Risorse Disponibili'
        >
            <div class="overflow-hidden border border-border-cyan bg-background-dark">
                <!-- Header -->
                <div class="flex items-center justify-between border-b border-border-cyan bg-card-dark px-6 py-3">
                    <div class="flex gap-2">
                        <div class="size-2 rounded-full bg-red-500/50"></div>
                        <div class="size-2 rounded-full bg-yellow-500/50"></div>
                        <div class="size-2 rounded-full bg-green-500/50"></div>
                    </div>
                    <span class="text-[10px] font-mono text-primary/60 tracking-widest uppercase">root@salentodev:~/event/{{ $event->id }}/resources</span>
                </div>
                <!-- Terminal Body -->
                <div class="divide-y divide-border-cyan/50 font-mono text-xs">
                    @foreach ($event->resources->where('is_published', true) as $resource)
                        <div class="group flex items-center justify-between px-6 py-4 hover:bg-primary/5 transition-colors">
                            <div class="flex items-center gap-4">
                                <span class="material-symbols-outlined text-primary">
                                    @if ($resource->source_type === 'file')
                                        @if (str_ends_with($resource->file_path ?? '', '.pdf'))
                                            description
                                        @elseif (str_ends_with($resource->file_path ?? '', '.zip') || str_ends_with($resource->file_path ?? '', '.rar'))
                                            folder_zip
                                        @else
                                            description
                                        @endif
                                    @else
                                        link
                                    @endif
                                </span>
                                <span class="text-white">{{ $resource->title }}</span>
                                <span class="hidden sm:inline text-white/20">|</span>
                                <span class="hidden md:inline text-white/20">{{ $resource->source_type === 'file' ? 'File' : 'Link' }}</span>
                            </div>
                            @if ($resource->source_type === 'file' && $resource->file_path)
                                <a class="text-primary hover:underline uppercase tracking-widest text-[10px] font-bold" href="{{ asset('storage/' . ltrim($resource->file_path, '/')) }}" download>Download</a>
                            @elseif ($resource->source_type === 'link' && $resource->external_url)
                                <a class="text-primary hover:underline uppercase tracking-widest text-[10px] font-bold" href="{{ $resource->external_url }}" target="_blank" rel="noreferrer noopener">Apri</a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </x-ui.section-header>
        @endif

        <!-- Related Events Section -->
        @if ($relatedEvents->isNotEmpty())
        <x-ui.section-header 
            eyebrow="RELATED_EVENTS" 
            title='Altri Eventi'
        >
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach ($relatedEvents as $relatedEvent)
                    <x-cards.event :event="$relatedEvent" />
                @endforeach
            </div>
        </x-ui.section-header>
        @endif
    </main>
</div>