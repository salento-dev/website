@props([
    'title',
    'icon',
    'actionLabel' => null,
    'actionHref' => '#',
])

<div class="mb-8 flex items-center justify-between" id="eventi">
    <h2 class="flex items-center gap-2 text-2xl font-black tracking-tighter text-primary uppercase">
        @svg('heroicon-o-' . $icon, 'size-6')
        {{ $title }}
    </h2>
    <div class="mx-6 hidden h-px flex-1 bg-cyber-border md:block"></div>
    @if ($actionLabel)
        <a class="text-xs font-bold tracking-widest text-primary/60 transition-colors hover:text-primary" href="{{ $actionHref }}">
            {{ $actionLabel }}
        </a>
    @endif
</div>