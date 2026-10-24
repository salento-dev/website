@props([
    'telemetryLabel' => 'Next_Event',
    'telemetryValue' => 'TBD',
    'title' => 'Nessun evento in programma, per ora',
    'description' => 'Il prossimo incontro viene annunciato prima di tutto nel gruppo WhatsApp della community.',
])

@php
    $whatsapp = collect(config('community.social'))->firstWhere('key', 'whatsapp');
@endphp

<div {{ $attributes->class(['group relative bg-surface-900 p-8 md:p-10 lg:p-12']) }}>
    <x-ui.corners color="text-surface-600" size="h-3" />

    <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
        <div class="flex max-w-2xl flex-col gap-3">
            <span class="font-mono text-2xs uppercase tracking-widest text-surface-400">
                {{ $telemetryLabel }}: <span class="text-primary">{{ $telemetryValue }}</span>
            </span>
            <h2 class="font-outfit text-2xl font-bold text-surface-50 md:text-3xl">
                {{ $title }}
            </h2>
            <p class="leading-relaxed text-surface-400">
                {{ $description }}
            </p>
        </div>

        @if ($whatsapp)
            <div class="flex h-12 shrink-0 self-start border border-green-500/30 bg-green-500/5 md:self-auto">
                <x-ui.spark-link
                    :href="$whatsapp['url']"
                    text="Join WhatsApp"
                    aria-label="Entra nel gruppo WhatsApp della community"
                    class="h-full"
                >
                    <x-slot:icon><x-simpleicon-whatsapp class="size-full" /></x-slot:icon>
                </x-ui.spark-link>
            </div>
        @endif
    </div>
</div>
