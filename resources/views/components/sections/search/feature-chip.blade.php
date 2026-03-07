@props([
    'state',
    'label',
])

<button
    type="button"
    x-on:click="{{ $state }} = !{{ $state }}"
    :aria-pressed="{{ $state }}"
    :class="{{ $state }} ? 'bg-honey-100 font-medium text-stone-800' : 'bg-cream-100 hover:bg-honey-100/50'"
    class="inline-flex h-9 cursor-pointer items-center justify-center gap-1.5 rounded-xs px-3 text-sm transition duration-300 ease-out select-none focus:outline-none focus-visible:ring focus-visible:ring-black/50 focus-visible:ring-inset active:scale-95"
>
    {{ $slot }}
    <span>{{ $label }}</span>
</button>
