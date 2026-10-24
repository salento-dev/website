<!DOCTYPE html>
<html class="dark scrollbar-thin" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $metaDescription ?? '' }}">

    <title>{{ $pageTitle ?? config('app.name') }} - {{ config('app.name') }}</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    @livewireStyles
</head>

<body x-data="{
    showMobileMenu: false,
    navMediaQuery: window.matchMedia(
        '(min-width: ' +
        (getComputedStyle(document.documentElement)
            .getPropertyValue('--breakpoint-nav')
            .trim() || '50rem') +
        ')',
    ),
}"
    x-resize="
            if (navMediaQuery.matches) {
                showMobileMenu = false
            }
        "
    :class="{
        'overflow-hidden': showMobileMenu,
    }"
    class="bg-dots-pattern bg-black min-h-screen font-albert-sans text-surface-300 antialiased selection:bg-surface-400/20 sm:px-5 lg:px-10">

    <a href="#contenuto"
        class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-100 focus:rounded-xs focus:bg-surface-800 focus:px-4 focus:py-2 focus:text-sm focus:font-medium focus:text-surface-50 focus:outline-none focus:ring focus:ring-primary/50">
        Vai al contenuto
    </a>

    <main class="relative mx-auto w-full max-w-350 overflow-x-clip border-x border-b border-border bg-surface">
        <x-partials.header />

        <section id="contenuto" tabindex="-1" data-triangle-decoration="bottom" class="relative overflow-hidden border-b border-border focus:outline-none">
            <div class="px-6 py-24 sm:px-12 lg:px-20 lg:py-32">
                <div class="mx-auto max-w-4xl text-center">
                    @yield('hero')
                </div>
            </div>
            @yield('hero-footer')
        </section>

        <div class="px-6 md:px-8 lg:px-16 py-12 md:py-16 lg:py-20">
            {{ $slot }}
        </div>

        <footer data-triangle-decoration="top" class="px-6 md:px-8 lg:px-16 py-16 border-t border-border">
            <x-partials.footer />
        </footer>
    </main>

    @livewireScripts
</body>

</html>
