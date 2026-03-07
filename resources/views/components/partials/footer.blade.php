<footer class="border-t border-border">
    <div class="px-6 py-16 sm:px-12 lg:px-20">
        <div class="mx-auto max-w-6xl">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 py-8">
                <div class="col-span-1 md:col-span-2">
                    <x-ui.logo class="mb-6" />
                    <p class="max-w-md text-sm text-surface-400 leading-relaxed mb-8">
                        Unisciti alla rivoluzione tecnologica del Sud Italia. La nostra community è aperta a sviluppatori, designer e appassionati di tecnologia che credono nel costruire il futuro insieme.
                    </p>
                    <div class="flex gap-3">
                    <a href="https://www.instagram.com/salento.dev/" target="_blank" rel="noopener noreferrer" 
                       class="flex h-10 w-10 items-center justify-center rounded-lg border border-border bg-surface-900 text-surface-400 hover:bg-border/20 hover:text-primary hover:border-primary/50 transition-all duration-200">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <a href="https://it.linkedin.com/company/salento-dev" target="_blank" rel="noopener noreferrer"
                       class="flex h-10 w-10 items-center justify-center rounded-lg border border-border bg-surface-900 text-surface-400 hover:bg-border/20 hover:text-primary hover:border-primary/50 transition-all duration-200">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>
                </div>
            </div>
            <div>
                <h4 class="text-xs font-semibold uppercase tracking-wider text-surface-300 mb-4">Naviga</h4>
                <ul class="space-y-3 text-sm text-surface-400">
                    <li><a class="hover:text-primary transition-colors duration-200" href="{{ route('events') }}">Eventi</a></li>
                    <li><a class="hover:text-primary transition-colors duration-200" href="{{ route('resources') }}">Risorse</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-xs font-semibold uppercase tracking-wider text-surface-300 mb-4">Community</h4>
                <ul class="space-y-3 text-sm text-surface-400">
                    <li><a class="hover:text-primary transition-colors duration-200" href="https://chat.whatsapp.com/Hj9JyfVHUOE2g7dqoql1OB" target="_blank" rel="noopener noreferrer">WhatsApp</a></li>
                    <li><a class="hover:text-primary transition-colors duration-200" href="https://www.instagram.com/salento.dev/" target="_blank" rel="noopener noreferrer">Instagram</a></li>
                    <li><a class="hover:text-primary transition-colors duration-200" href="https://it.linkedin.com/company/salento-dev" target="_blank" rel="noopener noreferrer">LinkedIn</a></li>
                </ul>
            </div>
        </div>
            <div class="mt-16 border-t border-border pt-8">
                <div class="flex flex-col md:flex-row items-center justify-between text-sm text-surface-400">
                    <p>© {{ date('Y') }} Salento.dev - Tutti i diritti riservati</p>
                    <div class="mt-4 md:mt-0 flex gap-6">
                        <a class="hover:text-primary transition-colors duration-200" href="#">Privacy Policy</a>
                        <a class="hover:text-primary transition-colors duration-200" href="#">Cookie</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>