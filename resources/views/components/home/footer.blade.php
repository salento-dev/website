<footer class="border-t border-border-cyan bg-background-dark px-6 py-16 lg:px-20">
    <div class="container">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
            <div class="col-span-1 md:col-span-2">
                <x-logo class="mb-6" />
                <p class="max-w-md text-sm text-white/40 leading-relaxed mb-8">
                    Join the technological revolution in Southern Italy. Our community is open to developers, designers, and tech enthusiasts who believe in building the future together.
                </p>
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-[0.2em] text-white mb-6">Protocols</h4>
                <ul class="space-y-3 text-sm text-white/40">
                    <li><a class="hover:text-primary transition-colors" href="{{ route('events') }}">Events Schedule</a></li>
                    <li><a class="hover:text-primary transition-colors" href="{{ route('resources') }}">Resource Library</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-[0.2em] text-white mb-6">Connect</h4>
                <ul class="space-y-3 text-sm text-white/40">
                    <li><a class="hover:text-primary transition-colors" href="https://chat.whatsapp.com/Hj9JyfVHUOE2g7dqoql1OB" target="_blank" rel="noopener noreferrer">WhatsApp</a></li>
                    <li><a class="hover:text-primary transition-colors" href="https://www.instagram.com/salento.dev/" target="_blank" rel="noopener noreferrer">Instagram</a></li>
                    <li><a class="hover:text-primary transition-colors" href="https://it.linkedin.com/company/salento-dev" target="_blank" rel="noopener noreferrer">LinkedIn</a></li>
                </ul>
            </div>
        </div>
        <div class="flex flex-col md:flex-row items-center justify-between border-t border-white/5 pt-8 text-[10px] uppercase tracking-[0.3em] text-white/30">
            <p>© {{ date('Y') }} SALENTO.DEV // ALL RIGHTS RESERVED.</p>
            <div class="mt-4 md:mt-0 flex gap-8">
                <a class="hover:text-primary" href="#">Privacy_Policy</a>
                <a class="hover:text-primary" href="#">Cookie_Settings</a>
            </div>
        </div>
    </div>
</footer>