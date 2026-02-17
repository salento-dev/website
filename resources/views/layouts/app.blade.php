<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{ $metaDescription ?? '' }}">

        <title>{{ $pageTitle ?? config('app.name') }} - {{ config('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
        @livewireStyles

        <style>
            @keyframes scanline {
                0% { transform: translateY(0); }
                100% { transform: translateY(100vh); }
            }
            .scanline {
                width: 100%;
                height: 2px;
                background: rgba(13, 231, 242, 0.1);
                position: fixed;
                z-index: 50;
                pointer-events: none;
                opacity: 0.1;
                animation: scanline 8s linear infinite;
            }
        </style>
    </head>
    <body class="bg-background-dark text-white font-display selection:bg-primary/30 selection:text-primary">
        <div class="scanline"></div>
        {{ $slot }}

        @livewireScripts
    </body>
</html>