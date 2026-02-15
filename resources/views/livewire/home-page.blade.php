<div class="relative flex min-h-screen flex-col overflow-x-hidden bg-grid">
    <x-home.header />

    <main class="mx-auto flex w-full max-w-7xl flex-1 flex-col gap-12 px-6 py-10 lg:px-20">
        <x-home.hero />

        <x-home.mission />

        <section>
            <x-home.section-title
                title="Prossimi Eventi"
                icon="event_note"
                action-label="Vedi tutti"
                :action-href="route('events')"
            />

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($events as $event)
                    <x-home.event-card :event="$event" :href="route('events.show', $event)" />
                @empty
                    <p class="text-sm text-primary/60">Nessun evento pubblicato al momento.</p>
                @endforelse
            </div>
        </section>

        <section>
            <div class="mb-8 flex items-center gap-4">
                <h2 class="flex items-center gap-2 text-2xl font-black uppercase tracking-tighter text-primary">
                    <span class="material-symbols-outlined">source</span>
                    Ultimi Documenti
                </h2>
                <div class="flex-1 border-t border-dashed border-primary/20"></div>
            </div>

            <div class="overflow-hidden border border-cyber-border bg-cyber-card/30 font-mono rounded-sm">
                <div class="flex items-center justify-between border-b border-cyber-border bg-cyber-card px-4 py-2 text-[10px] uppercase tracking-widest text-primary/40">
                    <span>Directory: /salento/docs/</span>
                    <span>Ultimi {{ $documents->count() }} file</span>
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

        <section class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <x-home.principle-card
                title="Qualità (e rigore)"
                description="Condividiamo best practice, test e documentazione essenziale per aiutare ogni dev a scrivere codice migliore, in modo riproducibile."
            />
            <x-home.principle-card
                title="Networking (quello vero)"
                description="Meetup, workshop e momenti informali per creare relazioni concrete, confrontarsi e far nascere nuove collaborazioni."
            />
            <x-home.principle-card
                title="Impatto territoriale"
                description="Facciamo crescere il Salento con conoscenza condivisa, opportunità locali e una community tecnica indipendente."
            />
        </section>
    </main>

    <x-home.footer />
</div>