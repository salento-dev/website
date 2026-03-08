<x-ui.section-header
    eyebrow="Le Nostre Attività"
    title='Cosa Facciamo'
    description="Alterniamo momenti strutturati di formazione, spazi aperti per confrontarsi e occasioni informali per fare rete."
>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-cards.simple icon="o-calendar-days" title="Meetup e Workshop" description="Incontri tecnici periodici, live coding, talk, panel e Q&A." />
        <x-cards.simple icon="o-beaker" title="AperiDev" description="Appuntamenti leggeri per momenti informali di confronto." />
        <x-cards.simple icon="o-academic-cap" title="Mentorship Leggera" description="Orientiamo nuovi e nuove dev nel mondo dello sviluppo software." />
        <x-cards.simple icon="o-map" title="Rotazione Territoriale" description="Teniamo gli incontri a rotazione nel Salento." />
    </div>

    <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-6">
        <x-cards.simple variant="danger" icon="o-no-symbol" title="Niente Vendite" description="Non vendiamo prodotti o servizi, non accettiamo membri interessati a vendere." />
        <x-cards.simple variant="danger" icon="o-banknotes" title="Niente Lucro" description="Niente lucro sulla community, si condivide in modo disinteressato." />
        <x-cards.simple variant="danger" icon="o-shield-exclamation" title="No Pronto Soccorso" description='Non siamo un "pronto soccorso" ma uno spazio di confronto.' />
    </div>
</x-ui.section-header>
