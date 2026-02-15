<footer class="relative mt-20 overflow-hidden border-t border-primary/20 bg-cyber-card px-6 py-12 lg:px-20">
    <div class="mx-auto flex max-w-7xl flex-col justify-between gap-12 md:flex-row">
        <div class="space-y-4">
            <div class="flex items-center gap-2 text-primary">
                <span class="material-symbols-outlined text-2xl">terminal</span>
                <h2 class="text-lg font-bold tracking-tighter uppercase">SALENTO_DEV_COMMUNITY</h2>
            </div>
            <p class="max-w-xs text-xs leading-relaxed tracking-wider text-primary/50 uppercase">
                Building digital sovereignty in the Salento Peninsula. Open Source. Decentralized. High-Performance.
            </p>
        </div>

        <div class="grid grid-cols-2 gap-8 sm:grid-cols-3">
            <div class="space-y-4">
                <h4 class="text-xs font-black tracking-widest text-primary uppercase">/Resources</h4>
                <ul class="space-y-2 text-[10px] tracking-widest text-primary/50 uppercase">
                    <li><a class="hover:text-primary" href="{{ route('documents-slides') }}">Documentazione</a></li>
                    <li><a class="hover:text-primary" href="{{ route('events') }}">Eventi</a></li>
                </ul>
            </div>
            <div class="space-y-4">
                <h4 class="text-xs font-black tracking-widest text-primary uppercase">/Community</h4>
                <ul class="space-y-2 text-[10px] tracking-widest text-primary/50 uppercase">
                    <li><a class="hover:text-primary" href="{{ route('about') }}">Chi siamo</a></li>
                    <li><a class="hover:text-primary" href="{{ route('contacts') }}">Contatti</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>