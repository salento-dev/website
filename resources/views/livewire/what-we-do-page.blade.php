@section('hero')
    <x-sections.page-hero :badge="'Activities // Community'"
        description="Alterniamo momenti strutturati di formazione, spazi aperti per confrontarsi e occasioni informali per fare rete.">
        Cosa <span class="text-primary">facciamo</span>
    </x-sections.page-hero>
@endsection

<div class="flex flex-col gap-20">
    <x-sections.activities />

    <x-sections.scopes />

    <x-sections.principles />

    <x-sections.code-of-conduct />
</div>
