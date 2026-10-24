{{--
    Conteggio dei risultati di una lista filtrabile. La regione role="status" è sempre presente
    (anche su mobile, in sr-only) così gli screen reader annunciano il nuovo conteggio dopo ricerca e filtri.
    Con visible=false resta solo l'annuncio, per le liste che mostrano già il totale altrove.
--}}
@props([
    'collection',
    'label' => 'risultati',
    'singularLabel' => null,
    'visible' => true,
])

@php
    $count = $collection instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator
        ? $collection->total()
        : $collection->count();

    $announcedLabel = $count === 1 && $singularLabel ? $singularLabel : $label;
@endphp

@if ($visible)
    <div {{ $attributes->class(['hidden items-center gap-1 text-xs md:flex']) }} aria-hidden="true">
        <span>{{ ucfirst($label) }}:</span>
        <span class="item min-w-9 font-medium">{{ $count }}</span>
    </div>
@endif
<span class="sr-only" role="status">{{ $count }} {{ $announcedLabel }}</span>
