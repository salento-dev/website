@props(['index' => 0])

<div
    x-show="current === {{ $index }}"
    x-transition:enter="transition duration-500 ease-out"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition duration-300 ease-in absolute inset-0"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    x-cloak
    @mouseenter="stop()"
    @mouseleave="start()"
>
    {{ $slot }}
</div>
