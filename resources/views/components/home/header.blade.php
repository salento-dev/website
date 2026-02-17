<header class="sticky top-0 z-50 w-full border-b border-border-cyan bg-background-dark/95 backdrop-blur-md px-6 py-4 lg:px-20">
    <div class="container">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-10">
                <a href="{{ route('home') }}">
                    <x-logo animated />
                </a>
                <nav class="hidden md:flex items-center gap-8">
                    <a class="text-sm font-medium uppercase tracking-widest {{ request()->routeIs('events') ? 'text-primary' : 'text-white/70' }} hover:text-primary transition-colors" href="{{ route('events') }}">Events</a>
                    <a class="text-sm font-medium uppercase tracking-widest {{ request()->routeIs('resources') ? 'text-primary' : 'text-white/70' }} hover:text-primary transition-colors" href="{{ route('resources') }}">Resources</a>
                </nav>
            </div>
            <a href="https://chat.whatsapp.com/Hj9JyfVHUOE2g7dqoql1OB" target="_blank" rel="noopener noreferrer" class="group relative flex h-10 items-center justify-center gap-2 overflow-hidden rounded border border-primary bg-primary/10 px-3 md:px-6 text-xs font-bold uppercase tracking-widest text-primary shadow-neon hover:bg-primary hover:text-background-dark transition-all">
                <span class="material-symbols-outlined text-lg">forum</span>
                <span class="relative z-10 hidden md:inline">Join WhatsApp</span>
            </a>
        </div>
        <nav class="flex md:hidden items-center justify-center gap-6 mt-4 pt-4 border-t border-border-cyan/50">
            <a class="text-sm font-medium uppercase tracking-widest {{ request()->routeIs('events') ? 'text-primary' : 'text-white/70' }} hover:text-primary transition-colors" href="{{ route('events') }}">Events</a>
            <a class="text-sm font-medium uppercase tracking-widest {{ request()->routeIs('resources') ? 'text-primary' : 'text-white/70' }} hover:text-primary transition-colors" href="{{ route('resources') }}">Resources</a>
        </nav>
    </div>
</header>