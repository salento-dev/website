@props(['partner'])

<div class="relative border border-border p-5 flex flex-col items-center text-center h-full duration-300">
    <div class="w-full h-32 flex items-center justify-center mb-5 overflow-hidden">
        @if($partner->logo_url)
        <img class="max-w-full max-h-full w-auto h-auto object-contain"
            src="{{ $partner->logo_url }}"
            alt="{{ $partner->name }}" />
        @else
        <x-heroicon-o-cpu-chip class="size-10" />
        @endif
    </div>
    @if($partner->name)
    <h3 class="font-headline text-xl font-bold text-white mb-2 transition-colors">
        {{ $partner->name }}
    </h3>
    @endif
    @if($partner->description)
    <p class="text-surface-300 text-sm leading-relaxed mb-5 flex-grow">
        {{ $partner->description }}
    </p>
    @endif
    <div class="mt-auto pt-5 border-t border-border w-full flex justify-center">
        <a class="inline-flex items-center gap-2 font-label text-sm uppercase tracking-tighter text-surface-500 hover:text-primary transition-colors"
            href="{{ $partner->website_url }}"
            target="_blank" rel="noopener">
            Visit Website
            <x-heroicon-o-arrow-top-right-on-square class="size-4" />
        </a>
    </div>
</div>
