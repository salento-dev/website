@props([
    'event',
])

@php
    $startsAt = $event->local_starts_at;
    $town = $event->location_town;
    $dateLabel = $startsAt?->translatedFormat($event->work_in_progress ? 'M Y' : 'j M Y');
@endphp

@if ($dateLabel || $town)
    <p {{ $attributes->class(['flex flex-wrap items-center gap-x-4 gap-y-1 font-mono text-xs text-surface-400']) }}>
        @if ($dateLabel)
            <span class="inline-flex items-center gap-1.5">
                <x-heroicon-o-calendar-days class="h-3.5 shrink-0 text-surface-500" aria-hidden="true" />
                <time datetime="{{ $startsAt->toIso8601String() }}">{{ $dateLabel }}</time>
            </span>
        @endif
        @if ($town)
            <span class="inline-flex min-w-0 items-center gap-1.5">
                <x-heroicon-o-map-pin class="h-3.5 shrink-0 text-surface-500" aria-hidden="true" />
                <span class="truncate">{{ $town }}</span>
            </span>
        @endif
    </p>
@endif
