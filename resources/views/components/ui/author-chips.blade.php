@props(['authors', 'label' => null])

@if ($authors->isNotEmpty())
    <div class="mt-10 border-t border-border pt-8"
         x-data="{ open: false, selected: null }">

        <p class="mb-4 font-mono text-2xs uppercase tracking-widest text-surface-400">
            {{ $label ?? ($authors->count() > 1 ? 'Autori' : 'Autore') }}
        </p>

        <div class="flex flex-wrap gap-3">
            @foreach ($authors as $index => $author)
                <button
                    type="button"
                    @click="selected = {{ $index }}; open = true"
                    class="flex cursor-pointer items-center gap-2 rounded-full border border-border bg-surface-900 px-3 py-1.5 transition-colors hover:border-primary/40 hover:bg-surface-800"
                >
                    <img loading="lazy"
                        src="{{ $author->avatar_url }}"
                        alt="{{ $author->name }}"
                        class="size-6 rounded-full object-cover"
                    />
                    <span class="font-outfit text-sm font-semibold text-surface-50">{{ $author->name }}</span>
                </button>
            @endforeach
        </div>

        {{-- Modale --}}
        <div
            x-show="open"
            x-transition:enter="transition duration-200 ease-out"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition duration-150 ease-in"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            role="dialog"
            aria-modal="true"
            aria-label="Autori"
            @keydown.escape.window="open = false"
            style="display: none"
        >
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="open = false"></div>

            <div
                x-show="open"
                x-transition:enter="transition duration-200 ease-out"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition duration-150 ease-in"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                x-trap.noscroll="open"
                class="relative z-10 w-full max-w-sm border border-border bg-surface-900 p-6"
                style="display: none"
            >
                <button
                    type="button"
                    @click="open = false"
                    aria-label="Chiudi"
                    class="absolute right-4 top-4 text-surface-400 transition-colors hover:text-surface-50"
                >
                    <x-heroicon-o-x-mark class="size-5" />
                </button>

                @foreach ($authors as $index => $author)
                    <div x-show="selected === {{ $index }}">
                        <x-cards.author :author="$author" size="lg" />
                    </div>
                @endforeach
            </div>
        </div>

    </div>
@endif
