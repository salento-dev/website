@props([
    'stickyTop' => 'lg:top-22',
])

<div class="flex flex-col gap-8 md:gap-12 lg:gap-16">

    <section>
        <div class="flex flex-wrap flex-col lg:flex-row gap-8 lg:gap-12">

            {{-- Scheda meta mobile: appare sopra l'articolo, nascosta su desktop --}}
            @isset($mobileMeta)
                <div class="lg:hidden">
                    {{ $mobileMeta }}
                </div>
            @endisset

            {{-- Articolo: colonne 1-2 su desktop --}}
            <article class="flex-1">
                {{ $article }}
            </article>

            {{-- Sidebar: colonna 3 su desktop, sticky --}}
            <aside class="lg:w-92 lg:min-w-92 flex flex-col gap-6 lg:sticky {{ $stickyTop }} lg:self-start">
                {{ $sidebar }}
            </aside>

        </div>
    </section>

    {{-- Sezioni aggiuntive sotto la griglia --}}
    {{ $slot }}

</div>
