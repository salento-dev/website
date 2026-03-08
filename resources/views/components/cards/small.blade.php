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
            <div class="absolute top-0 left-0" aria-hidden="true">
                <svg class="h-2.5 {{ $v['corner'] }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 11 11"
                    fill="none">
                    <path d="M9.5 0.5H0.5V9.5" class="stroke-current" stroke-linecap="round"></path>
                </svg>
            </div>
            <div class="absolute top-0 right-0" aria-hidden="true">
                <svg class="h-2.5 -scale-x-100 {{ $v['corner'] }}" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 11 11" fill="none">
                    <path d="M9.5 0.5H0.5V9.5" class="stroke-current" stroke-linecap="round"></path>
                </svg>
            </div>
            <div class="absolute bottom-0 left-0" aria-hidden="true">
                <svg class="h-2.5 -scale-y-100 {{ $v['corner'] }}" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 11 11" fill="none">
                    <path d="M9.5 0.5H0.5V9.5" class="stroke-current" stroke-linecap="round"></path>
                </svg>
            </div>
            <div class="absolute right-0 bottom-0" aria-hidden="true">
                <svg class="h-2.5 -scale-x-100 -scale-y-100 {{ $v['corner'] }}" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 11 11" fill="none">
                    <path d="M9.5 0.5H0.5V9.5" class="stroke-current" stroke-linecap="round"></path>
                </svg>
            </div>
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
