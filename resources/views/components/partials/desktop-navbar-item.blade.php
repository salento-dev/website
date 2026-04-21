@php $isActive = request()->routeIs($route) @endphp
<a x-data="{ isActive: {{ $isActive ? 'true' : 'false' }} }" href="{{ route($route) }}" {{ $isActive ? 'aria-current="page"' : '' }}
    class="group relative inline-grid place-items-center px-2 py-1.5 transition-all duration-300 ease-out will-change-transform focus:outline-none focus-visible:ring focus-visible:ring-black/50 focus-visible:ring-inset {{ $isActive ? 'font-medium text-surface-50' : 'hover:font-medium hover:text-surface-50'}}"
    x-init="() => {
        const animation = gsap.timeline({ paused: true })
    
        animation
            .fromTo(
                $refs['navbar-link-top-left'], 
                { x: -15, y: -15, opacity: 0 }, 
                {
                    x: 0,
                    y: 0,
                    opacity: 1,
                    duration: 0.25,
                    ease: 'circ.out',
                },
            )
            .fromTo(
                $refs['navbar-link-bottom-right'], 
                { x: 15, y: 15, opacity: 0 }, 
                {
                    x: 0,
                    y: 0,
                    opacity: 1,
                    duration: 0.25,
                    ease: 'circ.out',
                },
                '<',
            )
            .to(
                $refs['navbar-link-label'], 
                {
                    scale: 0.9,
                    duration: 0.35,
                    ease: 'sine.out',
                },
                0,
            )
    
        if (!isActive) {
            motion.hover($el, () => {
                animation.play()
    
                return () => {
                    animation.reverse()
                }
            })
        } else {
            animation.progress(1)
        }
    }">
    <div class="absolute top-0 left-0" x-ref="navbar-link-top-left" aria-hidden="true" :class="{'grayscale': !isActive}" style="opacity: 0">
        <svg class="h-2 text-primary-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 11 11" fill="none">
            <path d="M9.5 0.5H0.5V9.5" class="stroke-current" stroke-linecap="round"></path>
        </svg>
    </div>

    <span x-ref="navbar-link-label" class="flex items-center gap-1.5 whitespace-nowrap">
        {{ $routeName }}
    </span>

    <div class="absolute right-0 bottom-0" x-ref="navbar-link-bottom-right" aria-hidden="true" :class="{'grayscale': !isActive}" style="opacity: 0">
        <svg class="h-2 -scale-x-100 -scale-y-100 text-primary-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 11 11"
            fill="none">
            <path d="M9.5 0.5H0.5V9.5" class="stroke-current" stroke-linecap="round"></path>
        </svg>
    </div>
</a>
