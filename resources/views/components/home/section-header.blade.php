@props([
    'eyebrow',
    'title',
    'description' => null,
    'link' => null,
    'bordered' => false,
])

<section class="{{ ($bordered) ? 'bg-primary/5 border-y border-border-cyan' : '' }}">
    <div class="container py-24 lg:py-32">
        <div class="mb-12">
            <div class="flex items-end justify-between flex-wrap lg:flex-nowrap gap-4">
                <div>
                    <h2 class="text-[10px] font-bold uppercase tracking-[0.5em] text-primary mb-4">{{ $eyebrow }}</h2>
                    <h3 class="text-4xl font-bold uppercase tracking-tighter text-white">
                        {!! $title !!}<span class="animate-pulse text-primary">_</span>
                    </h3>
                </div>
                @if($link)
                <a class="text-xs font-bold uppercase tracking-widest text-primary hover:underline" href="{{ $link['href'] }}">{{ $link['text'] }} →</a>
                @endif
            </div>
            
            @if($description)
                <p class="mt-6 text-white/50 leading-relaxed">
                    {{ $description }}
                </p>
            @endif
        </div>

        {{ $slot }}
    </div>
</section>
