@props([
    'title',
    'description',
    'icon',
    'variant' => 'default', // 'default' | 'danger'
])

@php
    $variants = [
        'default' => [
            'corner' => 'text-surface-400',
            'icon' => 'text-surface-300',
        ],
        'danger' => [
            'corner' => 'text-red-900',
            'icon' => 'text-red-500',
        ],
    ];

    $v = $variants[$variant];
@endphp

<div class="flex items-start gap-4 sm:items-center sm:gap-5">

    <div class="relative p-0 grid size-12 sm:size-16 shrink-0 place-items-center bg-surface-800 {{ $v['icon'] }}">
        @if ($variant === 'danger')
            <x-ui.corners :color="$v['corner']" :hover="false" size="h-2.5" offset="4" />
        @endif

        @svg('heroicon-' . $icon, 'size-6 sm:size-7')
    </div>

    <div class="space-y-1 sm:space-y-0">
        <p class="text-lg text-surface-100">{{ $title }}</p>
        <p class="text-surface-400">
            {{ $description }}
        </p>
    </div>
</div>
