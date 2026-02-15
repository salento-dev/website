<header class="sticky top-0 z-40 border-b border-primary/30 bg-background-dark/80 px-6 py-4 backdrop-blur-md lg:px-20">
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-2 text-primary">
                <span class="material-symbols-outlined text-3xl">terminal</span>
                <h2 class="text-xl font-bold tracking-tighter uppercase">SALENTO_DEV_OS_v1.0</h2>
            </div>

            <nav class="hidden items-center gap-6 md:flex">
                <a class="border-b border-transparent pb-1 text-xs font-bold tracking-[0.2em] text-primary/70 transition-colors hover:border-primary hover:text-primary" href="#mission">
                    /MISSION
                </a>
                <a class="border-b border-transparent pb-1 text-xs font-bold tracking-[0.2em] text-primary/70 transition-colors hover:border-primary hover:text-primary" href="#eventi">
                    /EVENTS
                </a>
                <a class="border-b border-transparent pb-1 text-xs font-bold tracking-[0.2em] text-primary/70 transition-colors hover:border-primary hover:text-primary" href="#documenti">
                    /DOCS
                </a>
            </nav>
        </div>

        <div class="flex items-center gap-3">
            <a
                href="{{ route('about') }}"
                class="flex size-9 items-center justify-center border border-primary/40 text-primary transition-all hover:bg-primary hover:text-background-dark"
                aria-label="Chi siamo"
            >
                <span class="material-symbols-outlined text-xl">groups</span>
            </a>
            <a
                href="{{ route('contacts') }}"
                class="flex size-9 items-center justify-center border border-primary/40 text-primary transition-all hover:bg-primary hover:text-background-dark"
                aria-label="Contatti"
            >
                <span class="material-symbols-outlined text-xl">alternate_email</span>
            </a>
        </div>
    </div>

    <div class="mt-2 flex items-center gap-4">
        <div class="flex items-center gap-1">
            <div class="size-1.5 rounded-full bg-primary animate-pulse"></div>
            <span class="text-[10px] tracking-widest text-primary/50 uppercase">SYS_ACTIVE</span>
        </div>
        <div class="text-[10px] tracking-widest text-primary/50 uppercase">LOC: SALENTO_NODE_01</div>
        <div class="ml-auto text-[10px] tracking-widest text-primary/50 uppercase">COMMUNITY_MODE: ON</div>
    </div>
</header>