<!-- Badge -->
@component('components.ui.badge')
    Sistema Online • Salento, Italia
@endcomponent

<!-- Hero Title - Filament style -->
<h1 class="mb-6 text-5xl font-bold leading-[1.1] tracking-tight text-surface-50 sm:text-6xl lg:text-7xl">
    <span class="block text-primary">Salento.dev</span>
    <span class="block">la comunity di sviluppatori</span>
</h1>

<!-- Description -->
<p class="mx-auto max-w-2xl text-lg leading-relaxed text-surface-400">
    Una comunità di sviluppatori, designer e innovatori che trasformano idee in realtà attraverso la collaborazione, il
    codice open-source e la condivisione delle conoscenze.
</p>

<!-- CTA Buttons - Filament style -->
<div class="mt-10 flex flex-wrap items-center justify-center gap-3">
    <a href="{{ route('events') }}" class="btn btn-primary">
        Scopri gli Eventi
    </a>
    <a href="{{ route('resources') }}" class="btn">
        Esplora le Risorse
    </a>
</div>
