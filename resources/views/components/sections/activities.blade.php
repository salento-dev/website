<x-ui.section-header
    eyebrow="Le Nostre Attività"
    title='Cosa Facciamo'
    description="Alterniamo momenti strutturati di formazione, spazi aperti per confrontarsi e occasioni informali per fare rete."
>
    <div class="grid grid-cols-1 gap-x-6 gap-y-10 lg:grid-cols-2">
        <x-cards.small icon="o-calendar-days" title="Meetup e Workshop" description="Incontri tecnici periodici, live coding, talk, panel e Q&A." />
        <x-cards.small icon="o-beaker" title="AperiDev" description="Appuntamenti leggeri per momenti informali di confronto." />
        <x-cards.small icon="o-academic-cap" title="Mentorship Leggera" description="Orientiamo nuovi e nuove dev nel mondo dello sviluppo software." />
        <x-cards.small icon="o-map" title="Rotazione Territoriale" description="Teniamo gli incontri a rotazione nel Salento." />

        <x-cards.small variant="danger" icon="o-no-symbol" title="Niente Vendite" description="Non vendiamo prodotti o servizi, non accettiamo membri interessati a vendere." />
        <x-cards.small variant="danger" icon="o-banknotes" title="Niente Lucro" description="Non si fa lucro sulla community, si condivide in modo disinteressato." />
        {{-- <x-cards.small variant="danger" icon="o-shield-exclamation" title="No Pronto Soccorso" description='Non siamo un "pronto soccorso" ma uno spazio di confronto.' /> --}}
    </div>
</x-ui.section-header>
