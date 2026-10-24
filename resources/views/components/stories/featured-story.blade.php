@props(['story'])

<article class="group relative">
    {{-- Corner accents --}}
    <x-ui.corners color="text-primary" :hover="true" size="h-3" />

    <a href="{{ route('stories.show', $story) }}" aria-label="Leggi: {{ $story->title }}"
        class="flex flex-col lg:flex-row bg-surface-900 border border-border overflow-hidden transition-all duration-300 hover:border-primary/30 hover:bg-surface-800">

        {{-- Image --}}
        @if ($story->image_url)
            <div class="lg:w-1/2 h-64 lg:h-auto lg:max-h-128 relative overflow-hidden">
                <img src="{{ $story->image_url }}" alt="{{ $story->title }}"
                    class="w-full h-full object-cover transition-all duration-500 group-hover:scale-105">
                <div class="absolute inset-0 bg-linear-to-r from-surface-900 via-surface-900/40 to-transparent hidden lg:block"></div>
            </div>
        @else
            <div class="lg:w-1/2 h-64 lg:h-auto bg-surface-800 flex items-center justify-center">
                <x-heroicon-o-book-open class="size-16 text-surface-600" />
            </div>
        @endif

        {{-- Content --}}
        <div class="lg:w-1/2 p-8 lg:p-10 flex flex-col justify-center gap-6">

            {{-- Meta --}}
            <div class="flex items-center gap-3 flex-wrap">
                @if ($story->category)
                    <span class="inline-flex items-center rounded-xs border border-primary/20 bg-primary/10 px-3 py-1 font-mono text-2xs uppercase tracking-widest text-primary">
                        {{ $story->category->name }}
                    </span>
                @endif
                @if ($story->read_time_minutes)
                    <span class="font-mono text-2xs uppercase tracking-widest text-surface-400">
                        {{ $story->read_time_minutes }} min di lettura
                    </span>
                @endif
            </div>

            {{-- Title --}}
            <h3 class="font-outfit text-2xl md:text-3xl font-bold text-surface-50 leading-tight transition-colors duration-300 group-hover:text-primary">
                {{ $story->title }}
            </h3>

            {{-- Excerpt --}}
            <p class="text-sm leading-relaxed text-surface-400 line-clamp-3">
                {{ strip_tags($story->excerpt) }}
            </p>

            {{-- Authors + CTA --}}
            <div class="flex items-center justify-between mt-auto flex-wrap gap-4">
                @if ($story->authors->isNotEmpty())
                    <div class="flex items-center gap-3">
                        {{-- Stacked avatars --}}
                        <div class="flex -space-x-2">
                            @foreach ($story->authors->take(3) as $author)
                                @php $avatarUrl = $author->avatar_url; @endphp
                                <div class="size-9 rounded-full border-2 border-surface-900 overflow-hidden bg-surface-700 flex items-center justify-center shrink-0"
                                     title="{{ $author->name }}">
                                    @if ($avatarUrl)
                                        <img loading="lazy" src="{{ $avatarUrl }}" alt="{{ $author->name }}" class="size-full object-cover">
                                    @else
                                        <x-heroicon-o-user class="size-4 text-primary/60" />
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div>
                            <p class="text-sm font-bold text-surface-50">
                                {{ $story->authors->pluck('name')->join(', ') }}
                            </p>
                            @if ($story->authors->count() === 1 && filled($story->authors->first()->role))
                                <p class="font-mono text-2xs uppercase tracking-widest text-surface-400">{{ $story->authors->first()->role }}</p>
                            @endif
                        </div>
                    </div>
                @endif

                <span class="inline-flex items-center gap-2 font-mono text-xs font-bold uppercase tracking-widest text-primary transition-all duration-300 group-hover:gap-3">
                    Leggi la storia
                    <x-heroicon-o-arrow-right class="h-3.5 transition-transform duration-300 group-hover:translate-x-1" />
                </span>
            </div>
        </div>
    </a>
</article>
