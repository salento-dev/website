@php
$skills = [
    'Frontend', 'Backend', 'Full-stack',
    'Mobile', 'Game', 'Embedded/IoT',
    'AI/ML Developer', 'Data Scientist',
    'DevOps', 'SRE', 'Platform/Cloud',
    'Data Platform', 'DataOps',
    'Cybersecurity', 'DevSecOps',
    'QA/Automation', 'Test Engineering',
    'Infrastructure',
];

@endphp
<div x-data="" x-init="async () => {
    let registeredLoop = null

    const registerLoop = (newLoop) => {
        const arr = window.__heroAnimations
        if (!arr) return
        if (registeredLoop) {
            const idx = arr.indexOf(registeredLoop)
            if (idx !== -1) arr.splice(idx, 1)
        }
        arr.push(newLoop)
        registeredLoop = newLoop
    }

    const init = () => {
        const items = gsap.utils.toArray($el.querySelectorAll('li'))

        // Reset any previous transforms before rebuilding.
        gsap.set(items, { x: 0, xPercent: 0 })

        const loop = window.FilamentAnimations.horizontalLoop(items, {
            repeat: -1,
            speed: 0.3,
            paused: window.prefersReducedMotion(),
        })

        if (!window.prefersReducedMotion()) {
            loop.play()
        }

        return loop
    }

    // Start immediately with fallback metrics to avoid feeling late.
    let loop = init()
    registerLoop(loop)

    // Wait for our actual font families to be available.
    if (window.FilamentFonts?.ready) {
        await window.FilamentFonts.ready
    }

    // Let layout settle after potential font swap.
    await new Promise((resolve) => requestAnimationFrame(resolve))

    if (loop) {
        loop.kill()
    }
    loop = init()
    registerLoop(loop)

    // If late font swaps or other layout shifts happen, rebuild.
    // Debounced to avoid rebuilding the loop on every resize frame.
    let resizeTimer = null
    const ro = new ResizeObserver(() => {
        if (resizeTimer) cancelAnimationFrame(resizeTimer)
        resizeTimer = requestAnimationFrame(() => {
            if (loop) {
                loop.kill()
            }
            loop = init()
            registerLoop(loop)
        })
    })

    ro.observe($el)
}" aria-hidden="true"
    class="mt-10 overflow-hidden border-t border-border mask-[linear-gradient(to_right,transparent,black_10%,black_90%,transparent)]">
    <ul class="flex text-center text-sm">
        @foreach ($skills as $skill)
            <li class="border-r border-border px-3.5 py-2.5 whitespace-nowrap will-change-transform">
                {{ $skill }}
            </li>
        @endforeach
        @foreach ($skills as $skill)
            <li class="border-r border-border px-3.5 py-2.5 whitespace-nowrap will-change-transform">
                {{ $skill }}
            </li>
        @endforeach
    </ul>
</div>
