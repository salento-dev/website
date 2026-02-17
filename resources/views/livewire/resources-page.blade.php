<div class="relative flex min-h-screen flex-col overflow-x-hidden">
    <x-home.header />

    <main class="flex-1">
        <!-- Hero Section -->
        <section class="relative flex min-h-[40vh] items-center justify-center px-6 py-20 lg:px-20 bg-primary/5 border-b border-border-cyan">
            <div class="relative z-10 mx-auto max-w-[1000px] text-center">
                <div class="inline-block rounded border border-primary/20 bg-primary/5 px-4 py-1 text-[10px] font-bold uppercase tracking-[0.3em] text-primary mb-6">
                    Resource Library // Documentation Access
                </div>
                <h1 class="mb-6 text-5xl font-black uppercase leading-tight tracking-tighter sm:text-6xl lg:text-7xl text-white">
                    Documenti & <span class="text-primary text-glow">Slide</span>
                </h1>
                <p class="mx-auto max-w-2xl text-lg font-light leading-relaxed text-white/60">
                    Materiali condivisi dalla community: guide, slide e risorse utili per approfondire temi tecnici.
                </p>
            </div>
        </section>

        <!-- Documents Section -->
        <x-home.section-header 
            eyebrow="FETCHING_RESOURCES" 
            title='Resource Archive'
        >
            <div class="overflow-hidden border border-border-cyan bg-background-dark">
                <!-- Header -->
                <div class="flex items-center justify-between border-b border-border-cyan bg-card-dark px-6 py-3">
                    <div class="flex gap-2">
                        <div class="size-2 rounded-full bg-red-500/50"></div>
                        <div class="size-2 rounded-full bg-yellow-500/50"></div>
                        <div class="size-2 rounded-full bg-green-500/50"></div>
                    </div>
                    <span class="text-[10px] font-mono text-primary/60 tracking-widest uppercase">root@salentodev:~/resources</span>
                </div>
                <!-- Terminal Body -->
                <div class="divide-y divide-border-cyan/50 font-mono text-xs">
                    @forelse ($resources as $resource)
                        <div class="group flex items-center justify-between px-6 py-4 hover:bg-primary/5 transition-colors">
                            <div class="flex items-center gap-4">
                                <span class="material-symbols-outlined text-primary">
                                    @if(str_ends_with($resource->file_path ?? '', '.pdf'))
                                        description
                                    @elseif(str_ends_with($resource->file_path ?? '', '.zip') || str_ends_with($resource->file_path ?? '', '.rar'))
                                        folder_zip
                                    @else
                                        description
                                    @endif
                                </span>
                                <span class="text-white">{{ $resource->title }}</span>
                                <span class="hidden sm:inline text-white/20">|</span>
                                <span class="hidden md:inline text-white/20">Uploaded: {{ $resource->created_at->diffForHumans() }}</span>
                            </div>
                            @if ($resource->file_path)
                                <a class="text-primary hover:underline uppercase tracking-widest text-[10px] font-bold" href="{{ asset('storage/' . ltrim($resource->file_path, '/')) }}" download>Download</a>
                            @endif
                        </div>
                    @empty
                        <p class="p-4 text-sm text-primary/60">Nessun documento pubblicato al momento.</p>
                    @endforelse
                </div>
            </div>
        </x-home.section-header>
    </main>

    <x-home.footer />
</div>