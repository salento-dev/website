@php
    $whatsapp = collect(config('community.social'))->firstWhere('key', 'whatsapp');
    $whatsappUrl = $whatsapp['url'];
    $whatsappIcon = Blade::render('<x-simpleicon-whatsapp class="size-full" />');
@endphp

<nav class="sticky top-0 z-10 border-b border-border bg-surface">
    <header data-triangle-decoration="bottom" class="flex h-16 w-full items-stretch justify-between nav:gap-3">

        <div class="flex w-22 items-stretch border-r border-border min-[370px]:w-25 nav:hidden">
            <x-ui.spark-link
                :href="$whatsappUrl"
                aria-label="Join WhatsApp – gruppo della community"
                icon-size="5"
                class="w-full"
            >
                <x-slot:icon>{!! $whatsappIcon !!}</x-slot:icon>
            </x-ui.spark-link>
        </div>


        <div class="flex grow items-stretch nav:grow-0">

            <a href="{{ route('home') }}" aria-label="{{ config('community.name') }}, home"
                class="group grid w-full grow place-items-center border-r border-border px-1 pb-0.5 focus:outline-none focus-visible:ring focus-visible:ring-primary/50 focus-visible:ring-inset min-[370px]:px-3.5">
                <div class="relative">
                    <x-ui.logo class="h-8 w-auto text-surface-50" />
                </div>
            </a>


            <x-partials.desktop-navbar />
        </div>


        <div class="flex items-stretch">

            <div class="flex items-stretch">
                <div class="hidden justify-center border-l border-green-500/30 bg-green-500/5 nav:flex nav:w-auto">
                    <x-ui.spark-link
                        :href="$whatsappUrl"
                        text="Join WhatsApp"
                        aria-label="Join WhatsApp – gruppo della community"
                        class="h-full"
                    >
                        <x-slot:icon>{!! $whatsappIcon !!}</x-slot:icon>
                    </x-ui.spark-link>
                </div>
            </div>


            <x-partials.mobile-navbar />
        </div>
    </header>
</nav>
