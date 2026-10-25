{{--
    Layout verticale dal frame Figma «Frame» (poster A4, 3508 px di larghezza), scalato sulla larghezza del formato.
    Intestazione ancorata in alto, footer ancorato in basso.
--}}
@php
    $scale = $format->width() / 3508;
    $px = fn (float $value): string => round($value * $scale, 2).'px';
    $topOffset ??= 0;
@endphp

<div class="absolute" style="top: calc({{ $px(560.9) }} + {{ $topOffset }}px); left: {{ $px(352.7) }}; right: {{ $px(352.3) }};">
    @foreach (array_filter(['Salento', 'Dev', $title, $event->edition ? '#'.$event->edition : null]) as $line)
        <p data-fit class="font-semibold whitespace-nowrap" style="font-size: {{ $px(458.62) }}; line-height: {{ $px(458.62) }};">{{ $line }}</p>
    @endforeach

    @if (filled($event->subtitle))
        <p class="font-semibold" style="margin-top: {{ $px(177.5) }}; font-size: {{ $px(216.69) }}; line-height: {{ $px(216.69) }};">{!! nl2br(e($event->subtitle)) !!}</p>
    @endif
</div>

<div class="absolute" style="bottom: {{ $px(372.3) }}; left: {{ $px(352.7) }}; right: {{ $px(352.3) }};">
    <div class="flex items-end justify-between" style="margin-bottom: {{ $px(255.4) }};">
        <div class="flex items-end" style="gap: {{ $px(108) }};">
            <div class="[&>svg]:h-full [&>svg]:w-auto" style="height: {{ $px(255) }}; margin-bottom: {{ $px(13.1) }};">
                {!! file_get_contents(resource_path('images/brand/salentodev-stacked-symbol-white-on-dark.svg')) !!}
            </div>
            @foreach ($partnerLogos as $logo)
                <img src="{{ $logo }}" alt="" class="object-contain object-left"
                    style="width: {{ $px(438) }}; height: {{ $px(244) }}; margin-bottom: {{ $px(18.6) }};">
            @endforeach
        </div>

        <p class="shrink-0 font-semibold" style="font-size: {{ $px(323.6) }}; line-height: {{ $px(323.6) }};">{{ $event->local_starts_at->format('d.m.y') }}</p>
    </div>

    <div class="flex flex-wrap justify-between gap-x-8 font-roboto-mono tracking-[-0.0967em] whitespace-nowrap" style="font-size: {{ $px(72) }}; line-height: {{ $px(67.8) }};">
        <p><span class="font-bold">{{ $venue }}</span>@if ($town)<span> - {{ $town }}</span>@endif</p>
        {{-- Figma non applica il tracking negativo dopo l'ultimo carattere: lo compensa il margine --}}
        <p class="mr-[0.0967em] font-semibold">h.{{ $event->local_starts_at->format('H.i') }}@if (filled($event->cover_note)) - {{ $event->cover_note }}@endif</p>
    </div>
</div>
