@props([
    'badge' => null,
    'title' => null,
    'description' => false,
])

@if ($badge)
    <div class="mb-6 inline-block rounded border border-primary/20 bg-primary/5 px-4 py-1 text-2xs font-bold uppercase tracking-[0.3em] text-primary">
        {{ $badge }}
    </div>
@endif

    <h1 class="mb-6 text-5xl font-black uppercase leading-tight tracking-tighter text-white sm:text-6xl lg:text-7xl">
        {{ $slot }}
    </h1>

@if ($description)
    <p class="mx-auto max-w-2xl text-lg font-light leading-relaxed text-white/60">
        {{ $description }}
    </p>
@endif
