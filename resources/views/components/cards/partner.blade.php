@props(['partner'])

<div class="relative group border border-border p-5 flex flex-col h-full duration-300">
    <div class="mb-5 flex items-start justify-between">
        <div class="h-24 w-32 bg-surface-800 flex items-center justify-center border border-border/20 rounded-sm overflow-hidden">
            @if(!empty($partner->logo_path)&& Storage::disk('public')->exists($partner->logo_path))
            <img class="w-full h-full object-cover opacity-60 grayscale group-hover:grayscale-0 group-hover:opacity-100 transition-all"
                src="{{ Storage::disk('public')->url($partner->logo_path) }}" 
                alt="{{ $partner->name }}" />
            @else
            <x-heroicon-o-cpu-chip class="size-8 opacity-60 grayscale group-hover:grayscale-0 group-hover:opacity-100 transition-all" />
            @endif
        </div>
        {{-- <span class="font-label text-[10px] uppercase tracking-widest px-3 py-1 bg-stone-800 text-stone-300 border border-stone-700">Venue</span> --}}
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
    <div class="pt-5 border-t border-border">
        <a class="flex items-center gap-2 font-label text-sm uppercase tracking-tighter text-surface-500 hover:text-primary transition-colors"
            href="{{ $partner->website_url }}"
            target="_blank" rel="noopener">
            Visit Website
            <x-heroicon-o-arrow-top-right-on-square class="size-4" />
        </a>
    </div>
</div>
