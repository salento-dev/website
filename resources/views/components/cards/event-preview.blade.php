@props([
    'event',
])

<div class="group relative flex flex-col sm:flex-row gap-6 rounded-lg border border-border bg-surface-900 p-6 transition-all duration-200 hover:border-primary/50">
    <div class="flex flex-col items-center justify-center rounded-lg bg-primary/10 border border-primary/20 px-6 py-4 text-primary min-w-[80px]">
        <span class="text-2xl font-bold">{{ $event->starts_at?->format('d') }}</span>
        <span class="text-xs font-semibold uppercase tracking-wider">{{ $event->starts_at?->format('M') }}</span>
    </div>
    <div class="flex-1 flex flex-col">
        <h4 class="text-xl font-bold text-stone-50 mb-3 group-hover:text-primary transition-colors duration-200">{{ $event->title }}</h4>
        <p class="text-sm text-stone-400 mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">location_on</span> {{ $event->location }}
        </p>
        @if ($event->relationLoaded('categories') && $event->categories->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-5">
                @foreach ($event->categories->take(2) as $category)
                    <span class="px-3 py-1 rounded-full border border-border text-xs font-medium text-stone-400">{{ $category->name }}</span>
                @endforeach
            </div>
        @endif
        <a href="{{ route('events.show', $event) }}" class="inline-flex items-center justify-center px-6 py-2.5 rounded-lg border border-primary bg-primary text-sm font-semibold text-white hover:bg-primary-dark focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 transition-all duration-200 mt-auto self-start">Visualizza Evento</a>
    </div>
</div>