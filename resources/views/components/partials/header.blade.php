<nav class="sticky top-0 z-100 border-b border-border bg-surface">
    <header data-triangle-decoration="bottom" class="flex h-16 w-full items-stretch justify-between nav:gap-3">

        <div class="flex w-22 items-stretch border-r border-border min-[370px]:w-25 2xs:w-30 nav:hidden">
            <a href="https://chat.whatsapp.com/Hj9JyfVHUOE2g7dqoql1OB" target="_blank" aria-label="Open WhatsApp group"
                class="flex w-full items-center justify-center px-3.5 transition-all duration-300 ease-out hover:bg-border/30 focus:outline-none focus-visible:ring focus-visible:ring-black/50 focus-visible:ring-inset">
                WhatsApp
            </a>
        </div>


        <div class="flex grow items-stretch nav:grow-0">

            <a href="{{ route('home') }}" aria-label="Filament homepage"
                class="group grid w-full grow place-items-center border-r border-border px-1 pb-0.5 focus:outline-none focus-visible:ring focus-visible:ring-black/50 focus-visible:ring-inset min-[370px]:px-3.5">
                <div class="relative">
                    <x-ui.logo class="h-10" />
                </div>
            </a>


            <x-partials.desktop-navbar />
        </div>


        <div class="flex items-stretch">

            <div class="flex items-stretch">
                <div class="hidden justify-center border-l border-border nav:flex nav:w-auto nav-github:border-l-0">
                    <a href="https://chat.whatsapp.com/Hj9JyfVHUOE2g7dqoql1OB" target="_blank" aria-label="Open WhatsApp group"
                        class="flex w-full items-center justify-center gap-2.5 px-3.5 transition-all duration-300 ease-out hover:bg-border/30 hover:px-4 focus:outline-none focus-visible:ring focus-visible:ring-black/50 focus-visible:ring-inset nav:hover:gap-3">
                        Join WhatsApp
                    </a>
                </div>
            </div>


            <x-partials.mobile-navbar />
        </div>
    </header>
</nav>
