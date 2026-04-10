@props(['collection', 'label' => 'items found'])

@php
    $count = $collection instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator
        ? $collection->total()
        : $collection->count();
@endphp

<div class="hidden md:d-flex items-center gap-1 text-xs">
    <span aria-hidden="true">{{ ucfirst($label) }}:</span>
    <span class="item min-w-9 font-medium" aria-label="{{ $count }} {{ $label }}" role="status">
        {{ $count }}
    </span>
</div>
