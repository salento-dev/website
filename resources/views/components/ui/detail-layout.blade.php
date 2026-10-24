@props([
    'stickyTop' => 'lg:top-22',
    'reflowSidebar' => false,
])

<div class="flex flex-col gap-8 md:gap-12 lg:gap-16">

    <section>
        <div class="flex flex-wrap flex-col lg:flex-row gap-8 lg:gap-12">

            {{-- Articolo: colonne 1-2 su desktop --}}
            <article class="flex-1">
                {{ $article }}
            </article>

            {{-- Sidebar: colonna 3 su desktop, sticky. Con reflowSidebar, sotto lg i figli diventano elementi del flusso e possono usare order-* --}}
            <aside class="{{ $reflowSidebar ? 'contents lg:flex' : 'flex' }} lg:w-92 lg:min-w-92 flex-col gap-6 lg:sticky {{ $stickyTop }} lg:self-start">
                {{ $sidebar }}
            </aside>

        </div>
    </section>

    {{-- Sezioni aggiuntive sotto la griglia --}}
    {{ $slot }}

</div>
