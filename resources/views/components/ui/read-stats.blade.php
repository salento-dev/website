@props(['readTime' => null, 'pageViews' => null])

@if ($readTime || $pageViews)
    <div class="mb-6 flex items-center gap-4 font-mono text-xs text-surface-400">
        @if ($readTime)
            <span class="flex items-center gap-1.5">
                <x-heroicon-o-clock class="size-3.5 text-primary/60" />
                {{ $readTime }} min lettura
            </span>
        @endif
        @if ($pageViews)
            <span class="flex items-center gap-1.5">
                <x-heroicon-o-eye class="size-3.5 text-primary/60" />
                {{ number_format($pageViews) }} {{ $pageViews === 1 ? 'visita' : 'visite' }}
            </span>
        @endif
    </div>
@endif
