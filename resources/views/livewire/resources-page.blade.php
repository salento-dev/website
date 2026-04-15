@section('hero')
    <x-sections.page-hero :badge="'Resource Library // Documentation Access'" :description="'Materiali condivisi dalla community: guide, slide e risorse utili per approfondire temi tecnici.'">
        Documenti & <span class="text-primary text-glow">Slide</span>
    </x-sections.page-hero>
@endsection

<div class="flex flex-col gap-6">
    <x-ui.input-search wire-model="search" placeholder="Cerca risorse..." sr-label="Cerca risorse per titolo" />

    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-4">
            <x-ui.sort-select :options="$sortOptions" :current-value="$sortBy" wire-property="sortBy" />
            <x-ui.item-count :collection="$resources" label="resources found" />
        </div>

        <x-ui.paginator :paginator="$resources" />
    </div>

    <x-ui.terminal :path="'/resources'">
        <div class="divide-y divide-border/50 font-mono text-xs">
            @forelse ($resources as $resource)
                <x-cards.resource-row :resource="$resource" />
            @empty
                <p class="p-4 text-sm text-primary/60">Nessuna risorsa pubblicata al momento.</p>
            @endforelse
        </div>
    </x-ui.terminal>

</div>
