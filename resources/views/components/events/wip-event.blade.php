@props(['event'])

<div
    class="group relative flex flex-col justify-between border-2 border-dashed border-surface-700 bg-surface-900 p-8 transition-colors hover:border-primary/30">
    <div class="absolute right-6 top-6">
        <x-ui.status-badge status="wip" label="WIP" />
    </div>

    <div>
        <x-heroicon-o-clock class="mb-5 h-8 text-surface-600" />
        <h3 class="mb-2 font-outfit text-xl font-bold text-surface-200">{{ $event->title }}</h3>
        @if ($event->body)
            <p class="line-clamp-3 text-sm leading-relaxed text-surface-500">
                {{ strip_tags($event->body) }}
            </p>
        @endif
    </div>

    <div class="mt-8 flex items-center justify-between">
        @if ($event->local_starts_at)
            <span class="font-mono text-xs italic text-primary/70">
                Target: {{ $event->local_starts_at->translatedFormat('M Y') }}
            </span>
        @else
            <span class="font-mono text-xs italic text-surface-600">Data TBD</span>
        @endif

        @if ($event->location)
            <span class="flex items-center gap-1 font-mono text-2xs uppercase tracking-widest text-surface-500">
                <x-heroicon-o-map-pin class="h-3" />
                {{ $event->location }}
            </span>
        @endif
    </div>
</div>
