@props(['event', 'href' => null, 'variant' => 'default'])

@php
    $image_url = $event->image_path
        ? asset('storage/' . ltrim($event->image_path, '/'))
        : asset('storage/placeholders/events/event-placeholder.jpg');
@endphp

<a href="{{ $href ?? route('events.show', $event) }}" aria-label="View {{ $event->title }} event page" class="block bg-surface-900">
    <article class="group row-span-5 grid grid-rows-subgrid gap-0 rounded-xs p-5 hover:bg-primary-500/5 transition-colors duration-300 ease-out">

        <div class="flex flex-col gap-1">

            <div class="shrink-0 self-start" aria-hidden="true">
                <svg class="h-2.25 text-stone-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 11 11" fill="none">
                    <path d="M9.5 0.5H0.5V9.5" class="stroke-current" stroke-linecap="round"></path>
                </svg>
            </div>


            <div class="px-3">
                <div
                    class="aspect-video overflow-hidden rounded-xs ring ring-cocoa transition duration-300 ease-out group-hover:scale-101">
                    <img src="{{ $image_url }}" alt="{{ $event->title }} event thumbnail" class="size-full object-cover"
                        loading="lazy">
                </div>
            </div>


            <div class="shrink-0 self-end" aria-hidden="true">
                <svg class="h-2.25 -scale-x-100 -scale-y-100 text-stone-600" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 11 11" fill="none">
                    <path d="M9.5 0.5H0.5V9.5" class="stroke-current" stroke-linecap="round"></path>
                </svg>
            </div>
        </div>


        <div class="mt-3 flex flex-col gap-1">
            <div class="relative transition duration-300 ease-out will-change-transform group-hover:translate-x-4.5">

                <div x-data="" x-init="() => {
                    const tweens = []
                    let playing = false
                
                    const rotatingEl = $el.querySelector('[data-rotating]')
                    const rotate = () => {
                        gsap.to(rotatingEl, {
                            rotation: '+=60',
                            duration: 0.5,
                            ease: 'sine.out',
                            onComplete: () => {
                                if (playing) gsap.delayedCall(0.5, rotate)
                            },
                        })
                    }
                
                    const boxes = $el.querySelectorAll('[data-box]')
                    const delays = [0, 0.2, 0.1]
                    boxes.forEach((box, i) => {
                        tweens.push(
                            gsap.to(box, {
                                opacity: 0.3,
                                repeat: -1,
                                yoyo: true,
                                duration: 0.4,
                                delay: delays[i] || 0,
                                ease: 'power1.inOut',
                                paused: true,
                            }),
                        )
                    })
                
                    const group = $el.closest('.group')
                    if (group) {
                        group.addEventListener('mouseenter', () => {
                            playing = true
                            tweens.forEach((t) => t.resume())
                            rotate()
                        })
                        group.addEventListener('mouseleave', () => {
                            playing = false
                            tweens.forEach((t) => t.pause())
                        })
                    }
                }" class="absolute top-1/2 -left-4 -translate-y-1/2">
                    <div
                        class="translate-x-0.5 opacity-0 transition duration-300 ease-out will-change-transform group-hover:translate-x-0 group-hover:opacity-100">
                        <div data-rotating="" class="flex items-center gap-0.75"
                            style="translate: none; rotate: none; scale: none; transform: rotate(480deg);">
                            <div class="flex flex-col gap-1">

                                <div data-box="" class="size-0.75 bg-current" style="opacity: 0.9642;"></div>


                                <div data-box="" class="size-0.75 bg-current" style="opacity: 0.4618;"></div>
                            </div>


                            <div data-box="" class="size-0.75 bg-current" style="opacity: 0.7647;"></div>
                        </div>
                    </div>
                </div>


                <p class="line-clamp-1 font-outfit font-medium text-mist-200">
                    {{ $event->title }}
                </p>
            </div>


            <p class="line-clamp-2 text-sm">
                {{ strip_tags($event->description) }}
            </p>
        </div>

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

            <span class="text-xs" title="{{ $event->created_at->format('M d, Y') }}">
                {{ $event->created_at->diffForHumans() }}
            </span>

        </div>

    </article>
</a>
