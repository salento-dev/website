@props([
    'event',
    'variant' => 'default', // 'default' or 'simple'
])

@php
    $startsAt = $event->local_starts_at;
    $titleId = "event-card-{$event->id}-{$variant}-title";
    $metaId = "event-card-{$event->id}-{$variant}-meta";
@endphp


@if (!$event->work_in_progress)
    <a href="{{ route('events.show', $event) }}" aria-labelledby="{{ $titleId }} {{ $metaId }}"
        class="block h-full bg-surface-900 focus:outline-none focus-visible:ring focus-visible:ring-primary/50 focus-visible:ring-inset">
@endif
<div class="bg-surface-900 h-full {{ $event->work_in_progress ? 'cursor-not-allowed' : '' }}">
    <article
        class="group row-span-5 h-full flex flex-col gap-0 rounded-xs p-5 hover:bg-primary-500/5 transition-colors duration-300 ease-out">

        <div class="flex flex-col gap-1">

            <div class="shrink-0 self-start" aria-hidden="true">
                <svg class="h-2.25 text-surface-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 11 11"
                    fill="none">
                    <path d="M9.5 0.5H0.5V9.5" class="stroke-current" stroke-linecap="round"></path>
                </svg>
            </div>

            <div class="px-3">
                @if ($variant === 'default')
                    <div
                        class="aspect-video overflow-hidden rounded-xs ring ring-surface-700 transition duration-300 ease-out group-hover:scale-101">
                        <img src="{{ $event->image_url }}" alt=""
                            class="size-full object-cover" loading="lazy">
                    </div>
                @else
                    <div class="flex min-h-18 flex-col items-center justify-center gap-1.5 px-3 text-center">
                        <h3 id="{{ $titleId }}"
                            class="line-clamp-2 font-outfit text-lg font-medium wrap-anywhere text-surface-200 transition duration-300 ease-out group-hover:-translate-y-0.5">
                            {{ $event->title }}
                        </h3>
                        <x-events.meta :event="$event" :id="$metaId" class="justify-center" />
                    </div>
                @endif
            </div>

            <div class="shrink-0 self-end" aria-hidden="true">
                <svg class="h-2.25 -scale-x-100 -scale-y-100 text-surface-600" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 11 11" fill="none">
                    <path d="M9.5 0.5H0.5V9.5" class="stroke-current" stroke-linecap="round"></path>
                </svg>
            </div>
        </div>

        @if ($variant === 'default')
            <div class="mt-3 mb-auto flex flex-col gap-1">
                <div
                    class="relative transition duration-300 ease-out will-change-transform group-hover:translate-x-4.5">

                    <x-ui.title-indicator />


                    <h3 id="{{ $titleId }}" class="line-clamp-2 font-outfit font-bold wrap-anywhere text-surface-50 group-hover:text-primary">
                        {{ $event->title }}
                    </h3>
                </div>


                <x-events.meta :event="$event" :id="$metaId" class="mb-1" />

                @if ($event->plain_excerpt)
                    <p class="line-clamp-2 text-sm wrap-break-word">
                        {{ $event->plain_excerpt }}
                    </p>
                @endif
            </div>
        @endif

        <div
            class="mt-3.75 mb-3 flex h-11 items-stretch {{ $event->work_in_progress ? 'bg-amber-500/20 border border-amber-500/30' : ($event->isPast() ? 'bg-surface-800' : 'bg-surface-700') }} text-sm font-medium {{ $event->work_in_progress ? 'text-amber-200' : 'text-surface-50' }} transition-all duration-300 ease-out {{ !$event->isPast() && !$event->work_in_progress ? 'group-hover:bg-primary-800' : '' }}">
            <div class="inline-flex grow items-center justify-between gap-3 px-4">
                <span>{{ $event->work_in_progress ? 'In pianificazione' : 'Read' }}</span>
                <span
                    class="transition duration-300 ease-out {{ !$event->isPast() && !$event->work_in_progress ? 'group-hover:translate-x-1' : '' }}">
                    @if ($event->work_in_progress)
                        <x-heroicon-o-clock class="h-3" aria-hidden="true" />
                    @else
                        <x-heroicon-o-arrow-right class="h-3" aria-hidden="true" />
                    @endif
                </span>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-1.5">

            @if ($event->is_community_event)
                <span class="inline-flex h-7 shrink-0 items-center rounded-xs bg-surface-800 px-2"
                    title="Organizzato da Salento.dev">
                    <x-ui.logo class="h-3.5 w-auto text-primary-400" aria-hidden="true" />
                    <span class="sr-only">Organizzato da Salento.dev</span>
                </span>
            @endif

            @foreach ($event->categories as $category)
                <span
                    class="inline-flex h-7 max-w-full min-w-0 items-center rounded-xs bg-surface-800 px-2 text-xs font-medium text-surface-200">
                    <span class="truncate">{{ $category->name }}</span>
                </span>
            @endforeach

            <div class="grow"></div>

            @if ($startsAt)
                <time class="text-xs" datetime="{{ $startsAt->toIso8601String() }}">
                    {{ $startsAt->diffForHumans() }}
                </time>
            @endif

        </div>

    </article>
</div>
@if (!$event->work_in_progress)
    </a>
@endif
