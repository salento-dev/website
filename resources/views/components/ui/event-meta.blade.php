@props([
    'icon',
    'text',
    'href' => null,
])

<div {{ $attributes->class(['flex items-center gap-2']) }}>
    @svg('heroicon-o-' . $icon, 'h-3.5 w-3.5 shrink-0 text-primary/70')
    @if ($href)
        <a href="{{ $href }}" class="text-xs text-surface-400 transition-colors hover:text-primary">{{ $text }}</a>
    @else
        <span class="text-xs text-surface-400">{{ $text }}</span>
    @endif
</div>
