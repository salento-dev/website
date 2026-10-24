@props([
    'message' => 'Nessun risultato',
    'icon'    => 'o-magnifying-glass',
])

<div {{ $attributes->class(['flex flex-col items-center justify-center gap-4 border border-dashed border-surface-700 py-20 text-center']) }}>
    @svg('heroicon-' . $icon, 'h-10 text-surface-600')
    <p class="font-mono text-sm text-surface-400">{{ $message }}</p>
</div>
