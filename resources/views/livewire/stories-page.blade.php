@section('hero')
    <x-sections.page-hero :badge="'Storie dalla community'" :description="'Progetti ed esperimenti dei membri, raccontati da chi li ha fatti: cosa ha funzionato, cosa no, e il codice.'">
        Cose concrete che <span class="text-primary">stanno succedendo</span>
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
                @if ($categories->isNotEmpty() && $categories->count() > 1)
                    @php
                        $chipClasses = 'inline-flex min-h-11 shrink-0 cursor-pointer items-center whitespace-nowrap rounded-xs border px-3 font-mono text-2xs uppercase tracking-widest transition-colors duration-200 focus:outline-none focus-visible:ring focus-visible:ring-primary/50 pointer-fine:min-h-8';
                        $chipActive = 'border-primary bg-primary/10 text-primary';
                        $chipIdle = 'border-border bg-surface-800 text-surface-400 hover:border-primary/30 hover:text-surface-200';
                    @endphp
                    <div role="group" aria-label="Filtra per categoria"
                        class="-mx-6 flex snap-x scroll-px-6 gap-2 overflow-x-auto px-6 [scrollbar-width:none] md:mx-0 md:flex-wrap md:overflow-visible md:px-0 [&::-webkit-scrollbar]:hidden">
                        <button type="button" wire:click="$set('categoryId', null)"
                            aria-pressed="{{ $categoryId === null ? 'true' : 'false' }}"
                            class="{{ $chipClasses }} snap-start {{ $categoryId === null ? $chipActive : $chipIdle }}">
                            Tutte
                        </button>
                        @foreach ($categories as $category)
                            <button type="button" wire:click="$set('categoryId', {{ $category->id }})"
                                aria-pressed="{{ $categoryId === $category->id ? 'true' : 'false' }}"
                                class="{{ $chipClasses }} snap-start {{ $categoryId === $category->id ? $chipActive : $chipIdle }}">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                @endif

                <div class="flex items-center justify-between gap-4">
                    <x-ui.sort-select :options="$sortOptions" :current-value="$sortBy" wire-property="sortBy" />
                    <x-ui.paginator :paginator="$stories" />
                </div>

                <x-ui.item-count :collection="$stories" label="storie trovate" singular-label="storia trovata"
                    :visible="false" />
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
