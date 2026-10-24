{{--
    Ordinamento delle liste: select-only combobox (pattern WAI-ARIA APG). Il focus resta sul bottone,
    l'opzione attiva è indicata con aria-activedescendant. Tastiera: frecce, Home/End, Invio/Spazio, Esc, Tab.
--}}
@props([
    'options',
    'currentValue',
    'wireProperty' => 'sortBy',
    'label' => 'Ordina:',
])

@php
    $selectId = 'sort-select-' . \Illuminate\Support\Str::slug($wireProperty);
@endphp

<div class="flex items-center gap-1 text-xs">
    <span id="{{ $selectId }}-label" class="sr-only md:not-sr-only">{{ $label }}</span>
    <div class="relative"
        x-data="{
            open: false,
            activeIndex: 0,
            usingKeyboard: false,
            values: @js(array_map('strval', array_keys($options))),
            labels: @js($options),
            selectedIndex() {
                return Math.max(0, this.values.indexOf(String($wire.{{ $wireProperty }})))
            },
            show(index = null, usingKeyboard = true) {
                this.usingKeyboard = usingKeyboard
                this.activeIndex = index ?? this.selectedIndex()
                this.open = true
            },
            hide() {
                this.open = false
            },
            move(step) {
                this.usingKeyboard = true
                if (! this.open) {
                    return this.show()
                }
                this.activeIndex = Math.min(this.values.length - 1, Math.max(0, this.activeIndex + step))
            },
            choose(index) {
                if ($wire.{{ $wireProperty }} !== this.values[index]) {
                    $wire.set('{{ $wireProperty }}', this.values[index])
                }
                this.hide()
                $refs.trigger.focus()
            },
            confirm() {
                this.open ? this.choose(this.activeIndex) : this.show()
            },
        }"
        x-on:click.outside="hide()" x-on:keydown.escape.window="hide()">
        <button id="{{ $selectId }}" x-ref="trigger" type="button" role="combobox"
            aria-haspopup="listbox" aria-controls="{{ $selectId }}-listbox" aria-expanded="false"
            aria-labelledby="{{ $selectId }}-label {{ $selectId }}"
            x-bind:aria-expanded="open"
            x-bind:aria-activedescendant="open ? '{{ $selectId }}-option-' + activeIndex : null"
            x-on:click="open ? hide() : show(null, false)"
            x-on:keydown.arrow-down.prevent="$event.altKey ? show() : move(1)"
            x-on:keydown.arrow-up.prevent="move(-1)"
            x-on:keydown.home.prevent="show(0)"
            x-on:keydown.end.prevent="show(values.length - 1)"
            x-on:keydown.enter.prevent="confirm()"
            x-on:keydown.space.prevent="confirm()"
            x-on:keyup.space.prevent
            x-on:keydown.tab="hide()"
            class="btn btn-sm group">
            <span x-text="labels[$wire.{{ $wireProperty }}]">{{ $options[$currentValue] }}</span>
            <div class="transition-transform duration-300 ease-out" :class="open && 'rotate-180'"
                aria-hidden="true">
                <x-heroicon-o-chevron-down class="size-3.5" />
            </div>
        </button>

        <ul id="{{ $selectId }}-listbox" x-show="open" x-transition:enter="transition duration-300 ease-out"
            x-transition:enter-start="-translate-y-1 opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transition duration-150 ease-in"
            x-transition:leave-start="translate-y-0 opacity-100"
            x-transition:leave-end="-translate-y-1 opacity-0"
            class="absolute top-full left-0 z-20 mt-1 min-w-35 overflow-hidden rounded-xs border border-border bg-surface-950"
            role="listbox" tabindex="-1" aria-labelledby="{{ $selectId }}-label" style="display: none;">
            @foreach ($options as $value => $optionLabel)
                <li id="{{ $selectId }}-option-{{ $loop->index }}" role="option"
                    :aria-selected="$wire.{{ $wireProperty }} === '{{ $value }}' ? 'true' : 'false'"
                    x-on:click="choose({{ $loop->index }})"
                    class="flex w-full cursor-pointer items-center gap-2 px-3 py-2 text-left transition-colors duration-150 hover:bg-surface-900/70"
                    :class="{
                        'ring-1 ring-inset ring-primary/50': usingKeyboard && activeIndex === {{ $loop->index }},
                        'bg-surface-900 font-medium text-surface-200': $wire.{{ $wireProperty }} === '{{ $value }}',
                    }">
                    <span class="size-3.5"
                        :class="$wire.{{ $wireProperty }} === '{{ $value }}' ? 'opacity-100' : 'opacity-0'">
                        <x-heroicon-o-check class="size-3.5" />
                    </span>
                    {{ $optionLabel }}
                </li>
            @endforeach
        </ul>
    </div>
</div>
