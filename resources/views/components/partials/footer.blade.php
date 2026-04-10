<div class="grid grid-cols-1 md:grid-cols-4 gap-12 py-8">
    <div class="col-span-1 md:col-span-2">
        <x-ui.logo class="mb-6" />
        <p class="max-w-md text-sm text-surface-400 leading-relaxed mb-8">
            {{ config('community.description') }}
        </p>
        <div class="flex gap-3">
            @foreach (config('community.social') as $social)
                <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer"
                    aria-label="{{ $social['label'] }}"
                    class="flex h-10 w-10 items-center justify-center rounded-lg border border-border bg-surface-900 text-surface-400 hover:bg-border/20 hover:text-primary hover:border-primary/50 transition-all duration-200">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="{{ $social['icon_path'] }}" />
                    </svg>
                </a>
            @endforeach
        </div>
    </div>
    <div>
        <h4 class="text-xs font-semibold uppercase tracking-wider text-surface-300 mb-4">Naviga</h4>
        <ul class="space-y-3 text-sm text-surface-400">
            @foreach (array_filter(config('navigation.items'), fn($item) => $item['footer']) as $item)
                <li>
                    <a class="hover:text-primary transition-colors duration-200" href="{{ route($item['route']) }}">{{ $item['label'] }}</a>
                </li>
            @endforeach
        </ul>
    </div>
    <div>
        <h4 class="text-xs font-semibold uppercase tracking-wider text-surface-300 mb-4">Community</h4>
        <ul class="space-y-3 text-sm text-surface-400">
            @foreach (config('community.social') as $social)
                <li>
                    <a class="hover:text-primary transition-colors duration-200"
                        href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer">
                        {{ $social['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
<div class="mt-16 border-t border-border pt-8">
    <div class="flex flex-col md:flex-row items-center justify-between text-sm text-surface-400">
        <p>© {{ date('Y') }} {{ config('community.name') }} - Tutti i diritti riservati</p>
        <div class="mt-4 md:mt-0 flex gap-6">
            <a class="hover:text-primary transition-colors duration-200" href="#">Privacy Policy</a>
            <a class="hover:text-primary transition-colors duration-200" href="#">Cookie</a>
        </div>
    </div>
</div>
