@props([
    'href' => '#',
    'text' => '',
    'ariaLabel' => '',
    'target' => '_blank',
    'rel' => 'noopener noreferrer',
    'iconSize' => '4',
])

@php
    $uid = 'spark_' . uniqid();
    $hasText = !empty($text);

    $iconSizeClass = match ($iconSize) {
        '3' => 'size-3',
        '5' => 'size-5',
        '6' => 'size-6',
        default => 'size-4',
    };

    $linkClasses = 'group flex items-center whitespace-nowrap transition-all duration-300 ease-out focus:outline-none focus-visible:ring focus-visible:ring-primary/50 focus-visible:ring-inset hover:bg-green-500/10';
    $linkClasses .= $hasText
        ? ' gap-2.5 pr-4 pb-0.5 pl-3.5 hover:pr-5'
        : ' justify-center px-3.5';
@endphp

<a
    href="{{ $href }}"
    target="{{ $target }}"
    rel="{{ $rel }}"
    aria-label="{{ $ariaLabel ?: $text }}"
    {{ $attributes->merge(['class' => $linkClasses]) }}
    x-init="
        () => {
            const icon = $refs.{{ $uid }}Icon
            const sparks = $refs.{{ $uid }}Sparks.querySelectorAll('[data-spark]')
            gsap.set(sparks, { opacity: 0 })
            const anim = gsap.timeline({ paused: true })
            anim.to(icon, { scale: 1.25, duration: 0.2, ease: 'back.out(2)' }, 0)
            sparks.forEach((spark, i) => {
                anim.fromTo(
                    spark,
                    { opacity: 1, '--spark-distance': '0px' },
                    { opacity: 0, '--spark-distance': '8px', duration: 0.5, ease: 'power2.out', immediateRender: false },
                    0.02 + i * 0.02,
                )
            })
            motion.hover($el, () => {
                anim.restart()
                return () => gsap.to(icon, { scale: 1, duration: 0.3, ease: 'power2.out' })
            })
        }
    "
>
    <div class="relative" x-ref="{{ $uid }}Sparks">
        <div x-ref="{{ $uid }}Icon" class="{{ $iconSizeClass }} shrink-0 text-green-400 transition-colors duration-300 group-hover:text-green-300">
            {{ $icon }}
        </div>
        <div data-spark data-angle="-45" class="pointer-events-none absolute -top-1 -right-1 -rotate-45 opacity-0" style="translate: calc(var(--spark-distance, 0px) * 0.707) calc(var(--spark-distance, 0px) * -0.707)">
            <div class="h-0.5 w-1.5 rounded-full bg-green-400"></div>
        </div>
        <div data-spark data-angle="45" class="pointer-events-none absolute -right-1 -bottom-1 rotate-45 opacity-0" style="translate: calc(var(--spark-distance, 0px) * 0.707) calc(var(--spark-distance, 0px) * 0.707)">
            <div class="h-0.5 w-1.5 rounded-full bg-green-400"></div>
        </div>
        <div data-spark data-angle="-135" class="pointer-events-none absolute -top-1 -left-1 rotate-45 opacity-0" style="translate: calc(var(--spark-distance, 0px) * -0.707) calc(var(--spark-distance, 0px) * -0.707)">
            <div class="h-0.5 w-1.5 rounded-full bg-green-400"></div>
        </div>
        <div data-spark data-angle="135" class="pointer-events-none absolute -bottom-1 -left-1 -rotate-45 opacity-0" style="translate: calc(var(--spark-distance, 0px) * -0.707) calc(var(--spark-distance, 0px) * 0.707)">
            <div class="h-0.5 w-1.5 rounded-full bg-green-400"></div>
        </div>
    </div>

    @if ($hasText)
        <div class="text-sm leading-5 font-semibold text-green-400 transition-colors duration-300 group-hover:text-green-300">
            {{ $text }}
        </div>
    @endif
</a>
