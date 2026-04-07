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

<div class="flex flex-col gap-16">

    {{-- ─────────────────────────────────────────────
         MAIN CONTENT
    ───────────────────────────────────────────── --}}
    <section>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            {{-- Article body --}}
            <article class="lg:col-span-2">

                {{-- Excerpt --}}
                <p class="mb-8 text-lg leading-relaxed text-surface-300 border-l-2 border-primary pl-5">
                    {{ strip_tags($story->excerpt) }}
                </p>

                {{-- Body --}}
                <div class="prose prose-invert max-w-none
                    prose-p:text-surface-300
                    prose-li:text-surface-300
                    prose-strong:text-primary
                    prose-headings:text-white
                    prose-headings:uppercase
                    prose-headings:tracking-tighter
                    prose-a:text-primary prose-a:no-underline hover:prose-a:underline
                    prose-code:text-primary prose-code:bg-surface-800 prose-code:rounded-xs prose-code:px-1
                    prose-pre:bg-surface-800 prose-pre:border prose-pre:border-border">
                    {!! \Illuminate\Support\Str::markdown($story->body, [
                        'html_input' => 'strip',
                        'allow_unsafe_links' => false,
                    ]) !!}
                </div>
            </article>

            {{-- Sidebar --}}
            <aside class="lg:col-span-1 flex flex-col gap-6">

                {{-- Cover image --}}
                @if ($story->image_path)
                    <div class="border border-border bg-surface-900 overflow-hidden">
                        <img src="{{ asset('storage/' . ltrim($story->image_path, '/')) }}"
                            alt="{{ $story->title }}"
                            class="w-full aspect-video object-cover">
                    </div>
                @endif

                {{-- Author card --}}
                <div class="border border-border bg-surface-900 p-6 flex flex-col gap-4">
                    <h3 class="font-mono text-2xs uppercase tracking-widest text-primary">Autore</h3>
                    <div class="flex items-center gap-4">
                        <div class="size-12 rounded-full bg-surface-700 border border-primary/20 flex items-center justify-center shrink-0">
                            <x-heroicon-o-user class="size-6 text-primary/60" />
                        </div>
                        <div>
                            <p class="font-outfit font-bold text-surface-50">{{ $story->author }}</p>
                            @if ($story->author_role)
                                <p class="font-mono text-2xs uppercase tracking-widest text-surface-500 mt-0.5">{{ $story->author_role }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Meta --}}
                <div class="border border-border bg-surface-900 p-6 flex flex-col gap-3">
                    <h3 class="font-mono text-2xs uppercase tracking-widest text-primary">Dettagli</h3>
                    @if ($story->published_at)
                        <div class="flex items-center gap-3 text-sm text-surface-400">
                            <x-heroicon-o-calendar-days class="size-4 text-primary/50 shrink-0" />
                            {{ $story->published_at->translatedFormat('j F Y') }}
                        </div>
                    @endif
                    @if ($story->read_time_minutes)
                        <div class="flex items-center gap-3 text-sm text-surface-400">
                            <x-heroicon-o-clock class="size-4 text-primary/50 shrink-0" />
                            {{ $story->read_time_minutes }} minuti di lettura
                        </div>
                    @endif
                    @if ($story->category)
                        <div class="flex items-center gap-3 text-sm text-surface-400">
                            <x-heroicon-o-tag class="size-4 text-primary/50 shrink-0" />
                            {{ $story->category->name }}
                        </div>
                    @endif
                </div>

                {{-- Back link --}}
                <a href="{{ route('stories') }}"
                    class="flex items-center justify-center gap-2 border border-border bg-surface px-6 py-3 text-xs font-bold tracking-widest text-primary uppercase transition-all hover:bg-primary/10">
                    <x-heroicon-o-arrow-left class="size-4" />
                    Tutte le storie
                </a>

            </aside>
        </div>
    </section>

    {{-- ─────────────────────────────────────────────
         RELATED STORIES
    ───────────────────────────────────────────── --}}
    @if ($relatedStories->isNotEmpty())
        <x-ui.section-header eyebrow="Scopri anche" title="Storie correlate" size="small">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($relatedStories as $relatedStory)
                    <x-stories.story :story="$relatedStory" />
                @endforeach
            </div>
        </x-ui.section-header>
    @endif

</div>
