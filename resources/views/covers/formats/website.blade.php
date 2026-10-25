{{-- Valori del frame Figma «Slide 16:9 - 1» --}}
<div class="absolute top-[94px] right-[84px] left-[122px]">
    <p data-fit class="text-[180px] leading-[226.8px] font-semibold whitespace-nowrap">salento.dev</p>
    <p data-fit class="text-[180px] leading-[226.8px] font-semibold whitespace-nowrap">
        {{ trim(($event->edition ? '#'.$event->edition.' ' : '').$title) }}
    </p>

    @if (filled($event->subtitle))
        <p class="max-w-[1007px] text-[100px] leading-[216.69px] font-light">{!! nl2br(e($event->subtitle)) !!}</p>
    @endif
</div>

<p class="absolute right-[84px] bottom-[63.4px] text-[200px] leading-[323.6px] font-semibold">
    {{ $event->local_starts_at->format('d.m.y') }}
</p>

<div class="absolute right-[84px] bottom-[62.2px] left-[122px] flex justify-between gap-12 font-roboto-mono text-[42px] leading-[67.8px] tracking-[-0.0967em] whitespace-nowrap">
    <p><span class="font-semibold">{{ $venue }}</span>@if ($town)<span> - {{ $town }}</span>@endif</p>
    {{-- Figma non applica il tracking negativo dopo l'ultimo carattere: lo compensa il margine --}}
    <p class="mr-[0.0967em] font-semibold">h.{{ $event->local_starts_at->format('H.i') }}@if (filled($event->cover_note)) - {{ $event->cover_note }}@endif</p>
</div>
