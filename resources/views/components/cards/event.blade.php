@props(['event', 'href' => null, 'variant' => 'default'])

@php
    $image_url = $event->image_path
        ? asset('storage/' . ltrim($event->image_path, '/'))
        : asset('storage/placeholders/events/event-placeholder.jpg');
@endphp

<a href="{{ $href ?? route('events.show', $event) }}"
    class="group row-span-5 grid grid-rows-subgrid gap-0 rounded-xs p-5 bg-surface-900"
    aria-label="View {{ $event->title }} event page">

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
        class="mt-3.75 mb-3 flex h-11 items-stretch bg-surface-700 text-sm font-medium text-mist-50 transition-all duration-300 ease-out group-hover:bg-serenade-50">
        <div class="inline-flex grow items-center justify-between gap-3 px-4">
            <span>Read</span>
            <span class="transition duration-300 ease-out group-hover:translate-x-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3" viewBox="0 0 28 22" fill="none">
                    <path class="fill-current"
                        d="M1 10H5.96046e-08V12H1V10ZM27 12C27.5523 12 28 11.5523 28 11C28 10.4477 27.5523 10 27 10V12ZM18 1V5.96046e-08H16V1H18ZM26.4207 11.7774C26.9055 12.0419 27.5129 11.8632 27.7774 11.3783C28.0419 10.8935 27.8632 10.286 27.3783 10.0216L26.4207 11.7774ZM15.9999 20.8995V21.8995H17.9999V20.8995H15.9999ZM1 12H26.8994V10H1V12ZM26.8994 12H27V10H26.8994V12ZM16 1C16 2.47241 16.7953 3.87873 17.7716 5.0769C18.7678 6.29956 20.0716 7.44977 21.3383 8.42854C22.6109 9.41186 23.8784 10.2469 24.825 10.835C25.2993 11.1295 25.6952 11.3635 25.9738 11.5245C26.1131 11.605 26.2233 11.6674 26.2993 11.71C26.3374 11.7314 26.3669 11.7478 26.3873 11.7591C26.3975 11.7647 26.4055 11.7691 26.411 11.7721C26.4138 11.7737 26.416 11.7749 26.4176 11.7758C26.4184 11.7762 26.4191 11.7765 26.4196 11.7768C26.4199 11.777 26.4201 11.7771 26.4202 11.7772C26.4205 11.7773 26.4207 11.7774 26.8995 10.8995C27.3783 10.0216 27.3784 10.0217 27.3785 10.0217C27.3785 10.0217 27.3785 10.0217 27.3785 10.0217C27.3784 10.0216 27.3781 10.0215 27.3777 10.0213C27.3769 10.0208 27.3756 10.0201 27.3736 10.019C27.3697 10.0168 27.3634 10.0134 27.3549 10.0087C27.3378 9.99926 27.3118 9.98479 27.2773 9.96547C27.2084 9.92682 27.1058 9.86878 26.9745 9.79288C26.7117 9.64102 26.3342 9.41799 25.8804 9.13606C24.9708 8.57104 23.7635 7.77501 22.5612 6.84596C21.353 5.91235 20.182 4.86894 19.322 3.81356C18.4422 2.73371 18 1.77759 18 1H16ZM26.8994 11C26.5248 10.0728 26.5245 10.0729 26.5242 10.0731C26.524 10.0731 26.5237 10.0733 26.5234 10.0734C26.5228 10.0736 26.522 10.0739 26.5211 10.0743C26.5193 10.0751 26.5169 10.076 26.5138 10.0773C26.5078 10.0797 26.4994 10.0832 26.4888 10.0876C26.4674 10.0964 26.4369 10.1091 26.3979 10.1257C26.3199 10.1587 26.2077 10.2071 26.0662 10.2703C25.7834 10.3967 25.3826 10.5825 24.903 10.824C23.9463 11.3055 22.6639 12.0142 21.3751 12.919C20.0914 13.8201 18.7665 14.94 17.7546 16.2535C16.7415 17.5685 15.9999 19.1342 15.9999 20.8995H17.9999C17.9999 19.715 18.4958 18.5685 19.3389 17.4742C20.1831 16.3784 21.333 15.3922 22.5242 14.5559C23.7103 13.7232 24.9028 13.0632 25.8022 12.6104C26.2507 12.3846 26.6233 12.2119 26.8818 12.0965C27.011 12.0388 27.1115 11.9955 27.1785 11.967C27.212 11.9528 27.2371 11.9424 27.2533 11.9357C27.2613 11.9324 27.2671 11.93 27.2706 11.9286C27.2724 11.9279 27.2735 11.9274 27.2741 11.9271C27.2744 11.927 27.2745 11.927 27.2745 11.927C27.2745 11.927 27.2744 11.927 27.2744 11.927C27.2742 11.9271 27.274 11.9272 26.8994 11Z">
                    </path>
                </svg>
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
</a>
