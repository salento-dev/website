<!DOCTYPE html>
<html class="dark scrollbar-thin" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $metaDescription ?? '' }}">

    <title>{{ $pageTitle ?? config('app.name') }} - {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Albert+Sans:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

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

    <main class="relative mx-auto w-full max-w-350 overflow-x-clip border-x border-b border-border bg-surface">
        <x-partials.header />

        <section data-triangle-decoration="bottom" class="relative overflow-hidden border-b border-border">
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
