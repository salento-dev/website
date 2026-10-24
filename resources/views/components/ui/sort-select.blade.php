@props([
    'options',
    'currentValue',
    'wireProperty' => 'sortBy',
    'label' => 'Ordina:',
])

@php
    $selectId = 'sort-select-' . uniqid();
@endphp

<div class="flex items-center gap-1 text-xs">
    <span class="hidden md:inline">{{ $label }}</span>
    <div class="relative" x-data="{ open: false }" @click.outside="open = false"
        @keydown.escape.window="open = false">
        <label for="{{ $selectId }}" class="sr-only">
            Ordina per
        </label>
        <button id="{{ $selectId }}" type="button" @click="open = !open" :aria-expanded="open"
            aria-haspopup="listbox" class="btn btn-sm group" aria-expanded="false">
            <span
                x-text="({ @foreach ($options as $value => $optionLabel) '{{ $value }}': '{{ $optionLabel }}', @endforeach })[$wire.{{ $wireProperty }}]">{{ $options[$currentValue] }}</span>
            <div class="transition-transform duration-300 ease-out" :class="open && 'rotate-180'"
                aria-hidden="true">
                <x-heroicon-o-chevron-down class="size-3.5" />
            </div>
        </button>

        <div x-show="open" x-transition:enter="transition duration-300 ease-out"
            x-transition:enter-start="-translate-y-1 opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transition duration-150 ease-in"
            x-transition:leave-start="translate-y-0 opacity-100"
            x-transition:leave-end="-translate-y-1 opacity-0"
            class="absolute top-full left-0 z-20 mt-1 min-w-35 overflow-hidden rounded-xs border border-border bg-surface-950"
            role="listbox" aria-label="Sort options" style="display: none;">
            @foreach ($options as $value => $optionLabel)
                <button type="button" role="option"
                    :aria-selected="$wire.{{ $wireProperty }} === '{{ $value }}'"
                    @click="$wire.set('{{ $wireProperty }}', '{{ $value }}'); open = false"
                    class="flex w-full items-center gap-2 px-3 py-2 text-left transition-colors duration-150 hover:bg-surface-900/70"
                    :class="$wire.{{ $wireProperty }} === '{{ $value }}' &&
                        'bg-surface-900 font-medium text-surface-200'">
                    <span class="size-3.5"
                        :class="$wire.{{ $wireProperty }} === '{{ $value }}' ? 'opacity-100' : 'opacity-0'">
                        <x-heroicon-o-check class="size-3.5" />
                    </span>
                    {{ $optionLabel }}
                </button>
            @endforeach
        </div>
    </div>
</div>
