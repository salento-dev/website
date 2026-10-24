<div class="border border-border bg-surface-900 p-5 flex flex-col gap-3 {{ $classes ?? '' }}">
    <h2 class="font-mono text-2xs uppercase tracking-widest text-primary">Dettagli</h2>
    @if ($story->published_at)
        <div class="flex items-center gap-3 text-sm text-surface-400">
            <x-heroicon-o-calendar-days class="size-4 text-primary/50 shrink-0" />
            {{ $story->published_at->translatedFormat('j F Y') }}
        </div>
    @endif
    @if ($story->read_time_minutes)
        <div class="flex items-center gap-3 text-sm text-surface-400">
            <x-heroicon-o-clock class="size-4 text-primary/50 shrink-0" />
            {{ $story->read_time_minutes }} minuti di lettura
        </div>
    @endif
    @if ($story->category)
        <div class="flex items-center gap-3 text-sm text-surface-400">
            <x-heroicon-o-tag class="size-4 text-primary/50 shrink-0" />
            {{ $story->category->name }}
        </div>
    @endif
</div>
