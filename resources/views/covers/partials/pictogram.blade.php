{{--
    Il pittogramma del logo, alto quanto la copertina: l'ultima barra tocca il bordo inferiore.
    Gradiente in coordinate del logo (viewBox 90×105): viola fino a circa un terzo, poi verso il fucsia.
--}}
@php
    $pictogramHeight = $format->height() * 105 / 104.5;
    $pictogramWidth = $pictogramHeight * 90 / 105;
    $pictogramLeft = $format === \App\Enums\CoverFormat::Website
        ? $format->width() - $pictogramWidth + 3
        : $format->width() * 380 / 3508;
@endphp

<svg aria-hidden="true" class="absolute size-0">
    <defs>
        <linearGradient id="cover-gradient" gradientUnits="userSpaceOnUse" x1="0" y1="0" x2="0" y2="104.5">
            <stop offset="0.36" stop-color="#6a14d1" />
            <stop offset="1" stop-color="#c82090" />
        </linearGradient>
    </defs>
</svg>

<x-ui.logo aria-hidden="true" class="absolute top-0"
    style="left: {{ $pictogramLeft }}px; width: {{ $pictogramWidth }}px; height: {{ $pictogramHeight }}px; fill: url(#cover-gradient);" />
