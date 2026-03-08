@props([
    'route' => null,
    'routeName' => null,
])

@php $isActive = request()->routeIs($route) @endphp
<a href="{{ route($route) }}" aria-current="{{ $isActive ? 'page' : null }}" x-on:click="showMobileMenu = false"
    class="block py-2 transition duration-300 ease-out {{ $isActive ? 'font-medium text-mist-200' : 'hover:scale-110 hover:font-medium hover:text-mist-200'  }}">
    <span class="relative inline-flex items-center gap-1.5">
        {{ $routeName }}
        @if ($isActive)
            <div class="pointer-events-none absolute -right-23.5 bottom-2.25 inline-flex flex-col gap-2" data-badge=""
                style="opacity: 0; transform: scale(0)">
                <div x-init="$nextTick(() => initBadgeAnimation($el, 'rotate'))">
                    <div
                        class="ml-2.5 -rotate-5 rounded-tl-md rounded-tr-md rounded-br-md bg-surface-800 px-2 py-1 text-xs whitespace-nowrap text-mist-100">
                        You're here
                    </div>
                </div>
                <div x-init="$nextTick(() => initBadgeAnimation($el, 'simple'))">
                    <svg class="size-2 text-honey-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 8 8"
                        fill="none">
                        <path d="M4 0L8 4L4 8L0 4L4 0Z" class="fill-current">
                        </path>
                    </svg>
                </div>
            </div>
        @endif
    </span>
</a>
