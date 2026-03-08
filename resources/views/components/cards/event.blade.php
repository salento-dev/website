@props([
    'event',
    'variant' => 'default', // 'default' or 'simple'
])

@php
    $image_url = $event->image_path
        ? asset('storage/' . ltrim($event->image_path, '/'))
        : asset('storage/placeholders/events/event-placeholder.jpg');
@endphp

<a href="{{ route('events.show', $event) }}" aria-label="View {{ $event->title }} event page" class="block bg-surface-900">
    <article
        class="group row-span-5 grid grid-rows-subgrid gap-0 rounded-xs p-5 hover:bg-primary-500/5 transition-colors duration-300 ease-out">

        <div class="flex flex-col gap-1">

            <div class="shrink-0 self-start" aria-hidden="true">
                <svg class="h-2.25 text-mist-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 11 11" fill="none">
                    <path d="M9.5 0.5H0.5V9.5" class="stroke-current" stroke-linecap="round"></path>
                </svg>
            </div>



            <div class="px-3">
                @if ($variant === 'default')
                    <div
                        class="aspect-video overflow-hidden rounded-xs ring ring-cocoa transition duration-300 ease-out group-hover:scale-101">
                        <img src="{{ $image_url }}" alt="{{ $event->title }} event thumbnail"
                            class="size-full object-cover" loading="lazy">
                    </div>
                @else
                    <div class="flex min-h-18 items-center justify-center px-3 text-center">
                        <h3 class="line-clamp-2 font-outfit text-lg font-medium text-mist-200 transition duration-300 ease-out group-hover:-translate-y-0.5">
                            {{ $event->title }}
                        </h3>
                    </div>
                @endif
            </div>



            <div class="shrink-0 self-end" aria-hidden="true">
                <svg class="h-2.25 -scale-x-100 -scale-y-100 text-mist-600" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 11 11" fill="none">
                    <path d="M9.5 0.5H0.5V9.5" class="stroke-current" stroke-linecap="round"></path>
                </svg>
            </div>
        </div>


        @if ($variant === 'default')
        <div class="mt-3 flex flex-col gap-1">
            <div class="relative transition duration-300 ease-out will-change-transform group-hover:translate-x-4.5">

                <x-ui.title-indicator />


                <p class="line-clamp-1 font-outfit font-medium text-mist-200">
                    {{ $event->title }}
                </p>
            </div>


            <p class="line-clamp-2 text-sm">
                {{ strip_tags($event->description) }}
            </p>
        </div>
        @endif

        <div
            class="mt-3.75 mb-3 flex h-11 items-stretch bg-surface-700 text-sm font-medium text-mist-50 transition-all duration-300 ease-out group-hover:bg-primary-800">
            <div class="inline-flex grow items-center justify-between gap-3 px-4">
                <span>Read</span>
                <span class="transition duration-300 ease-out group-hover:translate-x-1">
                    <x-heroicon-o-arrow-right class="h-3" />
                </span>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-1.5">

            @foreach ($event->categories as $category)
                <span
                    class="inline-flex h-7 shrink-0 items-center rounded-xs bg-surface-800 px-2 text-xs font-medium text-mist-200">
                    {{ $category->name }}
                </span>
            @endforeach

            <div class="grow"></div>

            <span class="text-xs" title="{{ $event->starts_at->format('M d, Y') }}">
                {{ $event->starts_at->diffForHumans() }}
            </span>

        </div>

    </article>
</a>
