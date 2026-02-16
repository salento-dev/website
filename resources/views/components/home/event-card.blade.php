@props([
    'event',
    'href' => null,
    'variant' => 'default',
])

@if ($variant === 'archive')
    <article class="group relative flex h-full flex-col overflow-hidden rounded-sm border border-primary/20 bg-cyber-card/40 p-5 backdrop-blur-sm transition-all duration-300 hover:border-primary hover:shadow-[0_0_15px_rgba(13,231,242,0.25)]">
        <div class="absolute top-0 right-0 p-2">
            <span class="border border-primary/20 px-2 py-0.5 text-[10px] font-bold tracking-tighter text-primary/40">
                ARCHIVED_{{ $event->starts_at?->format('Y') }}
            </span>
        </div>

        <div class="relative mb-4 aspect-video overflow-hidden border border-primary/10">
            <img
                src="{{ $event->image_path ? asset('storage/' . ltrim($event->image_path, '/')) : asset('storage/placeholders/events/event-placeholder.jpg') }}"
                alt="{{ $event->title }}"
                class="h-full w-full object-cover opacity-60 transition-opacity duration-500 group-hover:opacity-100"
            >
            <div class="absolute inset-0 bg-linear-to-t from-background-dark/80 to-transparent"></div>
        </div>

        <div class="mb-4">
            <div class="mb-2 inline-block bg-primary px-2 py-1 text-[10px] font-black tracking-tighter text-background-dark">
                {{ $event->starts_at?->format('d_M_Y') }}
            </div>

            @if ($event->relationLoaded('categories') && $event->categories->isNotEmpty())
                <div class="mb-2 flex flex-wrap gap-1">
                    @foreach ($event->categories->take(3) as $category)
                        <span class="border border-primary/25 px-2 py-0.5 text-[9px] font-bold tracking-widest text-primary/80 uppercase">
                            {{ $category->name }}
                        </span>
                    @endforeach
                </div>
            @endif

            <h3 class="text-xl leading-tight font-bold tracking-tight text-white uppercase transition-colors group-hover:text-primary">
                {{ $event->title }}
            </h3>
        </div>

        <p class="mb-6 grow text-sm leading-relaxed font-light text-primary/70">
            {{ \Illuminate\Support\Str::limit(strip_tags($event->description), 150) }}
        </p>

        <div class="mt-auto border-t border-primary/10 pt-4">
            <div class="mb-3 flex items-center gap-1 text-[10px] text-primary/40 uppercase">
                <span class="material-symbols-outlined text-sm">location_on</span>
                {{ $event->location }}
            </div>

            <div class="flex flex-wrap gap-2">
                @if (($event->media_count ?? 0) > 0)
                    <span class="flex items-center gap-1 border border-primary/30 px-3 py-1 text-[10px] font-bold tracking-widest text-primary uppercase">
                        <span class="material-symbols-outlined text-xs">description</span>
                        Materials
                    </span>
                @endif

                @if (($event->video_media_count ?? 0) > 0)
                    <span class="flex items-center gap-1 border border-primary/30 px-3 py-1 text-[10px] font-bold tracking-widest text-primary uppercase">
                        <span class="material-symbols-outlined text-xs">play_circle</span>
                        Video
                    </span>
                @endif

                @if (($event->image_media_count ?? 0) > 0)
                    <span class="flex items-center gap-1 border border-primary/30 px-3 py-1 text-[10px] font-bold tracking-widest text-primary uppercase">
                        <span class="material-symbols-outlined text-xs">imagesmode</span>
                        Gallery
                    </span>
                @endif

                @if ($href)
                    <a
                        href="{{ $href }}"
                        class="flex items-center gap-1 border border-primary/30 px-3 py-1 text-[10px] font-bold tracking-widest text-primary uppercase transition-colors hover:bg-primary/20"
                        aria-label="Vai al dettaglio evento"
                    >
                        <span class="material-symbols-outlined text-xs">arrow_forward</span>
                        Apri
                    </a>
                @endif
            </div>
        </div>
    </article>
@else
    <article class="cyber-panel group flex h-full flex-col gap-4 border border-cyber-border bg-cyber-card p-6 transition-all hover:border-primary">
        <div class="flex items-start justify-between gap-3">
            <div class="border border-primary/30 bg-primary/10 px-2 py-1 text-[10px] font-bold text-primary">EVENTO</div>
            <span class="font-mono text-[10px] text-primary/40">{{ $event->starts_at?->format('d.m.Y_H:i') }}</span>
        </div>

        <h3 class="text-lg font-bold transition-colors group-hover:text-primary">{{ strtoupper($event->title) }}</h3>
        <p class="text-sm text-primary/60">{{ \Illuminate\Support\Str::limit($event->description, 110) }}</p>

        <div class="mt-auto flex items-center justify-between border-t border-cyber-border pt-4">
            <div class="flex items-center gap-1 text-[10px] text-primary/40 uppercase">
                <span class="material-symbols-outlined text-sm">location_on</span>
                {{ $event->location }}
            </div>
            @if ($href)
                <a
                    href="{{ $href }}"
                    class="flex size-8 items-center justify-center border border-cyber-border transition-all group-hover:border-primary group-hover:bg-primary group-hover:text-background-dark"
                    aria-label="Vai al dettaglio evento"
                >
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            @else
                <span class="flex size-8 items-center justify-center border border-cyber-border transition-all group-hover:border-primary group-hover:bg-primary group-hover:text-background-dark">
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </span>
            @endif
        </div>
    </article>
@endif