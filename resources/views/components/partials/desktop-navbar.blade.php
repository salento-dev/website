<nav class="hidden items-center gap-2 pl-3.5 nav:flex">
    @foreach (config('navigation.items') as $item)
        <div>
            <x-partials.desktop-navbar-item :route="$item['route']" :route-name="$item['label']" />
        </div>
    @endforeach
</nav>
