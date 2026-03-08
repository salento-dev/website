@props(['image_path', 'title', 'description'])

<div x-data aria-label="{{ $title }}" x-init="() => {
    const card = $el
    const background = $refs.background
    const featureImage = $refs.featureImage
    const patternBackground = $refs.patternBackground
    const topLeftCorner = $refs.topLeftCorner
    const bottomLeftCorner = $refs.bottomLeftCorner
    const topRightCorner = $refs.topRightCorner
    const bottomRightCorner = $refs.bottomRightCorner

    const tl = gsap.timeline({ paused: true })

    // Pattern background animation (infinite loop)
    const patternTl = gsap.timeline({ paused: true, repeat: -1 })
    patternTl.fromTo(
        patternBackground, { backgroundPositionY: '0px' }, { backgroundPositionY: '100px', duration: 10, ease: 'none' },
    )

    // Background scales up and changes color
    tl.fromTo(
        background, {
            scale: 1,
        }, {
            scale: 1.02,
            duration: 0.25,
            ease: 'power2.out',
        },
        0,
    )

    // Feature image scales down slightly
    tl.to(
        featureImage, {
            scale: 0.95,
            duration: 0.25,
            ease: 'sine.out',
        },
        0,
    )

    // Corner animations - move outward with stagger
    tl.to(
        topLeftCorner, {
            x: -8,
            y: -8,
            duration: 0.25,
            ease: 'power2.out',
        },
        0,
    )

    tl.to(
        topRightCorner, {
            x: 8,
            y: -8,
            duration: 0.25,
            ease: 'power2.out',
        },
        0,
    )

    tl.to(
        bottomLeftCorner, {
            x: -8,
            y: 8,
            duration: 0.25,
            ease: 'power2.out',
        },
        0,
    )

    tl.to(
        bottomRightCorner, {
            x: 8,
            y: 8,
            duration: 0.25,
            ease: 'power2.out',
        },
        0,
    )

    motion.hover(card, () => {
        tl.tweenTo(tl.duration())
        patternTl.play()

        return () => {
            tl.tweenTo(0)
            patternTl.pause()
        }
    })
}"
    class="group relative px-8 pb-10 text-center focus:outline-none focus-visible:ring focus-visible:ring-black/50"
    style="translate: none; rotate: none; scale: none; transform: translate(0px, 0px); opacity: 1;">

    <div x-ref="background"
        class="absolute inset-0 bg-surface-900 transition duration-300 ease-out group-hover:bg-primary-800/25"
        aria-hidden="true" style="translate: none; rotate: none; scale: none; transform: translate(0px, 0px);"></div>



    <div class="relative z-1 grid place-items-center">
        <div
            class="relative top-1 z-3 inline-grid size-22 place-items-center self-center justify-self-center rounded-full bg-surface-800 transition duration-400 ease-out [grid-area:1/-1] group-hover:bg-primary-900">
            <img x-ref="featureImage" src="{{ $image_path ? asset(ltrim($image_path, '/')) : '' }}"
                alt="Filament {{ $title }}" loading="lazy" width="351" height="300" aria-hidden="true"
                class="h-auto max-h-15 w-auto max-w-15"
                style="translate: none; rotate: none; scale: none; transform: translate(0px, 0px);">
        </div>


        <div class="relative z-1 self-center justify-self-center mask-y-from-80% [grid-area:1/-1]">
            <div x-ref="patternBackground" class="bg-x-pattern h-45 w-40 mask-x-from-35% bg-repeat"
                style="background-position-y: 77px;"></div>
        </div>
    </div>


    <div class="relative z-1 mt-3 flex flex-col items-center gap-1.5">
        <div class="relative">

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
                        style="translate: none; rotate: none; scale: none; transform: rotate(3058.03deg);">
                        <div class="flex flex-col gap-1">

                            <div data-box="" class="size-0.75 bg-current" style="opacity: 0.9978;"></div>


                            <div data-box="" class="size-0.75 bg-current" style="opacity: 0.7038;"></div>
                        </div>


                        <div data-box="" class="size-0.75 bg-current" style="opacity: 0.9383;"></div>
                    </div>
                </div>
            </div>


            <p class="font-outfit text-xl font-medium text-mist-200 transition duration-300 ease-out will-change-transform group-hover:translate-x-0.5 md:text-2xl">
                {{ $title }}
            </p>
        </div>


        <p class="text-mist-400">{{ $description }}</p>
    </div>


    <div x-ref="topLeftCorner" class="absolute top-0 left-0" aria-hidden="true"
        style="translate: none; rotate: none; scale: none; transform: translate(0px, 0px);">
        <svg class="h-2.5 text-mist-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 11 11" fill="none">
            <path d="M9.5 0.5H0.5V9.5" class="stroke-current" stroke-linecap="round"></path>
        </svg>
    </div>


    <div x-ref="bottomLeftCorner" class="absolute bottom-0 left-0" aria-hidden="true"
        style="translate: none; rotate: none; scale: none; transform: translate(0px, 0px);">
        <svg class="h-2.5 -scale-y-100 text-mist-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 11 11"
            fill="none">
            <path d="M9.5 0.5H0.5V9.5" class="stroke-current" stroke-linecap="round"></path>
        </svg>
    </div>


    <div x-ref="topRightCorner" class="absolute top-0 right-0" aria-hidden="true"
        style="translate: none; rotate: none; scale: none; transform: translate(0px, 0px);">
        <svg class="h-2.5 -scale-x-100 text-mist-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 11 11"
            fill="none">
            <path d="M9.5 0.5H0.5V9.5" class="stroke-current" stroke-linecap="round"></path>
        </svg>
    </div>


    <div x-ref="bottomRightCorner" class="absolute right-0 bottom-0" aria-hidden="true"
        style="translate: none; rotate: none; scale: none; transform: translate(0px, 0px);">
        <svg class="h-2.5 -scale-x-100 -scale-y-100 text-mist-400" xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 11 11" fill="none">
            <path d="M9.5 0.5H0.5V9.5" class="stroke-current" stroke-linecap="round"></path>
        </svg>
    </div>
</div>
