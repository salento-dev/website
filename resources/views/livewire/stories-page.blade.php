@section('hero')
    <x-sections.page-hero :badge="'Storie dalla community'" :description="'Il valore degli esperimenti tecnici condivisi: fallimenti, scoperte e codice reale che nasce dal confronto quotidiano tra sviluppatori.'">
        Cose concrete che <span class="text-primary text-glow">stanno succedendo</span>
    </x-sections.page-hero>
@endsection

<div class="flex flex-col gap-20">

    {{-- ─────────────────────────────────────────────
         FEATURED STORY
    ───────────────────────────────────────────── --}}
    @if ($featuredStory)
        <x-ui.section-header eyebrow="In Evidenza" title="L'ultima <span class='text-primary'>storia</span>">
            <x-stories.featured-story :story="$featuredStory" />
        </x-ui.section-header>
    @endif

    {{-- ─────────────────────────────────────────────
         ALL STORIES GRID
    ───────────────────────────────────────────── --}}
    <x-ui.section-header title="Tutte le storie" tag="Storie: {{ $stories->total() }}" size="small">
        <div class="flex flex-col gap-6">

            {{-- Filters row --}}
            <div class="flex flex-col gap-4">
                <x-ui.input-search wire-model="search" placeholder="Cerca storie..."
                    sr-label="Cerca storie per titolo, estratto o autore" />

                {{-- Category filter --}}
                @if ($categories->isNotEmpty())
                    <div class="hidden md:flex flex-wrap gap-2">
                        <button wire:click="$set('categoryId', null)"
                            class="rounded-xs border px-3 py-1.5 font-mono text-2xs uppercase tracking-widest transition-colors duration-200 {{ $categoryId === null ? 'border-primary bg-primary/10 text-primary' : 'border-border bg-surface-800 text-surface-400 hover:border-primary/30 hover:text-surface-200' }}">
                            Tutte
                        </button>
                        @foreach ($categories as $category)
                            <button wire:click="$set('categoryId', {{ $category->id }})"
                                class="rounded-xs border px-3 py-1.5 font-mono text-2xs uppercase tracking-widest transition-colors duration-200 {{ $categoryId === $category->id ? 'border-primary bg-primary/10 text-primary' : 'border-border bg-surface-800 text-surface-400 hover:border-primary/30 hover:text-surface-200' }}">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                @endif

                <div class="flex items-center justify-between gap-4">
                    <x-ui.sort-select :options="$sortOptions" :current-value="$sortBy" wire-property="sortBy" />
                    <x-ui.paginator :paginator="$stories" />
                </div>
            </div>

            {{-- Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 transition-opacity duration-300"
                x-init="autoAnimate($el, { duration: 250 })" aria-label="Lista storie">
                @forelse ($stories as $story)
                    <x-stories.story :story="$story" />
                @empty
                    <x-ui.empty-state class="col-span-full"
                        message="Nessuna storia trovata con i filtri selezionati." />
                @endforelse
            </div>

            @if ($stories->hasPages())
                <div class="flex justify-end">
                    <x-ui.paginator :paginator="$stories" />
                </div>
            @endif

        </div>
    </x-ui.section-header>

</div>
