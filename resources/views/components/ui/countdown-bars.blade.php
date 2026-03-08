@props(['timestamp'])

<div
    class="font-mono"
    x-data="{
        target: {{ $timestamp }},
        days: 0, hours: 0, minutes: 0, seconds: 0,
        finished: false,
        init() {
            this.tick();
            setInterval(() => this.tick(), 1000);
        },
        tick() {
            const diff = this.target - Math.floor(Date.now() / 1000);
            if (diff <= 0) { this.finished = true; return; }
            this.days    = Math.floor(diff / 86400);
            this.hours   = Math.floor((diff % 86400) / 3600);
            this.minutes = Math.floor((diff % 3600) / 60);
            this.seconds = diff % 60;
        },
        pad(n) { return String(n).padStart(2, '0'); }
    }"
    x-cloak
>
    <template x-if="finished">
        <span class="block text-2xl font-bold tabular-nums text-mist-50">Oggi</span>
    </template>
    <template x-if="!finished">
        <div class="flex items-end gap-4">
            <template x-if="days > 0">
                <div class="flex flex-col gap-1">
                    <span class="text-2xl font-bold tabular-nums leading-none text-mist-50 md:text-3xl" x-text="days"></span>
                    <div class="h-px w-full bg-surface-700">
                        <div class="h-px bg-primary transition-all duration-1000" :style="`width: ${Math.min((days % 30) / 29 * 100, 100)}%`"></div>
                    </div>
                    <span class="text-[9px] uppercase tracking-widest text-mist-500">giorni</span>
                </div>
            </template>
            <div class="flex flex-col gap-1">
                <span class="text-2xl font-bold tabular-nums leading-none text-mist-50 md:text-3xl" x-text="pad(hours)"></span>
                <div class="h-px w-full bg-surface-700">
                    <div class="h-px bg-primary transition-all duration-1000" :style="`width: ${hours / 23 * 100}%`"></div>
                </div>
                <span class="text-[9px] uppercase tracking-widest text-mist-500">ore</span>
            </div>
            <div class="flex flex-col gap-1">
                <span class="text-2xl font-bold tabular-nums leading-none text-mist-50 md:text-3xl" x-text="pad(minutes)"></span>
                <div class="h-px w-full bg-surface-700">
                    <div class="h-px bg-primary transition-all duration-1000" :style="`width: ${minutes / 59 * 100}%`"></div>
                </div>
                <span class="text-[9px] uppercase tracking-widest text-mist-500">min</span>
            </div>
            <div class="flex flex-col gap-1">
                <span class="text-2xl font-bold tabular-nums leading-none text-mist-50 md:text-3xl" x-text="pad(seconds)"></span>
                <div class="h-px w-full bg-surface-700">
                    <div class="h-px bg-primary transition-all duration-1000" :style="`width: ${seconds / 59 * 100}%`"></div>
                </div>
                <span class="text-[9px] uppercase tracking-widest text-mist-500">sec</span>
            </div>
        </div>
    </template>
</div>
