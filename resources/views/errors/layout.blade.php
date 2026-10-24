{{--
    Guscio delle pagine di errore: riusa layouts.app (lo stesso dei componenti Livewire) senza query al DB.
    Le pagine figlie definiscono code, title, message e, se serve, actions e panel.
--}}
@section('hero')
    <p class="mb-6 font-mono text-2xs uppercase tracking-widest text-surface-400">
        Status: <span class="text-primary">ERR_{{ $__env->yieldContent('code') }}</span>
    </p>

    <h1 class="mb-6 text-4xl font-black uppercase leading-tight tracking-tighter text-balance text-surface-50 sm:text-5xl lg:text-6xl">
        @yield('title')
    </h1>

    <p class="mx-auto max-w-2xl text-lg leading-relaxed text-pretty text-surface-400">
        @yield('message')
    </p>

    <div class="mt-10 flex flex-wrap items-center justify-center gap-3">
        @section('actions')
            <x-ui.button href="{{ route('events') }}" text="Vedi gli eventi" width="w-56" />
            <a href="{{ route('home') }}" class="btn">
                Torna alla home
            </a>
        @show
    </div>
@endsection

@component('layouts.app', [
    'pageTitle' => trim($__env->yieldContent('title')),
    'metaDescription' => trim($__env->yieldContent('message')),
])
    @section('panel')
        <x-events.no-upcoming
            telemetry-label="Channel"
            telemetry-value="WhatsApp"
            title="Resta in contatto con la community"
            description="Eventi e novità vengono annunciati prima di tutto nel gruppo WhatsApp della community."
        />
    @show
@endcomponent
