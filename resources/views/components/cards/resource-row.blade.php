@props(['resource'])

@php
    $isFile = $resource->source_type === 'file' && filled($resource->file_path);
    $url = $isFile
        ? asset('storage/' . ltrim((string) $resource->file_path, '/'))
        : (filled($resource->external_url) ? $resource->external_url : '#');
@endphp

<a class="group flex items-center justify-between p-4 transition-colors hover:bg-primary/5" href="{{ $url }}" target="_blank" rel="noreferrer noopener" id="documenti">
    <div class="flex items-center gap-4">
        <span class="material-symbols-outlined text-primary/40 transition-colors group-hover:text-primary">
            {{ $isFile ? 'description' : 'link' }}
        </span>
        <span class="text-sm transition-colors group-hover:text-primary">{{ $resource->title }}</span>
    </div>
    <div class="flex items-center gap-8 text-[10px]">
        <span class="hidden text-primary/30 uppercase sm:inline">{{ $resource->created_at?->diffForHumans() }}</span>
        <span class="font-bold text-primary">{{ $isFile ? 'APRI' : 'VAI' }}</span>
    </div>
</a>