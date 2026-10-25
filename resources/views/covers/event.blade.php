<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width={{ $format->width() }}">

    <title>{{ $event->title }} - {{ $format->label() }}</title>

    @vite(['resources/css/app.css', 'resources/js/covers.js'])
</head>

{{-- Colori del brand (Salento Dark e gradiente delle copertine Figma), usati solo qui --}}
<body class="m-0 overflow-hidden bg-[#1d1e1c]">
    <div data-cover class="relative overflow-hidden bg-[#1d1e1c] font-outfit text-[#eaeaea]"
        style="width: {{ $format->width() }}px; height: {{ $format->height() }}px;">
        @include('covers.partials.pictogram')

        @include('covers.formats.'.$format->value)
    </div>
</body>

</html>
