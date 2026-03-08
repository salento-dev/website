@props([
    'title',
    'description',
    'icon',
    'variant' => 'default', // 'default' | 'danger'
])

@php
    $variants = [
        'default' => [
            'corner' => 'text-mist-400',
            'icon' => 'text-primary',
        ],
        'danger' => [
            'corner' => 'text-red-900',
            'icon' => 'text-red-500',
        ],
    ];

    $v = $variants[$variant];
@endphp

<div class="flex flex-col items-center gap-5 sm:flex-row">

    <div class="relative p-0 grid size-16 shrink-0 place-items-center bg-surface-800 {{ $v['icon'] }}">
        @if ($variant === 'danger')
            <x-ui.corners :color="$v['corner']" :hover="false" size="h-2.5" offset="4" />
        @endif

        @svg('heroicon-' . $icon, 'size-7')
    </div>

    <div class="space-y-1 text-center sm:space-y-0 sm:text-left">
        <p class="text-lg text-mist-100">{{ $title }}</p>
        <p class="text-mist-400">
            {{ $description }}
        </p>
    </div>
</div>
