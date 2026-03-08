@props([
    'href' => '#',
    'text' => '',
    'ariaLabel' => '',
    'icon' => true,
    'variant' => 'default',
    'width' => 'auto',
])

@php
    $baseClasses = 'group inline-flex items-center justify-between rounded-full font-medium whitespace-nowrap text-mist-900 transition duration-300 ease-out will-change-transform hover:scale-y-102 focus:outline-none focus-visible:ring focus-visible:ring-black/50 focus-visible:ring-inset h-13 gap-2.5';
    
    $variantClasses = match($variant) {
        'primary' => 'bg-primary-100/20 hover:bg-primary-200',
        'secondary' => 'bg-surface hover:bg-cream-100',
        default => 'bg-cream-100 hover:bg-honey-100',
    };
    
    $paddingClasses = $icon ? 'pr-1.25 pl-5' : 'px-5';
    
    $widthClasses = match($width) {
        'full' => 'w-full',
        'auto' => 'w-full xs:w-40',
        default => $width,
    };
    
    $finalText = $text ?: $slot;
    $finalAriaLabel = $ariaLabel ?: $finalText;
    
    $animationId = 'btn_' . uniqid();
@endphp

<div x-data="" data-button-pulse="" class="rounded-full {{ $variantClasses }}" aria-label="{{ $finalAriaLabel }}" x-init="$nextTick(() => buttonAnimation{{ $animationId }}($el))">
    <a 
        href="{{ $href }}" 
        aria-label="{{ $finalAriaLabel }}"
        {{ $attributes->merge(['class' => "$baseClasses $variantClasses $paddingClasses $widthClasses"]) }}
    >
        <span data-text="" class="grow">
            {{ $finalText }}
        </span>

        @if($icon)
            <div data-icon="" class="relative isolate grid shrink-0 place-items-center overflow-hidden rounded-full size-11 bg-primary-50 text-mist-900">
                <x-heroicon-o-arrow-right class="h-3.25" />
            </div>
        @endif
    </a>
</div>

<script>
window.buttonAnimation{{ $animationId }} = function(el) {
    const button = el.querySelector('a');
    const textWrapper = button.querySelector('[data-text]');
    const icon = button.querySelector('[data-icon] svg');
    
    if (!button || !gsap || !motion) return;
    
    const tl = gsap.timeline({ paused: true });
    
    // Animate text characters
    let chars = [];
    if (textWrapper && window.SplitText) {
        const split = new SplitText(textWrapper, { type: 'chars' });
        chars = split.chars;
    }
    
    // Arrow animation
    if (icon) {
        const distance = 30;
        tl.to(icon, {
            keyframes: [
                { x: distance, y: 0, duration: 0.23, ease: 'power2.in' },
                { x: -distance, y: 0, duration: 0 },
                { x: 0, y: 0, duration: 0.23, ease: 'power2.out' },
            ],
        }, 0);
    }
    
    // Characters shine with stagger
    if (chars.length > 0) {
        tl.to(chars, {
            keyframes: {
                opacity: [1, 0.4, 1],
            },
            duration: 0.15,
            ease: 'sine.inOut',
            stagger: 0.02,
        }, 0.1);
    }
    
    motion.hover(button, () => {
        tl.tweenTo(tl.duration());
        return () => {
            tl.tweenTo(0);
        };
    });
}
</script>
