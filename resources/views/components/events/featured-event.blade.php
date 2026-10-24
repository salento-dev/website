@props(['event'])

@php
    $dateFormatted = $event->local_starts_at->translatedFormat('l j F Y');
    $timeFormatted = $event->local_starts_at->format('H:i');
    $daysUntil = (int) now('Europe/Rome')->startOfDay()->diffInDays($event->local_starts_at->startOfDay(), false);
    $endsAt = $event->local_ends_at ?? $event->local_starts_at->copy()->endOfDay();
    $isLive = now('Europe/Rome')->between($event->local_starts_at, $endsAt);
@endphp

<a href="{{ route('events.show', $event) }}" aria-label="Scopri l'evento {{ $event->title }}"
    class="group relative block bg-surface-900 transition-all duration-500 ease-out">

    {{-- Background image with overlay --}}
    <div class="absolute inset-0 overflow-hidden">
        <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="size-full object-cover opacity-80 transition-all duration-700 ease-out group-hover:opacity-40 group-hover:scale-105">
        <div class="absolute inset-0 bg-gradient-to-r from-surface-900 via-surface-900/70 to-surface-900/40"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-surface-900/80 via-transparent to-transparent"></div>
    </div>

    {{-- Corner accents --}}
    <x-ui.corners color="text-primary" :hover="true" size="h-3" />

    <div class="relative flex flex-col gap-6 p-8 md:p-10 lg:p-12">

        {{-- Top bar --}}
        <div class="flex flex-wrap gap-4 items-start justify-between">
            @if ($isLive)
                <span class="inline-flex items-center gap-2 rounded-full border border-green-500/40 bg-green-500/10 px-3 py-1 font-mono text-2xs uppercase tracking-widest text-green-400">
                    <span class="relative flex h-1.5 w-1.5">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-green-400"></span>
                    </span>
                    In corso
                </span>
            @else
                <span class="inline-flex items-center gap-2 rounded-full border border-primary/30 bg-primary/10 px-3 py-1 font-mono text-2xs uppercase tracking-widest text-primary">
                    <span class="relative flex h-1.5 w-1.5">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-primary opacity-75"></span>
                        <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-primary"></span>
                    </span>
                    Prossimo evento
                </span>

                @if ($daysUntil >= 0)
                    <x-ui.countdown-minimal :timestamp="$event->starts_at->timestamp" />
                @endif
            @endif
        </div>

        {{-- Title + description --}}
        <div class="max-w-2xl">
            <h3 class="mb-3 font-outfit text-3xl font-bold leading-tight text-surface-50 transition-colors duration-300 md:text-4xl lg:text-5xl">
                {{ $event->title }}
            </h3>
            @if ($event->plain_excerpt)
                <p class="line-clamp-2 text-sm leading-relaxed text-surface-400 md:text-base">
                    {{ $event->plain_excerpt }}
                </p>
            @endif
        </div>

        {{-- Meta details --}}
        <div class="flex flex-wrap items-center gap-x-6 gap-y-2 font-mono text-xs text-surface-400">
            <span class="flex items-center gap-2">
                <x-heroicon-o-calendar-days class="h-3.5 text-primary/70" />
                {{ $dateFormatted }} {{ $timeFormatted !== '00:00' ? '· ' . $timeFormatted : '' }}
            </span>
            @if ($event->location)
                <span class="flex items-center gap-2">
                    <x-heroicon-o-map-pin class="h-3.5 text-primary/70" />
                    {{ $event->location }}
                </span>
            @endif
        </div>

        {{-- Footer: categories + CTA --}}
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap gap-1.5">
                @if ($event->is_community_event)
                    <span class="inline-flex h-6 items-center rounded-xs bg-surface-800/80 px-2">
                        <x-ui.logo class="h-3.5 w-auto text-primary-400" />
                    </span>
                @endif
                @foreach ($event->categories as $category)
                    <span class="inline-flex h-6 items-center rounded-xs bg-surface-800/80 px-2 text-2xs font-medium uppercase tracking-wider text-surface-200/70">
                        {{ $category->name }}
                    </span>
                @endforeach
            </div>

            <span class="inline-flex h-11 items-center gap-3 border border-primary bg-primary/10 px-6 font-medium text-primary transition-all duration-300 ease-out group-hover:bg-primary group-hover:text-surface-900">
                Scopri e partecipa
                <x-heroicon-o-arrow-right class="h-4 transition-transform duration-300 group-hover:translate-x-1" />
            </span>
        </div>

    </div>
</a>
