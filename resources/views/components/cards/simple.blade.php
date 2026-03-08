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
            'corner'    => 'text-mist-400',
            'iconText'  => 'text-primary',
            'iconHover' => 'group-hover:bg-primary-900',
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
        <div class="absolute top-0 left-0" aria-hidden="true">
            <svg class="h-2.5 {{ $v['corner'] }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 11 11" fill="none"><path d="M9.5 0.5H0.5V9.5" class="stroke-current" stroke-linecap="round"></path></svg>
        </div>
        <div class="absolute top-0 right-0" aria-hidden="true">
            <svg class="h-2.5 -scale-x-100 {{ $v['corner'] }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 11 11" fill="none"><path d="M9.5 0.5H0.5V9.5" class="stroke-current" stroke-linecap="round"></path></svg>
        </div>
        <div class="absolute bottom-0 left-0" aria-hidden="true">
            <svg class="h-2.5 -scale-y-100 {{ $v['corner'] }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 11 11" fill="none"><path d="M9.5 0.5H0.5V9.5" class="stroke-current" stroke-linecap="round"></path></svg>
        </div>
        <div class="absolute right-0 bottom-0" aria-hidden="true">
            <svg class="h-2.5 -scale-x-100 -scale-y-100 {{ $v['corner'] }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 11 11" fill="none"><path d="M9.5 0.5H0.5V9.5" class="stroke-current" stroke-linecap="round"></path></svg>
        </div>
    @endif

    <div class="inline-flex size-14 items-center justify-center rounded-full bg-surface-800 {{ $v['iconText'] }} mb-5 {{ $v['iconHover'] }} transition-colors duration-300 ease-in-out">
        @svg('heroicon-' . $icon, 'size-6')
    </div>

    <p class="font-outfit text-xl font-medium text-mist-200 mb-2">{{ $title }}</p>
    <p class="text-sm text-mist-400 leading-relaxed">{{ $description }}</p>
</div>
