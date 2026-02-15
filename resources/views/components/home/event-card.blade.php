@props(['event'])

<article class="cyber-panel group flex h-full flex-col gap-4 border border-cyber-border bg-cyber-card p-6 transition-all hover:border-primary">
    <div class="flex items-start justify-between gap-3">
        <div class="border border-primary/30 bg-primary/10 px-2 py-1 text-[10px] font-bold text-primary">EVENTO</div>
        <span class="font-mono text-[10px] text-primary/40">{{ $event->starts_at?->format('d.m.Y_H:i') }}</span>
    </div>

    <h3 class="text-lg font-bold transition-colors group-hover:text-primary">{{ strtoupper($event->title) }}</h3>
    <p class="text-sm text-primary/60">{{ \Illuminate\Support\Str::limit($event->description, 110) }}</p>

    <div class="mt-auto flex items-center justify-between border-t border-cyber-border pt-4">
        <div class="flex items-center gap-1 text-[10px] text-primary/40 uppercase">
            <span class="material-symbols-outlined text-sm">location_on</span>
            {{ $event->location }}
        </div>
        <span class="flex size-8 items-center justify-center border border-cyber-border transition-all group-hover:border-primary group-hover:bg-primary group-hover:text-background-dark">
            <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </span>
    </div>
</article>