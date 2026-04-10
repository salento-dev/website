@section('hero')
    @if ($story->category)
        <div class="mb-6 inline-block rounded border border-primary/20 bg-primary/5 px-4 py-1 text-2xs font-bold uppercase tracking-[0.3em] text-primary">
            {{ $story->category->name }}
        </div>
    @endif

    <h1 class="mb-6 text-4xl font-black uppercase leading-tight tracking-tighter text-white sm:text-5xl lg:text-6xl">
        {{ $story->title }}
    </h1>
@endsection

<x-ui.detail-layout>

    {{-- Meta mobile --}}
    <x-slot:mobile-meta>
        @include('livewire.partials.story-meta-card')
    </x-slot:mobile-meta>

    {{-- Articolo --}}
    <x-slot:article>
        @if (!empty($story->excerpt))
            <p class="mb-8 text-base md:text-lg leading-relaxed text-surface-300 border-l-2 border-primary pl-5">
                {{ strip_tags($story->excerpt) }}
            </p>
        @endif

        <div class="prose prose-invert prose-sm md:prose-base max-w-none">
            {!! \Illuminate\Support\Str::markdown($story->body, [
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
            ]) !!}
        </div>
    </x-slot:article>

    {{-- Sidebar --}}
    <x-slot:sidebar>
        @if ($story->image_path)
            <x-ui.lightbox
                :src="asset('storage/' . ltrim($story->image_path, '/'))"
                :alt="$story->title"
                thumbnail="h-auto"
            />
        @endif

        @if ($story->authors->isNotEmpty())
            <div class="border border-border bg-surface-900 p-5 flex flex-col gap-4">
                <h3 class="font-mono text-2xs uppercase tracking-widest text-primary">
                    {{ $story->authors->count() > 1 ? 'Autori' : 'Autore' }}
                </h3>
                @foreach ($story->authors as $author)
                    <div class="flex items-center gap-4">
                        <div class="size-12 rounded-full overflow-hidden bg-surface-700 border border-primary/20 flex items-center justify-center shrink-0">
                            @if ($author->avatar_url)
                                <img src="{{ $author->avatar_url }}" alt="{{ $author->name }}" class="size-full object-cover">
                            @else
                                <x-heroicon-o-user class="size-6 text-primary/60" />
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="font-outfit font-bold text-surface-50 truncate">{{ $author->name }}</p>
                            @if (filled($author->role))
                                <p class="font-mono text-2xs uppercase tracking-widest text-surface-500 mt-0.5">{{ $author->role }}</p>
                            @endif
                            @if (filled($author->github_url) || filled($author->linkedin_url) || filled($author->twitter_url) || filled($author->website_url))
                                <div class="flex items-center gap-3 mt-2">
                                    @if (filled($author->github_username))
                                        <a href="{{ $author->github_url }}" target="_blank" rel="noreferrer noopener"
                                           class="text-surface-400 hover:text-primary transition-colors" title="GitHub">
                                            <x-heroicon-o-code-bracket class="size-4" />
                                        </a>
                                    @endif
                                    @if (filled($author->linkedin_url))
                                        <a href="{{ $author->linkedin_url }}" target="_blank" rel="noreferrer noopener"
                                           class="text-surface-400 hover:text-primary transition-colors" title="LinkedIn">
                                            <x-heroicon-o-briefcase class="size-4" />
                                        </a>
                                    @endif
                                    @if (filled($author->twitter_url))
                                        <a href="{{ $author->twitter_url }}" target="_blank" rel="noreferrer noopener"
                                           class="text-surface-400 hover:text-primary transition-colors" title="X / Twitter">
                                            <x-heroicon-o-chat-bubble-left class="size-4" />
                                        </a>
                                    @endif
                                    @if (filled($author->website_url))
                                        <a href="{{ $author->website_url }}" target="_blank" rel="noreferrer noopener"
                                           class="text-surface-400 hover:text-primary transition-colors" title="Sito web">
                                            <x-heroicon-o-globe-alt class="size-4" />
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    @if (!$loop->last)
                        <hr class="border-border/50">
                    @endif
                @endforeach
            </div>
        @endif

        @include('livewire.partials.story-meta-card', ['classes' => 'hidden lg:flex'])

        <a href="{{ route('stories') }}"
            class="flex items-center justify-center gap-2 border border-border bg-surface px-6 py-3 text-xs font-bold tracking-widest text-primary uppercase transition-all hover:bg-primary/10">
            <x-heroicon-o-arrow-left class="size-4" />
            Tutte le storie
        </a>
    </x-slot:sidebar>

    {{-- Sezioni aggiuntive --}}

    @if ($relatedStories->isNotEmpty())
        <x-ui.section-header eyebrow="Scopri anche" title="Storie correlate" size="small">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($relatedStories as $relatedStory)
                    <x-stories.story :story="$relatedStory" />
                @endforeach
            </div>
        </x-ui.section-header>
    @endif

</x-ui.detail-layout>
