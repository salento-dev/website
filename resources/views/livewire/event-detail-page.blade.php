<div class="relative flex min-h-screen flex-col overflow-x-hidden bg-grid">
    <x-home.header />

    <main class="mx-auto flex w-full max-w-7xl flex-1 flex-col gap-10 px-6 py-10 lg:px-20">
        <section class="relative overflow-hidden border border-primary/20 bg-cyber-card/50 p-8">
            <p class="mb-3 text-xs font-bold tracking-[0.2em] text-primary/50 uppercase">EVENT_DETAIL</p>
            <h1 class="mb-4 text-3xl font-black tracking-tighter text-primary uppercase md:text-5xl">{{ $event->title }}</h1>

            @if ($event->categories->isNotEmpty())
                <div class="mb-4 flex flex-wrap gap-2">
                    @foreach ($event->categories as $category)
                        <span class="border border-primary/30 px-2 py-1 text-[10px] font-bold tracking-widest text-primary uppercase">
                            {{ $category->name }}
                        </span>
                    @endforeach
                </div>
            @endif

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
                <div class="prose prose-invert max-w-none prose-p:text-primary/80 prose-li:text-primary/80 prose-strong:text-primary prose-headings:text-primary">
                    {!! \Illuminate\Support\Str::markdown(
                        $event->description,
                        [
                            'html_input' => 'strip',
                            'allow_unsafe_links' => false,
                        ],
                    ) !!}
                </div>
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
            <div class="mb-8 flex items-center gap-4">
                <h2 class="flex items-center gap-2 text-2xl font-black tracking-tighter text-primary uppercase">
                    <span class="material-symbols-outlined">photo_library</span>
                    Galleria
                </h2>
                <div class="flex-1 border-t border-dashed border-primary/20"></div>
            </div>

            @if ($event->media->isEmpty())
                <p class="text-sm text-primary/60">Nessun media disponibile per questo evento.</p>
            @else
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    @foreach ($event->media as $media)
                        <div class="overflow-hidden border border-cyber-border bg-cyber-card">
                            @if ($media->source_type === 'file' && filled($media->file_path))
                                @if ($media->media_type === 'video')
                                    <video controls class="h-72 w-full bg-black object-cover">
                                        <source src="{{ asset('storage/' . ltrim($media->file_path, '/')) }}">
                                    </video>
                                @else
                                    <img
                                        src="{{ asset('storage/' . ltrim($media->file_path, '/')) }}"
                                        alt="{{ $media->caption ?: $event->title }}"
                                        class="h-72 w-full object-cover"
                                    >
                                @endif
                            @elseif ($media->source_type === 'url' && filled($media->external_url))
                                @if ($this->toEmbedUrl($media->external_url))
                                    <iframe
                                        src="{{ $this->toEmbedUrl($media->external_url) }}"
                                        class="h-72 w-full"
                                        loading="lazy"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen
                                    ></iframe>
                                @else
                                    <a
                                        href="{{ $media->external_url }}"
                                        target="_blank"
                                        rel="noreferrer noopener"
                                        class="flex h-72 items-center justify-center px-4 text-center text-sm text-primary transition-colors hover:text-primary/80"
                                    >
                                        Apri video esterno
                                    </a>
                                @endif
                            @endif

                            @if (filled($media->caption))
                                <div class="border-t border-cyber-border px-4 py-3 text-sm text-primary/70">
                                    {{ $media->caption }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
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