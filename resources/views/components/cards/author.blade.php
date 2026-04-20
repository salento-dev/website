@props(['author'])

<div class="flex items-start gap-4">
    <div class="size-12 shrink-0 overflow-hidden rounded-full border border-primary/20 bg-surface-700">
        @if($author->avatar_url)
            <img src="{{ $author->avatar_url }}" alt="{{ $author->name }}" class="size-full object-cover" />
        @else
            <x-heroicon-o-user class="size-6 text-primary/60" />
        @endif
    </div>
    <div class="flex self-center min-w-0 flex-col gap-1">
        <p class="font-outfit font-bold text-mist-50 truncate">{{ $author->name }}</p>
        @if(filled($author->role))
            <p class="font-mono text-2xs uppercase tracking-widest text-primary">{{ $author->role }}</p>
        @endif
        @if(filled($author->bio))
            <p class="mt-1 text-2xs leading-relaxed text-mist-400">{{ $author->bio }}</p>
        @endif
        @if(filled($author->github_url) || filled($author->linkedin_url) || filled($author->twitter_url) || filled($author->website_url))
            <div class="mt-1 flex items-center gap-3">
                @if(filled($author->github_url))
                    <a href="{{ $author->github_url }}" target="_blank" rel="noreferrer noopener"
                        class="text-mist-400 transition-colors hover:text-primary" title="GitHub">
                        <x-heroicon-o-code-bracket class="size-4" />
                    </a>
                @endif
                @if(filled($author->linkedin_url))
                    <a href="{{ $author->linkedin_url }}" target="_blank" rel="noreferrer noopener"
                        class="text-mist-400 transition-colors hover:text-primary" title="LinkedIn">
                        <x-heroicon-o-briefcase class="size-4" />
                    </a>
                @endif
                @if(filled($author->twitter_url))
                    <a href="{{ $author->twitter_url }}" target="_blank" rel="noreferrer noopener"
                        class="text-mist-400 transition-colors hover:text-primary" title="X / Twitter">
                        <x-heroicon-o-chat-bubble-left class="size-4" />
                    </a>
                @endif
                @if(filled($author->website_url))
                    <a href="{{ $author->website_url }}" target="_blank" rel="noreferrer noopener"
                        class="text-mist-400 transition-colors hover:text-primary" title="Sito web">
                        <x-heroicon-o-globe-alt class="size-4" />
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
