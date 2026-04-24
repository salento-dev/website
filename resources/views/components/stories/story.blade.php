@props(['story', 'variant' => 'default'])

<a href="{{ route('stories.show', $story) }}"
    class="group flex flex-col bg-surface-900 border border-border transition-all duration-300 hover:border-primary/20 hover:bg-surface-800">

    {{-- Image --}}
    @if ($variant === 'default' && $story->image_url)
        <div class="overflow-hidden">
            <div class="aspect-video overflow-hidden">
                <img src="{{ $story->image_url }}" alt="{{ $story->title }}"
                    class="w-full h-full object-cover opacity-70 grayscale transition-all duration-500 group-hover:opacity-90 group-hover:grayscale-0 group-hover:scale-105"
                    loading="lazy">
            </div>
        </div>
    @endif

    <div class="flex flex-col flex-1 gap-4 p-5">

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
        <h4 class="font-outfit text-lg font-bold text-surface-50 leading-snug transition-colors duration-300 group-hover:text-primary line-clamp-2">
            {{ $story->title }}
        </h4>

        {{-- Excerpt --}}
        <p class="text-sm leading-relaxed text-surface-400 line-clamp-3 flex-1">
            {{ strip_tags($story->excerpt) }}
        </p>

        {{-- Footer --}}
        <div class="flex items-center justify-between pt-4 border-t border-border/50">
            @if ($story->authors->isNotEmpty())
                <div class="flex items-center gap-2">
                    <div class="flex -space-x-1.5">
                        @foreach ($story->authors->take(3) as $author)
                            @php $avatarUrl = $author->avatar_url; @endphp
                            <div class="size-7 rounded-full border-2 border-surface-900 overflow-hidden bg-surface-700 flex items-center justify-center shrink-0"
                                 title="{{ $author->name }}">
                                @if ($avatarUrl)
                                    <img src="{{ $avatarUrl }}" alt="{{ $author->name }}" class="size-full object-cover" loading="lazy">
                                @else
                                    <x-heroicon-o-user class="size-3 text-surface-400" />
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <span class="text-xs font-bold text-surface-200 line-clamp-1">
                        {{ $story->authors->pluck('name')->join(', ') }}
                    </span>
                </div>
            @else
                <span class="min-h-7"></span>
            @endif
            <span class="font-mono text-2xs uppercase tracking-widest text-primary/60 transition-colors duration-300 group-hover:text-primary shrink-0">
                Leggi
                <x-heroicon-o-arrow-top-right-on-square class="inline h-3 ml-0.5" />
            </span>
        </div>
    </div>
</a>
