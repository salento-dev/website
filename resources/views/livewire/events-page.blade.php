<div class="relative flex min-h-screen flex-col overflow-x-hidden bg-grid">
    <x-home.header />

    <main class="mx-auto flex w-full max-w-7xl flex-1 flex-col gap-10 px-6 py-10 lg:px-20">
        <section class="relative overflow-hidden border border-primary/20 bg-cyber-card/50 p-8">
            <h1 class="mb-3 text-4xl font-black tracking-tighter text-primary uppercase md:text-5xl">Eventi</h1>
            <p class="max-w-3xl text-sm leading-relaxed text-primary/70 md:text-base">
                Meetup, workshop e momenti di confronto della community. Qui trovi tutti gli eventi pubblicati, ordinati per data.
            </p>
        </section>

        <section>
            <div class="mb-8 flex items-center justify-between">
                <h2 class="flex items-center gap-2 text-2xl font-black tracking-tighter text-primary uppercase">
                    <span class="material-symbols-outlined">event_note</span>
                    Lista Eventi
                </h2>
                <a class="text-xs font-bold tracking-widest text-primary/60 transition-colors hover:text-primary" href="{{ route('home') }}">
                    TORNA_HOME
                </a>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($events as $event)
                    <x-home.event-card :event="$event" />
                @empty
                    <p class="text-sm text-primary/60">Nessun evento pubblicato al momento.</p>
                @endforelse
            </div>
        </section>
    </main>

    <x-home.footer />
</div>