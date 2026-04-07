@props([
    'wireSearch' => null,
    'versions' => [
        ['value' => 'all', 'label' => 'All'],
        ['value' => '3', 'label' => '3.x'],
        ['value' => '4', 'label' => '4.x'],
        ['value' => '5', 'label' => '5.x'],
    ],
    'prices' => [
        ['value' => 'all', 'label' => 'All'],
        ['value' => 'free', 'label' => 'Free'],
        ['value' => 'paid', 'label' => 'Paid'],
    ],
    'tags' => [],
    'tagLabels' => [],
    'listeningMessages' => [
        'Whatcha looking for? 🔍',
        "I'm listening... 👀",
        "Go ahead, I'm ready 🎯",
        'Type away! ⌨️',
        'Searching is fun! 🤓',
        'What can I find for you? 🕵️‍♂️',
    ],
])

<section
    x-data="pluginListFilters({
        wireSearch: @js($wireSearch),
        tags: @js(array_values($tags)),
        tagLabels: @js($tagLabels),
        listeningMessages: @js($listeningMessages),
    })"
    aria-label="Plugin search and listing"
>
    <search class="flex w-full flex-col-reverse gap-2.75 sm:flex-row sm:items-center" role="search">
        <div class="flex items-center">
            <button
                type="button"
                x-ref="filtersButton"
                x-on:click="showFilters = !showFilters"
                :aria-expanded="showFilters"
                aria-controls="plugin-filters-panel"
                class="group btn"
                :class="showFilters ? 'bg-surface-900 hover:bg-surface-900/90' : 'hover:bg-surface-900/10'"
            >
                <span>
                    <svg class="size-5" viewBox="0 0 24 24">
                        <g stroke="currentColor" stroke-width="1" stroke-linecap="round">
                            <line x1="2" y1="5" x2="22" y2="5"></line>
                            <line x1="2" y1="12" x2="22" y2="12"></line>
                            <line x1="2" y1="19" x2="22" y2="19"></line>
                        </g>
                        <g stroke="currentColor" stroke-width="1" fill="none">
                            <circle cx="8" cy="5" r="2"></circle>
                            <circle cx="14" cy="12" r="2"></circle>
                            <circle cx="11" cy="19" r="2"></circle>
                        </g>
                    </svg>
                </span>

                <span class="text-sm font-medium">Filters</span>
            </button>

            <div
                class="grid transition-[grid-template-columns] duration-500 ease-out"
                :style="`grid-template-columns: ${activeFilterCount > 0 ? '1fr' : '0fr'};`"
            >
                <div class="flex items-center overflow-hidden">
                    <div class="px-1 transition-opacity duration-500 ease-out" :style="`opacity: ${activeFilterCount > 0 ? '1' : '0'}`">
                        <svg class="h-px w-5 text-surface-500" viewBox="0 0 20 1" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <line class="stroke-current" y1="0.5" x2="20" y2="0.5" stroke-dasharray="4 4"></line>
                        </svg>
                    </div>

                    <button
                        type="button"
                        x-on:click="resetFilters()"
                        :inert="activeFilterCount === 0"
                        :aria-hidden="activeFilterCount === 0"
                        class="group custom-dashed-border inline-flex h-10 min-w-27 items-center justify-center gap-1.5 rounded-xs bg-surface-800 text-surface-50 pr-3 pl-2.5 transition-all duration-500 ease-out will-change-transform hover:bg-secondary-200/70 active:scale-95"
                        :style="`opacity: ${activeFilterCount > 0 ? '1' : '0'}; visibility: ${activeFilterCount > 0 ? 'visible' : 'hidden'}; pointer-events: ${activeFilterCount > 0 ? 'auto' : 'none'};`"
                    >
                        <span class="text-sm">Reset</span>

                        <span
                            aria-hidden="true"
                            class="inline-grid h-5 min-w-4.75 place-items-center rounded-xs bg-secondary-200 px-1 text-center font-medium"
                            x-text="activeFilterCount"
                        ></span>
                    </button>
                </div>
            </div>
        </div>

        <x-ui.input-search
            placeholder="Search"
            sr-label="Search plugins, authors, descriptions, and repos"
            :wire-model="$wireSearch"
        />
    </search>

    <div id="plugin-filters-panel" x-show="showFilters" x-collapse>
        <div class="mt-5 flex flex-wrap items-center gap-x-7 gap-y-4">
            <x-sections.search.tab-switcher
                legend="Version:"
                legend-id="version-filter-label"
                name="version-filter"
                model="selectedVersion"
                :options="$versions"
                id-prefix="version-filter"
            />

            <x-sections.search.tab-switcher
                legend="Price:"
                legend-id="price-filter-label"
                name="price-filter"
                model="selectedPrice"
                :options="$prices"
                id-prefix="price-filter"
            />

            <fieldset class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <legend class="float-left text-sm">Features:</legend>
                <div class="flex flex-wrap gap-2">
                    <x-sections.search.feature-chip state="features.darkMode" label="Dark mode ready">
                        <svg class="size-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18" fill="none">
                            <path class="fill-current" d="M15.8526 11.6206C15.9755 11.7638 16.011 11.9626 15.9451 12.1395C13.7932 18.0165 6.08626 19.3601 2.0726 14.558C-1.53851 10.2375 0.229642 3.64308 5.51783 1.70875C5.89324 1.57216 6.27573 1.89317 6.20632 2.28658C6.20576 2.28974 6.20518 2.29288 6.20457 2.29602C5.25629 7.1087 8.93646 11.5903 13.8417 11.5963C14.3506 11.5965 14.8584 11.5473 15.3578 11.4493C15.5416 11.4129 15.7306 11.4784 15.8526 11.6206Z"></path>
                        </svg>
                    </x-sections.search.feature-chip>

                    <x-sections.search.feature-chip state="features.multilingual" label="Multilingual support">
                        <svg class="size-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18" fill="none">
                            <path class="fill-current" d="M17.7694 16.162L13.2792 7.18163C13.1141 6.85017 12.652 6.82185 12.4475 7.13064L10.63 10.7577C9.13564 10.6958 7.69742 10.171 6.51429 9.25594C7.88973 7.84927 8.71042 5.99289 8.82514 4.02887H10.9243C11.2946 4.02886 11.5261 3.62794 11.3409 3.30722C11.255 3.15838 11.0962 3.06669 10.9243 3.06669H6.27374V1.62341C6.27374 1.25307 5.87284 1.0216 5.55211 1.20677C5.40326 1.29271 5.31156 1.45153 5.31156 1.62341V3.06669H0.661006C0.290663 3.06671 0.059194 3.46762 0.244376 3.78835C0.330319 3.93718 0.489141 4.02887 0.661006 4.02887H7.86055C7.74636 5.7576 7.01207 7.38702 5.79265 8.61769C5.04469 7.86391 4.4727 6.95403 4.11765 5.95324C4.00264 5.60121 3.54967 5.50569 3.3023 5.78132C3.18194 5.91543 3.14662 6.10557 3.2108 6.27397C3.60654 7.39169 4.24138 8.40962 5.07101 9.25674C3.80948 10.2355 2.25772 10.7659 0.661006 10.7642C0.290663 10.7642 0.059194 11.1651 0.244376 11.4858C0.330319 11.6347 0.489134 11.7263 0.661006 11.7263C2.52905 11.7281 4.34089 11.0874 5.79265 9.91183C7.044 10.9207 8.56464 11.539 10.165 11.6895L7.9287 16.162C7.76329 16.4935 8.01871 16.8797 8.38847 16.8572C8.56008 16.8468 8.71309 16.7456 8.78986 16.5918L9.93887 14.2922H15.7585L16.9083 16.5918C16.9897 16.755 17.1565 16.858 17.3389 16.858C17.4136 16.8582 17.4872 16.8406 17.5538 16.8067C17.7912 16.6881 17.8877 16.3996 17.7694 16.162Z"></path>
                        </svg>
                    </x-sections.search.feature-chip>

                    <x-sections.search.feature-chip state="features.featured" label="Featured">
                        <svg class="size-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18" fill="none">
                            <path class="fill-current" d="M8.08887 0.989258C8.31973 0.427753 9.07211 0.326095 9.44336 0.806641C9.4865 0.862481 9.52201 0.923989 9.54883 0.989258V0.991211L11.3418 5.33105L11.458 5.61328L11.7637 5.63867L16.4102 6.0127V6.01367C17.0215 6.06538 17.3475 6.75913 16.9971 7.2627C16.9591 7.31722 16.9145 7.36673 16.8643 7.41016L13.3232 10.4697L13.0938 10.668L13.1631 10.9629L14.2432 15.5381L14.2441 15.54C14.3864 16.1331 13.8328 16.6577 13.248 16.4844C13.1823 16.4648 13.1191 16.4371 13.0605 16.4014H13.0615L9.08301 13.9531L8.82031 13.792L8.55859 13.9531L4.5791 16.4004C4.05796 16.7169 3.38971 16.3508 3.37598 15.7412C3.37445 15.6735 3.38178 15.6059 3.39746 15.54V15.5391L4.48145 10.9629L4.55176 10.668L4.32129 10.4697L0.776367 7.41016C0.312197 7.00899 0.456622 6.25511 1.03613 6.05371C1.09645 6.03279 1.1591 6.01868 1.22266 6.0127L5.87305 5.63867L6.17773 5.61328L6.29492 5.33105L8.08789 0.991211L8.08887 0.989258Z"></path>
                        </svg>
                    </x-sections.search.feature-chip>
                </div>
            </fieldset>
        </div>

        <fieldset class="mt-4" x-show="tags.length > 0">
            <legend class="mb-2 text-sm">Tags:</legend>
            <div class="flex flex-wrap gap-2 pb-2 xs:pb-0" role="group" aria-label="Filter by tags">
                @foreach ($tags as $tag)
                    <x-sections.search.tag-chip
                        :tag="$tag"
                        :label="$tagLabels[$tag] ?? $tag"
                    />
                @endforeach
            </div>
        </fieldset>
    </div>
