@props(['author', 'size' => 'default'])

@php
    $isLg      = $size === 'lg';
    $avatarSize = $isLg ? 'size-16' : 'size-12';
    $nameClass  = $isLg ? 'text-lg'  : 'text-base';
    $bioClass   = $isLg ? 'text-sm'  : 'text-2xs';
    $iconClass  = $isLg ? 'size-5'   : 'size-4';
@endphp

<div class="flex items-start gap-4">
    <div class="{{ $avatarSize }} shrink-0 overflow-hidden rounded-full border border-primary/20 bg-surface-700">
        @if ($author->avatar_url)
            <img loading="lazy" src="{{ $author->avatar_url }}" alt="{{ $author->name }}" class="size-full object-cover" />
        @else
            <x-heroicon-o-user class="size-6 text-primary/60" />
        @endif
    </div>
    <div class="flex min-w-0 flex-col gap-1 self-center">
        <p class="font-outfit font-bold text-surface-50 truncate {{ $nameClass }}">{{ $author->name }}</p>
        @if (filled($author->display_role))
            <p class="font-mono text-2xs uppercase tracking-widest text-primary">{{ $author->display_role }}</p>
        @endif
        @if (filled($author->display_bio))
            <p class="mt-1 leading-relaxed text-surface-400 {{ $bioClass }}">{{ $author->display_bio }}</p>
        @endif
        @if (filled($author->github_url) || filled($author->linkedin_url) || filled($author->display_twitter_url) || filled($author->display_website_url))
            <div class="mt-1 flex items-center gap-3">
                @if (filled($author->github_url))
                    <a href="{{ $author->github_url }}" target="_blank" rel="noreferrer noopener"
                        class="text-surface-400 transition-colors hover:text-primary" title="GitHub">
                        <x-simpleicon-github class="{{ $iconClass }}" />
                    </a>
                @endif
                @if (filled($author->linkedin_url))
                    <a href="{{ $author->linkedin_url }}" target="_blank" rel="noreferrer noopener"
                        class="text-surface-400 transition-colors hover:text-primary" title="LinkedIn">
                        <x-simpleicon-linkedin class="{{ $iconClass }}" />
                    </a>
                @endif
                @if (filled($author->display_twitter_url))
                    <a href="{{ $author->display_twitter_url }}" target="_blank" rel="noreferrer noopener"
                        class="text-surface-400 transition-colors hover:text-primary" title="X / Twitter">
                        <x-simpleicon-x class="{{ $iconClass }}" />
                    </a>
                @endif
                @if (filled($author->display_website_url))
                    <a href="{{ $author->display_website_url }}" target="_blank" rel="noreferrer noopener"
                        class="text-surface-400 transition-colors hover:text-primary" title="Sito web">
                        <x-heroicon-o-globe-alt class="{{ $iconClass }}" />
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
