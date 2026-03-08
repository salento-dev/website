<div
    x-data
    x-init="() => {
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
    }"
    class="absolute top-1/2 -left-4 -translate-y-1/2"
    aria-hidden="true"
>
    <div class="translate-x-0.5 opacity-0 transition duration-300 ease-out will-change-transform group-hover:translate-x-0 group-hover:opacity-100">
        <div data-rotating class="flex items-center gap-0.75">
            <div class="flex flex-col gap-1">
                <div data-box class="size-0.75 bg-current"></div>
                <div data-box class="size-0.75 bg-current"></div>
            </div>
            <div data-box class="size-0.75 bg-current"></div>
        </div>
    </div>
</div>
