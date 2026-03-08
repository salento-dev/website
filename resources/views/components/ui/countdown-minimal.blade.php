@props(['timestamp'])

<div class="font-mono" x-data="{
    target: {{ $timestamp }},
    days: 0,
    hours: 0,
    minutes: 0,
    seconds: 0,
    finished: false,
    init() {
        this.tick();
        setInterval(() => this.tick(), 1000);
    },
    tick() {
        const diff = this.target - Math.floor(Date.now() / 1000);
        if (diff <= 0) { this.finished = true; return; }
        this.days = Math.floor(diff / 86400);
        this.hours = Math.floor((diff % 86400) / 3600);
        this.minutes = Math.floor((diff % 3600) / 60);
    },
    pad(n) { return String(n).padStart(2, '0'); }
}" x-cloak>
    <template x-if="finished">
        <span class="text-xs uppercase tracking-widest text-primary">Oggi</span>
    </template>
    <template x-if="!finished">
        <div class="flex items-baseline gap-3 tabular-nums text-mist-50">
            <template x-if="days > 0">
                <span>
                    <span class="text-xl font-semibold" x-text="days"></span><span class="text-xs uppercase text-mist-400">g</span>
                </span>
            </template>
            <template x-if="hours > 0">
                <span>
                    <span class="text-xl font-semibold" x-text="pad(hours)"></span><span class="text-xs uppercase text-mist-400">h</span>
                </span>
            </template>
            <template x-if="minutes > 0">
                <span>
                    <span class="text-xl font-semibold" x-text="pad(minutes)"></span><span class="text-xs uppercase text-mist-400">m</span>
                </span>
            </template>
        </div>
    </template>
</div>
