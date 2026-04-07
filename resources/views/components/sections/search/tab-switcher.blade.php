@props([
    'legend',
    'legendId',
    'name',
    'model',
    'options' => [],
    'idPrefix' => null,
])

@php
    $resolvedIdPrefix = $idPrefix ?? $name;
@endphp

<fieldset class="flex flex-col gap-2 sm:flex-row sm:items-center">
    <legend class="float-left text-sm" id="{{ $legendId }}">{{ $legend }}</legend>

    <div
        class="tab-switcher relative grid auto-cols-fr grid-flow-col rounded-xs bg-surface-100 has-[input:nth-of-type(2)]:[--count:2] has-[input:nth-of-type(3)]:[--count:3] has-[input:nth-of-type(4)]:[--count:4] has-[input:nth-of-type(5)]:[--count:5] has-[input:nth-of-type(6)]:[--count:6] has-[input:nth-of-type(7)]:[--count:7] has-[input:nth-of-type(8)]:[--count:8] has-[:checked:nth-of-type(1)]:[--active:0] has-[:checked:nth-of-type(2)]:[--active:1] has-[:checked:nth-of-type(3)]:[--active:2] has-[:checked:nth-of-type(4)]:[--active:3] has-[:checked:nth-of-type(5)]:[--active:4] has-[:checked:nth-of-type(6)]:[--active:5] has-[:checked:nth-of-type(7)]:[--active:6] has-[:checked:nth-of-type(8)]:[--active:7]"
        aria-labelledby="{{ $legendId }}"
        role="radiogroup"
    >
        <div
            aria-hidden="true"
            class="pointer-events-none absolute inset-y-0 left-0 w-[calc(100%/var(--count,1))] translate-x-[calc(var(--active,0)*100%)] rounded-xs bg-primary-400 transition-all duration-400 ease-out"
        ></div>

        @foreach ($options as $index => $option)
            @php
                $optionValue = (string) ($option['value'] ?? '');
                $optionLabel = (string) ($option['label'] ?? $optionValue);
                $escapedValue = str_replace("'", "\\'", $optionValue);
                $inputId = $resolvedIdPrefix.'-'.$index;
            @endphp

            <input
                type="radio"
                id="{{ $inputId }}"
                name="{{ $name }}"
                value="{{ $optionValue }}"
                class="peer absolute size-full cursor-pointer opacity-0"
                x-model="{{ $model }}"
            >

            <label
                for="{{ $inputId }}"
                :class="{ 'font-medium text-surface-800': {{ $model }} === '{{ $escapedValue }}', 'hover:bg-primary-400/50 hover:font-medium hover:text-surface-800': {{ $model }} !== '{{ $escapedValue }}' }"
                class="relative z-10 flex h-9 cursor-pointer items-center justify-center px-3 text-sm transition-all duration-400 ease-out select-none peer-focus-visible:ring peer-focus-visible:ring-black/50 peer-focus-visible:ring-inset first:rounded-l-xs last:rounded-r-xs"
            >
                @if (! $loop->last)
                    <svg
                        aria-hidden="true"
                        class="pointer-events-none absolute top-1/2 right-0 h-full w-px -translate-y-1/2 text-surface-300"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 1 16"
                        fill="currentColor"
                        preserveAspectRatio="none"
                    >
                        <rect x="0" y="0" width="1" height="2.5" rx="0.5"></rect>
                        <rect x="0" y="4" width="1" height="2.5" rx="0.5"></rect>
                        <rect x="0" y="9" width="1" height="2.5" rx="0.5"></rect>
                        <rect x="0" y="14" width="1" height="2.5" rx="0.5"></rect>
                    </svg>
                @endif

                <span class="sr-only">{{ $optionLabel }}</span>

                <span class="flex items-center gap-1.5" aria-hidden="true">
                    <span class="grid [grid-template-areas:'text']">
                        <span class="[grid-area:text]">{{ $optionLabel }}</span>
                        <span class="invisible font-medium select-none [grid-area:text]">{{ $optionLabel }}</span>
                    </span>
                </span>
            </label>
        @endforeach
    </div>
</fieldset>
