@props([
    'tag',
    'label',
])

@php
    $safeTag = str_replace("'", "\\'", (string) $tag);
@endphp

<button
    type="button"
    x-on:click="toggleTag('{{ $safeTag }}')"
    :aria-pressed="isTagSelected('{{ $safeTag }}')"
    class="inline-flex h-8 shrink-0 cursor-pointer items-center gap-1.25 rounded-xs pr-1.75 pl-2.5 text-sm transition-all duration-300 ease-out select-none focus:outline-none focus-visible:ring focus-visible:ring-black/50 focus-visible:ring-inset active:scale-95"
    :class="isTagSelected('{{ $safeTag }}') ? 'bg-surface-100 font-medium text-surface-800' : 'bg-surface-100 hover:bg-surface-100/50'"
>
    <span>{{ $label }}</span>

    <span aria-hidden="true" class="inline-grid size-3.5 place-items-center overflow-hidden">
        <span
            class="self-center justify-self-center transition duration-300 ease-out [grid-area:1/-1]"
            :class="isTagSelected('{{ $safeTag }}') ? 'opacity-0 scale-0' : 'opacity-100 scale-100'"
        >
            <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"></path>
                <path d="M12 5v14"></path>
            </svg>
        </span>

        <span
            class="self-center justify-self-center transition duration-300 ease-out [grid-area:1/-1]"
            :class="isTagSelected('{{ $safeTag }}') ? 'opacity-100 scale-100' : 'opacity-0 scale-0'"
        >
            <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 6 9 17l-5-5"></path>
            </svg>
        </span>
    </span>
</button>
