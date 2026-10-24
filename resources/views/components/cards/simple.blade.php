@props([
    'title',
    'description',
    'icon',
    'variant' => 'default', // 'default' | 'danger'
])

@php
    $variants = [
        'default' => [
            'card'      => 'hover:bg-primary-800/25',
            'corner'    => 'text-surface-400',
            'iconText'  => 'text-surface-300',
            'iconHover' => 'group-hover:bg-primary-900 group-hover:text-primary',
        ],
        'danger' => [
            'card'      => 'hover:bg-red-950/30',
            'corner'    => 'text-red-900',
            'iconText'  => 'text-red-500',
            'iconHover' => 'group-hover:bg-red-950',
        ],
    ];

    $v = $variants[$variant];
@endphp

<div {{ $attributes->class(['group relative px-8 pb-10 pt-8 text-center bg-surface-900 transition-colors duration-300 ease-in-out', $v['card']]) }}>
    @if ($variant === 'danger')
        <x-ui.corners :color="$v['corner']" :hover="false" size="h-2.5" offset="4" />
    @endif

    <div class="inline-flex size-14 items-center justify-center rounded-full bg-surface-800 {{ $v['iconText'] }} mb-5 {{ $v['iconHover'] }} transition-colors duration-300 ease-in-out">
        @svg('heroicon-' . $icon, 'size-6')
    </div>

    <p class="font-outfit text-xl font-medium text-surface-200 mb-2">{{ $title }}</p>
    <p class="text-sm text-surface-400 leading-relaxed">{{ $description }}</p>
</div>
