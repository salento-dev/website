<section data-triangle-decoration="bottom" class="relative overflow-hidden border-b border-border">
    <div class="px-6 py-24 sm:px-12 lg:px-20 lg:py-32">
        <div class="mx-auto max-w-4xl text-center">
            <!-- Badge -->
            @component('components.ui.badge')
                Sistema Online • Salento, Italia
            @endcomponent
            
            <!-- Hero Title - Filament style -->
            <h1 class="mb-6 text-5xl font-bold leading-[1.1] tracking-tight text-stone-50 sm:text-6xl lg:text-7xl">
                <span class="block text-primary">Salento.dev</span>
                <span class="block">la comunity di sviluppatori</span>
            </h1>
            
            <!-- Description -->
            <p class="mx-auto max-w-2xl text-lg leading-relaxed text-stone-400">
                Una comunità di sviluppatori, designer e innovatori che trasformano idee in realtà attraverso la collaborazione, il codice open-source e la condivisione delle conoscenze.
            </p>
            
            <!-- CTA Buttons - Filament style -->
            <div class="mt-10 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('events') }}" 
                   class="btn btn-primary">
                    Scopri gli Eventi
                </a>
                <a href="{{ route('resources') }}" 
                   class="btn">
                    Esplora le Risorse
                </a>
            </div>
        </div>
    </div>

    <x-partials.skills-loop />
</section>