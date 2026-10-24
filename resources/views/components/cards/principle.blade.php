@props(['image_path', 'title', 'description'])

<div x-data aria-label="{{ $title }}" x-init="() => {
    const card = $el
    const background = $refs.background
    const featureImage = $refs.featureImage
    const patternBackground = $refs.patternBackground
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

    motion.hover(card, () => {
        tl.tweenTo(tl.duration())
        patternTl.play()

        return () => {
            tl.tweenTo(0)
            patternTl.pause()
        }
    })
}"
    class="group relative px-8 pb-10 text-center focus:outline-none focus-visible:ring focus-visible:ring-primary/50"
    style="translate: none; rotate: none; scale: none; transform: translate(0px, 0px); opacity: 1;">

    <div x-ref="background"
        class="absolute inset-0 bg-surface-900 transition duration-300 ease-out group-hover:bg-primary-800/25"
        aria-hidden="true" style="translate: none; rotate: none; scale: none; transform: translate(0px, 0px);"></div>



    <div class="relative z-1 grid place-items-center">
        <div
            class="relative top-1 z-3 inline-grid size-18 md:size-22 place-items-center self-center justify-self-center rounded-full bg-surface-800 transition duration-400 ease-out [grid-area:1/-1] group-hover:bg-primary-900">
            <img x-ref="featureImage" src="{{ $image_path ? asset(ltrim($image_path, '/')) : '' }}"
                alt="Filament {{ $title }}" loading="lazy" aria-hidden="true"
                class="h-auto max-h-10 md:max-h-15 w-auto max-w-10 md:max-w-15"
                style="translate: none; rotate: none; scale: none; transform: translate(0px, 0px);">
        </div>


        <div class="relative z-1 self-center justify-self-center mask-y-from-80% [grid-area:1/-1]">
            <div x-ref="patternBackground" class="bg-x-pattern h-30 md:h-45 w-40 mask-x-from-35% bg-repeat"
                style="background-position-y: 77px;"></div>
        </div>
    </div>


    <div class="relative z-1 mt-3 flex flex-col items-center gap-1.5">
        <div class="relative">

            <x-ui.title-indicator />


            <p class="font-outfit text-xl font-medium text-surface-200 transition duration-300 ease-out will-change-transform group-hover:translate-x-0.5 md:text-2xl">
                {{ $title }}
            </p>
        </div>


        <p class="text-surface-400">{{ $description }}</p>
    </div>


    <x-ui.corners :hover="true" />
</div>
