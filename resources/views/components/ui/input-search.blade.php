@props([
    'placeholder' => 'Search',
    'srLabel' => 'Search',
    'wireModel' => null,
    'listeningMessages' => [
        'Whatcha looking for? 🔍',
        "I'm listening... 👀",
        "Go ahead, I'm ready 🎯",
        'Type away! ⌨️',
        'Searching is fun! 🤓',
        'What can I find for you? 🕵️‍♂️',
    ],
])

<div
    class="group relative grow overflow-hidden rounded-xs bg-surface-800 text-surface-50 transition duration-300 ease-out focus-within:ring focus-within:ring-black/50 focus-within:ring-inset"
    x-data="{
        search: {{ $wireModel ? "\$wire.{$wireModel} ?? ''" : "''" }},
        listeningMessages: @js($listeningMessages),
        listeningIndex: 0,
        get listeningMessage() {
            if (this.listeningMessages.length === 0) return '';
            return this.listeningMessages[this.listeningIndex % this.listeningMessages.length];
        },
        rotateListeningMessage() { this.listeningIndex++; },
        clearSearch() { this.search = ''; },
        @if ($wireModel)
        init() {
            this.$watch('search', (value) => this.$wire.set('{{ $wireModel }}', value));
            this.$watch(() => this.$wire.{{ $wireModel }}, (value) => {
                const next = value ?? '';
                if (next !== this.search) this.search = next;
            });
        },
        @endif
    }"
>
    <div aria-hidden="true" class="pointer-events-none absolute top-3.5 left-3 flex flex-col items-center justify-center">
        <div class="size-2.5 -rotate-45 rounded-full ring-1 ring-surface-50 transition duration-500 ease-out group-focus-within:-scale-x-100"></div>
        <div class="relative -top-0.5 left-1.25 h-1.5 w-px -rotate-45 bg-surface-50"></div>
    </div>

    <label class="contents">
        <span class="sr-only">{{ $srLabel }}</span>
        <input
            x-ref="searchInput"
            name="search"
            type="search"
            autocomplete="off"
            placeholder="{{ $placeholder }}"
            class="-m-0.5 h-11 w-[calc(100%+4px)] border-2 border-black bg-transparent py-1.5 pr-19 pl-8.25 text-sm font-medium placeholder:text-surface-300 transition-all duration-300 ease-out focus:outline-none [&::-webkit-search-cancel-button]:appearance-none"
            x-model.debounce.250ms="search"
            @focus="rotateListeningMessage()"
        >
    </label>

    <div
        aria-hidden="true"
        class="pointer-events-none absolute top-3 right-2 hidden translate-y-3 text-xs opacity-0 transition duration-300 ease-out md:block group-focus-within:opacity-100 group-focus-within:translate-y-0"
        :class="{ 'group-focus-within:opacity-100 group-focus-within:translate-y-0': search.length === 0 }"
    >
        <div x-text="listeningMessage"></div>
    </div>

    <button
        type="button"
        @click="clearSearch()"
        aria-label="Cancella ricerca"
        :aria-hidden="search.length === 0"
        :tabindex="search.length === 0 ? -1 : 0"
        class="absolute top-1.5 right-1.5 flex h-7 items-center justify-center gap-0.75 rounded-xs bg-surface-900 pr-1.75 pl-2 text-xs opacity-0 transition-all duration-300 ease-out will-change-transform hover:bg-secondary/60"
        :class="{ 'pointer-events-auto opacity-100 hover:scale-95': search.length > 0, 'opacity-0 scale-50 pointer-events-none': search.length === 0 }"
    >
        <span>Cancella</span>
        <svg aria-hidden="true" class="size-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 6 6 18"></path>
            <path d="m6 6 12 12"></path>
        </svg>
    </button>
</div>
