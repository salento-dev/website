@props([
    'path' => '',
])

<div class="overflow-hidden border border-border bg-black">
    <!-- Header -->
    <div class="flex items-center justify-between border-b border-border bg-surface-900 px-6 py-3">
        <div class="flex gap-2">
            <div class="size-2 rounded-full bg-red-600"></div>
            <div class="size-2 rounded-full bg-yellow-600"></div>
            <div class="size-2 rounded-full bg-green-600"></div>
        </div>
        <span class="text-[0.625rem] font-mono text-primary tracking-widest uppercase">root@{{ config('community.name') }}:~{{ $path }}</span>
    </div>
    <!-- Terminal Body -->
    {{ $slot }}
</div>