</section>

@once
    <script>
        function pluginListFilters(config) {
            return {
                search: '',
                showFilters: false,
                selectedVersion: 'all',
                selectedPrice: 'all',
                features: {
                    darkMode: false,
                    multilingual: false,
                    featured: false,
                },
                selectedTags: [],
                tags: config.tags ?? [],
                tagLabels: config.tagLabels ?? {},
                listeningMessages: config.listeningMessages ?? [],
                listeningIndex: 0,



                get activeFilterCount() {
                    let count = 0;

                    if (this.selectedVersion !== 'all') {
                        count++;
                    }

                    if (this.selectedPrice !== 'all') {
                        count++;
                    }

                    if (this.features.darkMode) {
                        count++;
                    }

                    if (this.features.multilingual) {
                        count++;
                    }

                    if (this.features.featured) {
                        count++;
                    }

                    count += this.selectedTags.length;

                    return count;
                },

                get listeningMessage() {
                    if (this.listeningMessages.length === 0) {
                        return '';
                    }

                    return this.listeningMessages[this.listeningIndex % this.listeningMessages.length];
                },

                rotateListeningMessage() {
                    this.listeningIndex++;
                },

                isTagSelected(tag) {
                    return this.selectedTags.includes(tag);
                },

                toggleTag(tag) {
                    if (this.isTagSelected(tag)) {
                        this.selectedTags = this.selectedTags.filter((selectedTag) => selectedTag !== tag);

                        return;
                    }

                    this.selectedTags = [...this.selectedTags, tag];
                },

                clearSearch() {
                    this.search = '';
                },

                resetFilters() {
                    this.selectedVersion = 'all';
                    this.selectedPrice = 'all';
                    this.features = {
                        darkMode: false,
                        multilingual: false,
                        featured: false,
                    };
                    this.selectedTags = [];
                    this.showFilters = false;
                },
            };
        }
    </script>
@endonce
