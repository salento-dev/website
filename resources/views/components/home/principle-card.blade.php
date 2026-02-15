@props([
    'title',
    'description',
])

<article class="cyber-panel border border-cyber-border bg-cyber-card p-6">
    <h3 class="mb-3 text-lg font-bold text-primary uppercase">{{ $title }}</h3>
    <p class="text-sm leading-relaxed text-primary/70">{{ $description }}</p>
</article>