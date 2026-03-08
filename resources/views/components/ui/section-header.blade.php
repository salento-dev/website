@props(['eyebrow', 'title', 'description' => null, 'link' => null, 'info' => null, 'bordered' => false])

<section class="{{ $bordered ? 'border-y border-border' : '' }}">
    <div class="mb-12">
        <div class="flex items-end justify-between flex-wrap lg:flex-nowrap gap-6">
            <div>
                <h2 class="text-xs font-semibold uppercase tracking-wider text-primary mb-3">{{ $eyebrow }}</h2>
                <h3 class="text-4xl md:text-5xl font-bold text-stone-50">
                    {!! $title !!}
                </h3>
            </div>
            @if ($info)
                <div class="text-sm font-medium text-stone-400">
                    {{ $info }}
                </div>
            @endif
            @if ($link)
                <a class="inline-flex items-center gap-2 text-sm font-semibold text-primary hover:text-primary-dark transition-colors duration-200"
                    href="{{ $link['href'] }}">
                    {{ $link['text'] }}
                    <x-heroicon-o-chevron-right class="h-4 w-4" />
                </a>
            @endif
        </div>

        @if ($description)
            <p class="mt-3 text-text-secondary leading-relaxed max-w-3xl">
                {{ $description }}
            </p>
        @endif
    </div>

    {{ $slot }}
</section>
