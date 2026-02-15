<div class="relative flex min-h-screen flex-col overflow-x-hidden bg-grid">
    <x-home.header />

    <main class="mx-auto flex w-full max-w-7xl flex-1 flex-col gap-10 px-6 py-10 lg:px-20">
        <section class="relative overflow-hidden border border-primary/20 bg-cyber-card/50 p-8">
            <h1 class="mb-3 text-4xl font-black tracking-tighter text-primary uppercase md:text-5xl">Documenti & Slide</h1>
            <p class="max-w-3xl text-sm leading-relaxed text-primary/70 md:text-base">
                Materiali condivisi dalla community: guide, slide e risorse utili per approfondire temi tecnici trattati negli incontri.
            </p>
        </section>

        <section>
            <div class="mb-8 flex items-center justify-between">
                <h2 class="flex items-center gap-2 text-2xl font-black tracking-tighter text-primary uppercase">
                    <span class="material-symbols-outlined">source</span>
                    Archivio Documenti
                </h2>
                <a class="text-xs font-bold tracking-widest text-primary/60 transition-colors hover:text-primary" href="{{ route('home') }}">
                    TORNA_HOME
                </a>
            </div>

            <div class="overflow-hidden border border-cyber-border bg-cyber-card/30 font-mono rounded-sm">
                <div class="flex items-center justify-between border-b border-cyber-border bg-cyber-card px-4 py-2 text-[10px] uppercase tracking-widest text-primary/40">
                    <span>Directory: /salento/docs/</span>
                    <span>Elementi totali: {{ $documents->count() }}</span>
                </div>

                <div class="divide-y divide-cyber-border/50">
                    @forelse ($documents as $document)
                        <x-home.document-row :document="$document" />
                    @empty
                        <p class="p-4 text-sm text-primary/60">Nessun documento pubblicato al momento.</p>
                    @endforelse
                </div>
            </div>
        </section>
    </main>

    <x-home.footer />
</div>