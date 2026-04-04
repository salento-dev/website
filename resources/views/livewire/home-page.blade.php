@section('hero')
    <x-sections.home-hero />
@endsection

@section('hero-footer')
    <x-partials.skills-loop />
@endsection

<div class="flex flex-col gap-24 md:gap-32 lg:gap-40">

    <!-- Upcoming Event -->
    @if ($nextEvent || $pastEvents->isNotEmpty())
        <div class="flex flex-col gap-12">
            @if ($nextEvent)
            <x-cards.event-featured :event="$nextEvent" />
            @endif

            @if ($pastEvents->isNotEmpty())
                <x-ui.section-header eyebrow="Eventi passati" :link="['href' => route('events'), 'text' => 'Full Schedule']">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach ($pastEvents as $event)
                            <x-cards.event :event="$event" :variant="'simple'" />
                        @endforeach
                    </div>
                </x-ui.section-header>
            @endif
        </div>
    @endif



    <!-- Documents Feed / Resources -->
    <x-ui.section-header eyebrow="FETCHING_RESOURCES" title="Le ultime risorse" :link="['href' => route('resources'), 'text' => 'View All Resources']">
        <x-ui.terminal :path="'~/resources/latest'">
            <div class="divide-y divide-border-cyan/50 font-mono text-xs">
                @forelse ($resources as $resource)
                    <div class="group flex items-center justify-between px-6 py-4 hover:bg-primary/5 transition-colors">
                        <div class="flex items-center gap-4">
                            <x-heroicon-o-document-text class="size-4 text-primary" />
                            <span class="text-white">{{ $resource->title }}</span>
                            <span class="hidden sm:inline text-white/20">|</span>
                            <span class="hidden md:inline text-white/20">Uploaded:
                                {{ $resource->created_at->diffForHumans() }}</span>
                        </div>
                        @if ($resource->file_path)
                            <a class="text-primary hover:underline uppercase tracking-widest text-[10px] font-bold"
                                href="{{ asset('storage/' . ltrim($resource->file_path, '/')) }}" download>Download</a>
                        @endif
                    </div>
                @empty
                    <p class="p-4 text-sm text-primary/60">Nessuna risorsa pubblicata al momento.</p>
                @endforelse
            </div>
        </x-ui.terminal>
    </x-ui.section-header>

    <x-sections.principles />

    <x-sections.activities />

    <x-sections.scopes />

    <x-sections.code-of-conduct />
</div>
