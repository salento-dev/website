@props([
    'event',
])

<div class="relative flex flex-col sm:flex-row gap-6 border border-border-cyan bg-background-dark p-6 transition-all hover:bg-card-dark">
    <div class="flex flex-col items-center justify-center rounded bg-primary/10 border border-primary/30 px-6 py-4 text-primary">
        <span class="text-2xl font-black">{{ $event->starts_at?->format('d') }}</span>
        <span class="text-[10px] uppercase font-bold tracking-widest">{{ $event->starts_at?->format('M') }}</span>
    </div>
    <div class="flex-1">
        <h4 class="text-xl font-bold uppercase text-white mb-2">{{ $event->title }}</h4>
        <p class="text-sm text-white/50 mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">location_on</span> {{ $event->location }}
        </p>
        @if ($event->relationLoaded('categories') && $event->categories->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-6">
                @foreach ($event->categories->take(2) as $category)
                    <span class="px-2 py-0.5 rounded bg-white/5 border border-white/10 text-[10px] uppercase text-white/60">{{ $category->name }}</span>
                @endforeach
            </div>
        @endif
        <a href="{{ route('events.show', $event) }}" class="w-full sm:w-auto px-6 py-2 rounded bg-primary text-[10px] font-bold uppercase tracking-widest text-background-dark hover:shadow-neon inline-block text-center">Attend Event</a>
    </div>
</div>