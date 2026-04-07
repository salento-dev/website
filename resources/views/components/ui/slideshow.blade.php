@props([
    'count' => 1,
    'interval' => 6000,
])

<div
    x-data="{
        current: 0,
        total: {{ $count }},
        timer: null,
        start() {
            if (this.total <= 1) return;
            this.timer = setInterval(() => this.next(), {{ $interval }});
        },
        stop() { clearInterval(this.timer); },
        next() { this.current = (this.current + 1) % this.total; },
        prev() { this.current = (this.current - 1 + this.total) % this.total; },
    }"
    x-init="start()"
    class="relative"
>
    {{ $slot }}

    @if ($count > 1)
        <div class="mt-4 flex items-center justify-between">
            {{-- Dots --}}
            <div class="flex items-center gap-2">
                @for ($i = 0; $i < $count; $i++)
                    <button
                        @click="current = {{ $i }}; stop(); start()"
                        class="h-1.5 rounded-full transition-all duration-300"
                        :class="current === {{ $i }} ? 'w-6 bg-primary' : 'w-1.5 bg-surface-600 hover:bg-surface-400'"
                        aria-label="Vai alla slide {{ $i + 1 }}"
                    ></button>
                @endfor
            </div>

            {{-- Frecce --}}
            <div class="flex items-center gap-2">
                <button
                    @click="prev()"
                    class="flex h-9 w-9 items-center justify-center border border-surface-600 text-surface-400 transition hover:border-primary hover:text-primary"
                    aria-label="Precedente"
                >
                    <x-heroicon-o-chevron-left class="h-4" />
                </button>
                <button
                    @click="next()"
                    class="flex h-9 w-9 items-center justify-center border border-surface-600 text-surface-400 transition hover:border-primary hover:text-primary"
                    aria-label="Successivo"
                >
                    <x-heroicon-o-chevron-right class="h-4" />
                </button>
            </div>
        </div>
    @endif
</div>
