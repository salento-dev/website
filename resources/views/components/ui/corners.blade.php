@props([
    'hover' => false,
    'color' => 'text-surface-400',
    'size' => 'h-2.5',
    'offset' => 8,
])

@if ($hover)
    <div
        x-data
        x-init="() => {
            const tl = gsap.timeline({ paused: true })
            tl.to($refs.topLeftCorner,     { x: -{{ $offset }}, y: -{{ $offset }}, duration: 0.25, ease: 'power2.out' }, 0)
            tl.to($refs.topRightCorner,    { x:  {{ $offset }}, y: -{{ $offset }}, duration: 0.25, ease: 'power2.out' }, 0)
            tl.to($refs.bottomLeftCorner,  { x: -{{ $offset }}, y:  {{ $offset }}, duration: 0.25, ease: 'power2.out' }, 0)
            tl.to($refs.bottomRightCorner, { x:  {{ $offset }}, y:  {{ $offset }}, duration: 0.25, ease: 'power2.out' }, 0)

            const target = $el.closest('.group') ?? $el.parentElement
            motion.hover(target, () => {
                tl.tweenTo(tl.duration())
                return () => tl.tweenTo(0)
            })
        }"
        style="display: contents"
        aria-hidden="true"
    >
@endif

{{-- Top left --}}
<div
    @if ($hover) x-ref="topLeftCorner" @endif
    class="absolute top-0 left-0"
    aria-hidden="true"
>
    <svg class="{{ $size }} {{ $color }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 11 11" fill="none">
        <path d="M9.5 0.5H0.5V9.5" class="stroke-current" stroke-linecap="round"></path>
    </svg>
</div>

{{-- Bottom left --}}
<div
    @if ($hover) x-ref="bottomLeftCorner" @endif
    class="absolute bottom-0 left-0"
    aria-hidden="true"
>
    <svg class="{{ $size }} -scale-y-100 {{ $color }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 11 11" fill="none">
        <path d="M9.5 0.5H0.5V9.5" class="stroke-current" stroke-linecap="round"></path>
    </svg>
</div>

{{-- Top right --}}
<div
    @if ($hover) x-ref="topRightCorner" @endif
    class="absolute top-0 right-0"
    aria-hidden="true"
>
    <svg class="{{ $size }} -scale-x-100 {{ $color }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 11 11" fill="none">
        <path d="M9.5 0.5H0.5V9.5" class="stroke-current" stroke-linecap="round"></path>
    </svg>
</div>

{{-- Bottom right --}}
<div
    @if ($hover) x-ref="bottomRightCorner" @endif
    class="absolute right-0 bottom-0"
    aria-hidden="true"
>
    <svg class="{{ $size }} -scale-x-100 -scale-y-100 {{ $color }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 11 11" fill="none">
        <path d="M9.5 0.5H0.5V9.5" class="stroke-current" stroke-linecap="round"></path>
    </svg>
</div>

@if ($hover)
    </div>
@endif
