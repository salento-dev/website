@props([
    'src',
    'alt' => '',
])

<div x-data="{ open: false }">

    {{-- Thumbnail --}}
    <button type="button" @click="open = true"
        class="group relative block w-full overflow-hidden border border-border bg-surface-900 cursor-zoom-in">
        <img src="{{ $src }}" alt="{{ $alt }}"
            class="w-full transition duration-300 ease-out group-hover:scale-105 group-hover:opacity-70">
        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
            <div class="flex items-center gap-2 rounded-xs border border-primary/40 bg-surface-900/80 px-3 py-1.5 font-mono text-2xs uppercase tracking-widest text-primary backdrop-blur-sm">
                <x-heroicon-o-magnifying-glass-plus class="size-3.5" />
                Apri
            </div>
        </div>
    </button>

    {{-- Lightbox overlay --}}
    <template x-teleport="body">
        <div x-show="open"
            x-transition:enter="transition duration-200 ease-out"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition duration-150 ease-in"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click.self="open = false"
            @keydown.escape.window="open = false"
            class="fixed inset-0 z-100 flex items-center justify-center bg-black/90 p-4 md:p-10"
            style="display: none;">

            <button type="button" @click="open = false"
                class="absolute top-4 right-4 flex items-center gap-2 font-mono text-2xs uppercase tracking-widest text-surface-400 transition-colors hover:text-primary">
                <x-heroicon-o-x-mark class="size-5" />
                Chiudi
            </button>

            <img src="{{ $src }}" alt="{{ $alt }}"
                class="max-h-full max-w-full object-contain shadow-2xl">
        </div>
    </template>
</div>
