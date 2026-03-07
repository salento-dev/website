<x-ui.section-header 
    eyebrow="Le Nostre Attività" 
    title='Cosa Facciamo'
    description="Alterniamo momenti strutturati di formazione, spazi aperti per confrontarsi e occasioni informali per fare rete."
>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Cosa facciamo -->
        <div class="rounded-lg border border-border bg-surface-900 p-10 hover:border-primary/50 transition-all">
            <div class="flex items-center gap-4 mb-8">
                <div class="inline-flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10 text-primary">
                    <span class="material-symbols-outlined text-2xl">check_circle</span>
                </div>
                <h4 class="text-2xl font-bold text-stone-50">Cosa Facciamo</h4>
            </div>
            <div class="space-y-8">
                <div class="flex gap-5">
                    <div class="shrink-0">
                        <div class="w-12 h-12 rounded-lg bg-primary/10 border border-primary/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-lg text-primary">event</span>
                        </div>
                    </div>
                    <div>
                        <h5 class="text-base font-bold text-stone-50 mb-2">Meetup e Workshop</h5>
                        <p class="text-sm text-stone-400 leading-relaxed">
                            Incontri tecnici periodici, live coding, talk, panel e Q&A.
                        </p>
                    </div>
                </div>
                <div class="flex gap-5">
                    <div class="shrink-0">
                        <div class="w-12 h-12 rounded-lg bg-primary/10 border border-primary/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-lg text-primary">local_cafe</span>
                        </div>
                    </div>
                    <div>
                        <h5 class="text-base font-bold text-stone-50 mb-2">AperiDev</h5>
                        <p class="text-sm text-stone-400 leading-relaxed">
                            Appuntamenti leggeri per momenti informali di confronto.
                        </p>
                    </div>
                </div>
                <div class="flex gap-5">
                    <div class="shrink-0">
                        <div class="w-12 h-12 rounded-lg bg-primary/10 border border-primary/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-lg text-primary">school</span>
                        </div>
                    </div>
                    <div>
                        <h5 class="text-base font-bold text-stone-50 mb-2">Mentorship Leggera</h5>
                        <p class="text-sm text-stone-400 leading-relaxed">
                            Orientiamo nuovi e nuove dev nel mondo dello sviluppo software.
                        </p>
                    </div>
                </div>
                <div class="flex gap-5">
                    <div class="shrink-0">
                        <div class="w-12 h-12 rounded-lg bg-primary/10 border border-primary/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-lg text-primary">map</span>
                        </div>
                    </div>
                    <div>
                        <h5 class="text-sm font-semibold text-text-primary mb-1">Rotazione Territoriale</h5>
                        <p class="text-sm text-text-secondary leading-relaxed">
                            Teniamo gli incontri a rotazione nel Salento.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cosa non facciamo -->
        <div class="rounded-xl border border-red-500/30 bg-surface-dark p-8 hover:border-red-500/50 transition-all">
            <div class="flex items-center gap-3 mb-6">
                <div class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-red-500/10 text-red-500">
                    <span class="material-symbols-outlined text-xl">cancel</span>
                </div>
                <h4 class="text-xl font-semibold text-text-primary">Cosa Non Facciamo</h4>
            </div>
            <div class="space-y-6">
                <div class="flex gap-4">
                    <div class="shrink-0">
                        <div class="w-10 h-10 rounded-lg bg-red-500/10 border border-red-500/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-base text-red-500">block</span>
                        </div>
                    </div>
                    <div>
                        <h5 class="text-sm font-semibold text-text-primary mb-1">Niente Vendite</h5>
                        <p class="text-sm text-text-secondary leading-relaxed">
                            Non vendiamo prodotti o servizi, non accettiamo membri interessati a vendere.
                        </p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="shrink-0">
                        <div class="w-10 h-10 rounded-lg bg-red-500/10 border border-red-500/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-base text-red-500">money_off</span>
                        </div>
                    </div>
                    <div>
                        <h5 class="text-sm font-semibold text-text-primary mb-1">Niente Lucro</h5>
                        <p class="text-sm text-text-secondary leading-relaxed">
                            Niente lucro sulla community, si condivide in modo disinteressato.
                        </p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="shrink-0">
                        <div class="w-10 h-10 rounded-lg bg-red-500/10 border border-red-500/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-base text-red-500">health_and_safety</span>
                        </div>
                    </div>
                    <div>
                        <h5 class="text-sm font-semibold text-text-primary mb-1">No Pronto Soccorso</h5>
                        <p class="text-sm text-text-secondary leading-relaxed">
                            Non siamo un "pronto soccorso" ma uno spazio di confronto.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-ui.section-header>
