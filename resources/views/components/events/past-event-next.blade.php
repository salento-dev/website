@props([
    'nextEvent' => null,
])

@php
    $whatsapp = collect(config('community.social'))->firstWhere('key', 'whatsapp');
@endphp

<section aria-labelledby="past-event-next-title" {{ $attributes->class(['relative flex flex-col gap-5 bg-surface-900 p-6']) }}>
    <x-ui.corners color="text-surface-600" size="h-3" />

    <div class="flex flex-col gap-2">
        <span class="font-mono text-2xs uppercase tracking-widest text-surface-400">
            Next_Event:
            <span class="text-primary">
                {{ $nextEvent ? $nextEvent->local_starts_at->translatedFormat('j M Y') : 'TBD' }}
            </span>
        </span>

        @if ($nextEvent)
            <h2 id="past-event-next-title" class="font-outfit text-xl font-bold text-balance text-surface-50">
                <a href="{{ route('events.show', $nextEvent) }}"
                    class="group transition-colors duration-200 hover:text-primary focus:outline-none focus-visible:ring focus-visible:ring-primary/50">
                    <span class="sr-only">Prossimo evento: </span>{{ $nextEvent->title }}
                    <x-heroicon-o-arrow-right class="ml-1 inline size-4 -translate-y-px text-primary transition-transform duration-200 group-hover:translate-x-1" />
                </a>
            </h2>
            @if ($nextEvent->location)
                <p class="flex items-center gap-2 text-sm text-surface-400">
                    <x-heroicon-o-map-pin class="size-4 shrink-0 text-primary/50" />
                    {{ $nextEvent->location }}
                </p>
            @endif
        @else
            <h2 id="past-event-next-title" class="font-outfit text-xl font-bold text-balance text-surface-50">
                Nessun evento in programma, per ora
            </h2>
        @endif
    </div>

    @if ($whatsapp)
        <div class="flex flex-col gap-4 border-t border-border pt-5">
            <p class="text-sm leading-relaxed text-surface-400">
                Il prossimo incontro viene annunciato prima di tutto nel gruppo WhatsApp della community.
            </p>
            <div class="flex h-12 self-start border border-green-500/30 bg-green-500/5">
                <x-ui.spark-link
                    :href="$whatsapp['url']"
                    text="Join WhatsApp"
                    aria-label="Join WhatsApp – gruppo della community"
                    class="h-full"
                >
                    <x-slot:icon><x-simpleicon-whatsapp class="size-full" /></x-slot:icon>
                </x-ui.spark-link>
            </div>
        </div>
    @endif
</section>
