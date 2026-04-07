@props([
    'status' => 'upcoming',
    'label' => null,
])

@php
    $defaultLabels = [
        'upcoming' => 'Prossimo evento',
        'ongoing'  => 'In corso',
        'past'     => 'Passato',
        'wip'      => 'In pianificazione',
    ];

    $displayLabel = $label ?? $defaultLabels[$status] ?? $status;

    $classes = match ($status) {
        'ongoing'  => 'border border-green-500/40 bg-green-500/10 text-green-400',
        'past'     => 'bg-surface-700 text-surface-400',
        'wip'      => 'border border-amber-500/30 bg-amber-500/20 text-amber-200',
        default    => 'border border-primary/30 bg-primary/10 text-primary', // upcoming
    };
@endphp

<span {{ $attributes->class(['inline-flex items-center gap-2 rounded-full px-3 py-1 font-mono text-[10px] uppercase tracking-widest', $classes]) }}>
    @if ($status === 'upcoming' || $status === 'ongoing')
        <span class="relative flex h-1.5 w-1.5 shrink-0">
            <span class="absolute inline-flex h-full w-full animate-ping rounded-full opacity-75
                {{ $status === 'ongoing' ? 'bg-green-400' : 'bg-primary' }}"></span>
            <span class="relative inline-flex h-1.5 w-1.5 rounded-full
                {{ $status === 'ongoing' ? 'bg-green-400' : 'bg-primary' }}"></span>
        </span>
    @endif
    {{ $displayLabel }}
</span>
