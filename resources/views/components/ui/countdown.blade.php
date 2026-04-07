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
        <span class="block text-3xl font-bold tabular-nums text-surface-50 md:text-4xl">Oggi</span>
    </template>
    <template x-if="!finished">
        <div class="flex items-end justify-end gap-2 text-surface-50">
            <template x-if="days > 0">
                <div class="flex flex-col items-center">
                    <span class="text-2xl font-bold tabular-nums md:text-3xl" x-text="days"></span>
                    <span class="text-[9px] uppercase tracking-widest text-primary">g</span>
                </div>
            </template>
            <div class="flex flex-col items-center">
                <span class="text-2xl font-bold tabular-nums md:text-3xl" x-text="pad(hours)"></span>
                <span class="text-[9px] uppercase tracking-widest text-primary">h</span>
            </div>
            <div class="flex flex-col items-center">
                <span class="text-2xl font-bold tabular-nums md:text-3xl" x-text="pad(minutes)"></span>
                <span class="text-[9px] uppercase tracking-widest text-primary">min</span>
            </div>
            <div class="flex flex-col items-center">
                <span class="text-2xl font-bold tabular-nums md:text-3xl" x-text="pad(seconds)"></span>
                <span class="text-[9px] uppercase tracking-widest text-primary">sec</span>
            </div>
        </div>
    </template>
</div>
