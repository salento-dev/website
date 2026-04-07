@section('hero')
    <x-sections.home-hero />
@endsection

@section('hero-footer')
    <x-partials.skills-loop />
@endsection

<div class="flex flex-col gap-20">

    <!-- Upcoming Event -->
    @if ($nextEvent || $pastEvents->isNotEmpty())
        <div class="flex flex-col gap-12">
            @if ($nextEvent)
            <x-events.featured-event :event="$nextEvent" />
            @endif

            @if ($pastEvents->isNotEmpty())
                <x-ui.section-header eyebrow="Eventi passati" :link="['href' => route('events'), 'text' => 'Full Schedule']">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach ($pastEvents as $event)
                            <x-events.event :event="$event" :variant="'simple'" />
                        @endforeach
                    </div>
                </x-ui.section-header>
            @endif
        </div>
    @endif



    <!-- Documents Feed / Resources -->
    <x-ui.section-header eyebrow="FETCHING_RESOURCES" title="Le ultime risorse" :link="['href' => route('resources'), 'text' => 'View All Resources']">
        <x-ui.terminal :path="'~/resources/latest'">
            <div class="divide-y divide-border/50">
                @forelse ($resources as $resource)
                    <x-cards.resource-row :resource="$resource" />
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
