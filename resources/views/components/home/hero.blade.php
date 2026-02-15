<section class="relative flex min-h-[400px] flex-col items-center justify-center overflow-hidden border border-primary/20 bg-cyber-card/50 p-8">
    <div class="absolute top-4 left-4 size-8 border-t-2 border-l-2 border-primary"></div>
    <div class="absolute top-4 right-4 size-8 border-t-2 border-r-2 border-primary"></div>
    <div class="absolute bottom-4 left-4 size-8 border-b-2 border-l-2 border-primary"></div>
    <div class="absolute right-4 bottom-4 size-8 border-r-2 border-b-2 border-primary"></div>

    <div class="relative z-10 flex flex-col items-center gap-6 text-center">
        <div>
            <p class="mb-2 text-xs font-bold tracking-[0.5em] text-primary/70 uppercase">Initializing Connection...</p>
            <h1 class="glitch-text text-6xl leading-none font-black tracking-tighter italic uppercase md:text-8xl">
                SALENTO<br><span class="text-primary">DEV</span>
            </h1>
        </div>

        <div class="max-w-xl border-l-4 border-primary py-2 pl-4">
            <h2 class="text-lg font-medium tracking-tight text-white/90 md:text-xl">
                COMMUNITY INDIPENDENTE DI SVILUPPATORI NEL SALENTO.
                <span class="font-mono text-primary">[OPEN_SOURCE_PROTOCOL]</span>
            </h2>
        </div>

        <div class="mt-4 flex flex-wrap justify-center gap-4">
            <a
                href="{{ route('events') }}"
                class="group relative overflow-hidden bg-primary px-8 py-3 pr-8 text-sm font-bold tracking-widest text-background-dark uppercase transition-all hover:pr-10"
            >
                <span class="relative z-10">SCOPRI_EVENTI</span>
                <span class="material-symbols-outlined absolute right-2 opacity-0 transition-opacity group-hover:opacity-100">chevron_right</span>
            </a>
            <a
                href="{{ route('documents-slides') }}"
                class="border border-primary px-8 py-3 text-sm font-bold tracking-widest text-primary uppercase transition-all hover:bg-primary/10"
            >
                VEDI_DOCUMENTI
            </a>
        </div>
    </div>
</section>