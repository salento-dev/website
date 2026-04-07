@props(['story'])

@php
    $imageUrl = $story->image_path
        ? asset('storage/' . ltrim($story->image_path, '/'))
        : null;
@endphp

<article class="group flex flex-col bg-surface-900 border border-border transition-all duration-300 hover:border-primary/20 hover:bg-surface-800">

    {{-- Image --}}
    @if ($imageUrl)
        <a href="{{ route('stories.show', $story) }}" class="block overflow-hidden" tabindex="-1" aria-hidden="true">
            <div class="aspect-video overflow-hidden">
                <img src="{{ $imageUrl }}" alt="{{ $story->title }}"
                    class="w-full h-full object-cover opacity-70 grayscale transition-all duration-500 group-hover:opacity-90 group-hover:grayscale-0 group-hover:scale-105"
                    loading="lazy">
            </div>
        </a>
    @endif

    <div class="flex flex-col flex-1 gap-4 p-6">

        {{-- Category badge --}}
        <div class="flex items-center gap-2">
            @if ($story->category)
                <span class="inline-flex items-center rounded-xs bg-surface-700 px-2.5 py-1 font-mono text-2xs uppercase tracking-widest text-surface-300 border border-border">
                    {{ $story->category->name }}
                </span>
            @endif
            @if ($story->read_time_minutes)
                <span class="font-mono text-2xs uppercase tracking-widest text-surface-500">
                    {{ $story->read_time_minutes }} min
                </span>
            @endif
        </div>

        {{-- Title --}}
        <a href="{{ route('stories.show', $story) }}" class="block">
            <h4 class="font-outfit text-lg font-bold text-surface-50 leading-snug transition-colors duration-300 group-hover:text-primary line-clamp-2">
                {{ $story->title }}
            </h4>
        </a>

        {{-- Excerpt --}}
        <p class="text-sm leading-relaxed text-surface-400 line-clamp-3 flex-1">
            {{ strip_tags($story->excerpt) }}
        </p>

        {{-- Footer --}}
        <div class="flex items-center justify-between pt-4 border-t border-border/50">
            <div class="flex items-center gap-2">
                <div class="size-7 rounded-full bg-surface-700 border border-border flex items-center justify-center">
                    <x-heroicon-o-user class="size-3.5 text-surface-400" />
                </div>
                <span class="text-xs font-bold text-surface-200">{{ $story->author }}</span>
            </div>
            <a href="{{ route('stories.show', $story) }}"
                class="font-mono text-2xs uppercase tracking-widest text-primary/60 transition-colors duration-300 hover:text-primary"
                aria-label="Leggi {{ $story->title }}">
                Leggi
                <x-heroicon-o-arrow-top-right-on-square class="inline h-3 ml-0.5" />
            </a>
        </div>
    </div>
</article>
