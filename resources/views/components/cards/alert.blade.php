@props([
    'icon',
    'title' => null,
    'description' => false,
])

<div {{ $attributes->class(['mt-6 bg-surface-800 p-6']) }}>
    <div class="flex items-start gap-4">
        @svg('heroicon-' . $icon, 'size-5 text-primary shrink-0 mt-0.5')
        <div>
            @if ($title)
                <h5 class="text-sm font-semibold text-mist-100 mb-2">{{ $title }}</h5>
            @endif
            @if ($description)
                <p class="text-sm text-mist-300 leading-relaxed">
                    {{ $description }}
                </p>
            @endif
        </div>
    </div>
</div>
