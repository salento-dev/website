<div class="border border-border bg-surface-900 p-5 flex flex-col gap-3 {{ $classes ?? '' }}">
    <h3 class="font-mono text-2xs uppercase tracking-widest text-primary">Dettagli</h3>

    <div class="flex items-start gap-3">
        <x-heroicon-o-calendar-days class="size-4 text-primary/50 shrink-0 mt-0.5" />
        <div class="flex flex-col gap-1.5 text-sm">
            <div class="flex flex-wrap items-baseline gap-x-2 gap-y-0.5">
                <span class="font-mono text-2xs uppercase tracking-widest text-surface-600">Inizio</span>
                <span class="text-surface-300">{{ $event->starts_at->translatedFormat('j F Y') }}</span>
                @if ($event->starts_at->format('H:i') !== '00:00')
                    <span class="font-mono text-xs text-primary/70">{{ $event->starts_at->format('H:i') }}</span>
                @endif
            </div>
            @if ($event->ends_at)
                <div class="flex flex-wrap items-baseline gap-x-2 gap-y-0.5">
                    <span class="font-mono text-2xs uppercase tracking-widest text-surface-600">Fine&nbsp;&nbsp;</span>
                    <span class="text-surface-400">{{ $event->ends_at->translatedFormat('j F Y') }}</span>
                    @if ($event->ends_at->format('H:i') !== '00:00')
                        <span class="font-mono text-xs text-surface-500">{{ $event->ends_at->format('H:i') }}</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @if ($event->location)
        <div class="flex items-center gap-3 text-sm text-surface-400">
            <x-heroicon-o-map-pin class="size-4 text-primary/50 shrink-0" />
            {{ $event->location }}
        </div>
    @endif

    @if ($event->address)
        <div class="flex items-center gap-3 text-sm text-surface-400">
            <x-heroicon-o-building-office class="size-4 text-primary/50 shrink-0" />
            {{ $event->address }}
        </div>
    @endif

    @if ($event->read_time_minutes)
        <div class="flex items-center gap-3 text-sm text-surface-400">
            <x-heroicon-o-book-open class="size-4 text-primary/50 shrink-0" />
            {{ $event->read_time_minutes }} minuti di lettura
        </div>
    @endif

    @if ($event->categories->isNotEmpty())
        <div class="flex items-start gap-3 text-sm text-surface-400">
            <x-heroicon-o-tag class="size-4 text-primary/50 shrink-0 mt-0.5" />
            <div class="flex flex-wrap gap-1.5">
                @foreach ($event->categories as $category)
                    <x-ui.category-tag :name="$category->name" />
                @endforeach
            </div>
        </div>
    @endif
</div>
