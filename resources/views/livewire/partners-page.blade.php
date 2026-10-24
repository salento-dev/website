@section('hero')
    <x-sections.page-hero :badge="'Collaborations // Network protocol'" :description="'Scopri le aziende e le organizzazioni che supportano la nostra comunità.'">
        I nostri <span class="text-primary">Partner</span>
    </x-sections.page-hero>
@endsection

<div class="flex flex-col gap-20">

    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($partners as $partner)
            <x-cards.partner :partner="$partner" />
        @endforeach
    </div>

    @if ($partners->isEmpty())
        <div class="text-center py-12">
            <p class="text-surface-300">Nessun partner disponibile al momento.</p>
        </div>
    @endif


    <!-- CTA Section -->
    <div
        class="bg-surface-800 border border-border/10 p-12 relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-8">
        <div class="relative z-10 text-center md:text-left">
            <h2 class="font-headline text-3xl font-bold text-surface-50 mb-3">Vuoi diventare partner?</h2>
            <p class="text-surface-300 max-w-md">
                Unisciti all'ecosistema di {{ config('community.name') }} e contribuisci a far crescere la scena tech salentina.
            </p>
        </div>
        <div class="relative z-10 shrink-0">
            <x-ui.button href="mailto:{{ config('community.email') }}" text="Contattaci" width="w-44" />
        </div>
        <!-- Technical decoration for CTA background -->
        <div class="absolute right-0 top-0 opacity-5 pointer-events-none">
            <x-heroicon-c-at-symbol class="size-64" />
        </div>
    </div>

</div>
