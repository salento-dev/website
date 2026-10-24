@props([
    'eyebrow' => null,
    'title' => null,
    'description' => null,
    'link' => null,
    'info' => null,
    'tag' => null,
    'bordered' => false,
    'size' => 'default'
])

<section class="{{ $bordered ? 'border-y border-border' : '' }}">
    <div class="mb-8">
        <div class="flex items-end justify-between flex-wrap lg:flex-nowrap gap-6">
            <div class="flex flex-col gap-3">
                @if ($eyebrow)
                    <{{ $title ? 'p' : 'h2' }} class="text-xs font-semibold uppercase tracking-wider text-primary m-0">{{ $eyebrow }}</{{ $title ? 'p' : 'h2' }}>
                @endif
                @if ($title)
                    <h2 class="{{ $size == 'small' ? 'text-2xl md:text-3xl' : 'text-4xl md:text-5xl'}} font-bold text-surface-50">
                        {!! $title !!}
                    </h2>
                @endif
            </div>
            @if ($info)
                <div class="text-sm font-medium text-surface-400">
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
            @if ($tag)
                <span
                    class="rounded-xs border border-border bg-surface-800 px-3 py-1 font-mono text-2xs uppercase tracking-widest text-primary">
                    {{ $tag }}
                </span>
            @endif
        </div>

        @if ($description)
            <p class="mt-3 text-surface-400 leading-relaxed">
                {{ $description }}
            </p>
        @endif
    </div>

    {{ $slot }}
</section>
