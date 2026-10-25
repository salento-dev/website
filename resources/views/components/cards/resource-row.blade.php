@props(['resource'])

@php
    $isFile = $resource->source_type === 'file' && filled($resource->file_path);
    $authors = $resource->relationLoaded('authors') ? $resource->authors : collect();
@endphp

<div x-data="{ open: false }">

    {{-- Row --}}
    <button type="button" @click="open = true"
        class="group flex gap-4 w-full cursor-pointer items-center justify-between p-4 text-left transition-colors hover:bg-primary/5">
        <div class="flex items-center gap-4">
            @if($isFile)
                <x-heroicon-o-document-text class="size-4 shrink-0 text-primary/40 transition-colors group-hover:text-primary" />
            @else
                <x-heroicon-o-link class="size-4 shrink-0 text-primary/40 transition-colors group-hover:text-primary" />
            @endif
            <div class="flex flex-col gap-0.5">
                <span class="text-sm transition-colors group-hover:text-primary">{{ $resource->title }}</span>
            </div>

            <span class="hidden shrink-0 whitespace-nowrap font-mono text-2xs uppercase tracking-wider text-surface-400 sm:inline">{{ $resource->created_at?->diffForHumans() }}</span>
        </div>
        <div class="flex items-center gap-2 text-2xs">
            @if($authors->isNotEmpty())
                <div class="flex items-center">
                    @foreach($authors->take(3) as $author)
                        <img loading="lazy"
                            src="{{ $author->avatar_url }}"
                            alt="{{ $author->name }}"
                            title="{{ $author->name }}"
                            class="-ml-2 size-5 min-w-5 rounded-full border border-surface-800 object-cover first:ml-0"
                        />
                    @endforeach
                </div>
            @endif
            
            <x-heroicon-o-chevron-right class="size-3 text-primary/40 transition-colors group-hover:text-primary" />
        </div>
    </button>

    {{-- Modal --}}
    <template x-teleport="body">
    <div
        x-show="open"
        x-transition:enter="transition duration-200 ease-out"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition duration-150 ease-in"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click.self="open = false"
        @keydown.escape.window="open = false"
        role="dialog"
        aria-modal="true"
        aria-labelledby="resource-{{ $resource->id }}-title"
        class="fixed inset-0 z-100 flex items-center justify-center bg-black/80 p-4 backdrop-blur-sm"
        style="display: none;"
    >
        <div x-trap.noscroll="open" class="relative flex w-full max-w-sm flex-col gap-5 border border-border bg-surface-900 p-6">
            <x-ui.corners />

            {{-- Close --}}
            <button type="button" @click="open = false"
                class="absolute top-4 right-4 flex items-center gap-1.5 font-mono text-2xs uppercase tracking-widest text-surface-400 transition-colors hover:text-primary">
                <x-heroicon-o-x-mark class="size-4" />
                Chiudi
            </button>

            {{-- Type + title --}}
            <div class="flex items-start gap-3 pr-16">
                @if($isFile)
                    <x-heroicon-o-document-text class="mt-0.5 size-4 shrink-0 text-primary" />
                @else
                    <x-heroicon-o-link class="mt-0.5 size-4 shrink-0 text-primary" />
                @endif
                <h2 id="resource-{{ $resource->id }}-title" class="font-outfit text-sm font-semibold leading-snug text-surface-50">{{ $resource->title }}</h2>
            </div>

            {{-- Description --}}
            @if(filled($resource->description))
                <p class="text-xs leading-relaxed text-surface-400">{{ $resource->description }}</p>
            @endif

            {{-- Authors --}}
            @if($authors->isNotEmpty())
                <div class="flex flex-col gap-4 border-t border-border/50 pt-4">
                    @foreach($authors as $author)
                        <x-cards.author :author="$author" />
                        @if(!$loop->last)
                            <hr class="border-border/30">
                        @endif
                    @endforeach
                </div>
            @endif

            {{-- CTA --}}
            @if(filled($resource->resource_url))
            <a
                href="{{ $resource->resource_url ?? '#' }}"
                data-track="resource"
                data-track-label="{{ $resource->title }}"
                target="_blank"
                rel="noreferrer noopener"
                class="flex items-center justify-center gap-2 border border-primary/30 bg-primary/10 px-4 py-3 font-mono text-xs font-bold uppercase tracking-widest text-primary transition-colors hover:bg-primary/20"
            >
                @if($isFile)
                    <x-heroicon-o-arrow-down-tray class="size-4" />
                    Scarica il file<span class="sr-only">, si apre in una nuova scheda</span>
                @else
                    <x-heroicon-o-arrow-top-right-on-square class="size-4" />
                    Apri il link<span class="sr-only">, si apre in una nuova scheda</span>
                @endif
            </a>
            @endif
        </div>
    </div>
    </template>
</div>
