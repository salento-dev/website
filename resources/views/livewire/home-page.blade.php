<div class="relative flex min-h-screen flex-col">
    <x-home.header />

    <main class="flex-1">
        <x-home.hero />

        <!-- Upcoming Events -->
        @if($events->isNotEmpty())
            <x-home.section-header 
                eyebrow="SCHEDULED_OPERATIONS" 
                title="Upcoming Events"
                :link="['href' => route('events'), 'text' => 'Full Schedule']">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach ($events as $event)
                    <x-home.event-card-preview :event="$event" />
                    @endforeach
                </div>
            </x-home.section-header>
        @endif

        <!-- Documents Feed / Resources -->
        <x-home.section-header 
            eyebrow="FETCHING_RESOURCES" 
            title="Latest Documents"
            :link="['href' => route('resources'), 'text' => 'View All Resources']"
            bordered>
            <div class="overflow-hidden border border-border-cyan bg-background-dark">
                <!-- Header -->
                <div class="flex items-center justify-between border-b border-border-cyan bg-card-dark px-6 py-3">
                    <div class="flex gap-2">
                        <div class="size-2 rounded-full bg-red-500/50"></div>
                        <div class="size-2 rounded-full bg-yellow-500/50"></div>
                        <div class="size-2 rounded-full bg-green-500/50"></div>
                    </div>
                    <span class="text-[10px] font-mono text-primary/60 tracking-widest uppercase">root@salentodev:~/resources</span>
                </div>
                <!-- Terminal Body -->
                <div class="divide-y divide-border-cyan/50 font-mono text-xs">
                    @forelse ($resources as $resource)
                        <div class="group flex items-center justify-between px-6 py-4 hover:bg-primary/5 transition-colors">
                            <div class="flex items-center gap-4">
                                <span class="material-symbols-outlined text-primary">description</span>
                                <span class="text-white">{{ $resource->title }}</span>
                                <span class="hidden sm:inline text-white/20">|</span>
                                <span class="hidden md:inline text-white/20">Uploaded: {{ $resource->created_at->diffForHumans() }}</span>
                            </div>
                            @if ($resource->file_path)
                                <a class="text-primary hover:underline uppercase tracking-widest text-[10px] font-bold" href="{{ asset('storage/' . ltrim($resource->file_path, '/')) }}" download>Download</a>
                            @endif
                        </div>
                    @empty
                        <p class="p-4 text-sm text-primary/60">Nessun documento pubblicato al momento.</p>
                    @endforelse
                </div>
            </div>
        </x-home.section-header>

        <x-home.principles />

        <x-home.activities />

        <x-home.scopes />

        <x-home.code-of-conduct />
    </main>

    <x-home.footer />
</div>