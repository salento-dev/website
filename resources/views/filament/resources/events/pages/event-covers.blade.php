<x-filament-panels::page>
    <div style="display: grid; gap: 1.5rem; grid-template-columns: repeat(auto-fit, minmax(18rem, 1fr)); align-items: start;">
        @foreach ($this->getCovers() as $cover)
            @php($format = $cover['format'])

            <x-filament::section
                :heading="$format->label()"
                :description="$format->width().' × '.$format->height().' px'"
                wire:key="cover-{{ $format->value }}"
            >
                <div
                    x-data="{
                        scale: 0,
                        busy: false,
                        async capture() {
                            return await this.$refs.iframe.contentWindow.captureCover();
                        },
                        async download() {
                            this.busy = true;
                            try {
                                const link = document.createElement('a');
                                link.href = URL.createObjectURL(await this.capture());
                                link.download = @js($cover['filename']);
                                link.click();
                                setTimeout(() => URL.revokeObjectURL(link.href), 1000);
                            } finally {
                                this.busy = false;
                            }
                        },
                        async useOnWebsite() {
                            this.busy = true;
                            try {
                                const file = new File([await this.capture()], @js($cover['filename']), { type: 'image/png' });
                                await $wire.$upload('websiteCover', file);
                                await $wire.saveWebsiteCover();
                            } finally {
                                this.busy = false;
                            }
                        },
                    }"
                    x-init="new ResizeObserver(() => scale = $refs.frame.clientWidth / {{ $format->width() }}).observe($refs.frame)"
                    x-on:download-all-covers.window="download()"
                >
                    <div x-ref="frame" style="position: relative; overflow: hidden; width: 100%; aspect-ratio: {{ $format->width() }} / {{ $format->height() }};">
                        <iframe
                            x-ref="iframe"
                            src="{{ $cover['url'] }}"
                            title="{{ $format->label() }}"
                            tabindex="-1"
                            style="position: absolute; top: 0; left: 0; border: 0; pointer-events: none; transform-origin: 0 0; width: {{ $format->width() }}px; height: {{ $format->height() }}px;"
                            x-effect="$el.style.transform = `scale(${scale})`"
                        ></iframe>
                    </div>

                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 1rem;">
                        <x-filament::button
                            color="gray"
                            icon="heroicon-o-arrow-down-tray"
                            x-on:click="download()"
                            x-bind:disabled="busy"
                        >
                            Scarica PNG
                        </x-filament::button>

                        @if ($format === \App\Enums\CoverFormat::Website)
                            <x-filament::button
                                icon="heroicon-o-globe-alt"
                                x-on:click="useOnWebsite()"
                                x-bind:disabled="busy"
                            >
                                Usa come immagine del sito
                            </x-filament::button>
                        @endif
                    </div>
                </div>
            </x-filament::section>
        @endforeach
    </div>

    <div>
        <x-filament::button icon="heroicon-o-arrow-down-tray" x-data x-on:click="$dispatch('download-all-covers')">
            Scarica tutte
        </x-filament::button>
    </div>
</x-filament-panels::page>
