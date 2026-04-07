@props([
    'name',
])

<span {{ $attributes->class(['inline-flex h-6 items-center rounded-xs bg-surface-800 px-2 text-[10px] font-medium uppercase tracking-wider text-surface-200/70']) }}>
    {{ $name }}
</span>
