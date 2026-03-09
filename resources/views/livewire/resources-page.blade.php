@section('hero')
    <div
        class="inline-block rounded border border-primary/20 bg-primary/5 px-4 py-1 text-[10px] font-bold uppercase tracking-[0.3em] text-primary mb-6">
        Resource Library // Documentation Access
    </div>
    <h1 class="mb-6 text-5xl font-black uppercase leading-tight tracking-tighter sm:text-6xl lg:text-7xl text-white">
        Documenti & <span class="text-primary text-glow">Slide</span>
    </h1>
    <p class="mx-auto max-w-2xl text-lg font-light leading-relaxed text-white/60">
        Materiali condivisi dalla community: guide, slide e risorse utili per approfondire temi tecnici.
    </p>
@endsection

<div class="flex flex-col gap-5">
    <x-ui.input-search wire-model="search" placeholder="Cerca risorse..." sr-label="Cerca risorse per titolo" />

    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-4">
            <x-ui.sort-select :options="$sortOptions" :current-value="$sortBy" wire-property="sortBy" />
            <x-ui.item-count :collection="$resources" label="resources found" />
        </div>

        <x-ui.paginator :paginator="$resources" />
    </div>

    <x-ui.terminal :path="'~/resources'">
        <div class="divide-y divide-border-cyan/50 font-mono text-xs">
            @forelse ($resources as $resource)
                <div class="group flex items-center justify-between px-6 py-4 hover:bg-primary/5 transition-colors">
                    <div class="flex items-center gap-4">
                        @if (str_ends_with($resource->file_path ?? '', '.zip') || str_ends_with($resource->file_path ?? '', '.rar'))
                            <x-heroicon-o-archive-box class="size-4 text-primary" />
                        @else
                            <x-heroicon-o-document-text class="size-4 text-primary" />
                        @endif
                        <span class="text-white">{{ $resource->title }}</span>
                        <span class="hidden sm:inline text-white/20">|</span>
                        <span class="hidden md:inline text-white/20">Uploaded:
                            {{ $resource->created_at->diffForHumans() }}</span>
                    </div>
                    @if ($resource->file_path)
                        <a class="text-primary hover:underline uppercase tracking-widest text-[10px] font-bold"
                            href="{{ asset('storage/' . ltrim($resource->file_path, '/')) }}" download>Download</a>
                    @endif
                </div>
            @empty
                <p class="p-4 text-sm text-primary/60">Nessun documento pubblicato al momento.</p>
            @endforelse
        </div>
    </x-ui.terminal>

</div>
