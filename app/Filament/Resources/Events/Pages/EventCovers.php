<?php

namespace App\Filament\Resources\Events\Pages;

use App\Enums\CoverFormat;
use App\Filament\Resources\Events\EventResource;
use App\Models\Event;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

/**
 * Previews the event covers and lets the admin's browser export them as PNG:
 * the hosting has no Node/Chromium, so rendering happens client side.
 */
class EventCovers extends Page
{
    use InteractsWithRecord;
    use WithFileUploads;

    private const GENERATED_DIRECTORY = 'events/covers';

    protected static string $resource = EventResource::class;

    protected string $view = 'filament.resources.events.pages.event-covers';

    public ?TemporaryUploadedFile $websiteCover = null;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function getTitle(): string
    {
        return 'Copertine';
    }

    public function getSubheading(): string
    {
        return $this->getRecord()->title;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('edit')
                ->label('Modifica evento')
                ->icon(Heroicon::OutlinedPencilSquare)
                ->color('gray')
                ->url(fn (): string => EditEvent::getUrl(['record' => $this->getRecord()])),
        ];
    }

    /**
     * @return list<array{format: CoverFormat, url: string, filename: string}>
     */
    public function getCovers(): array
    {
        /** @var Event $event */
        $event = $this->getRecord();

        return array_map(fn (CoverFormat $format): array => [
            'format' => $format,
            'url' => URL::temporarySignedRoute('events.cover', now()->addHours(2), [
                'event' => $event,
                'format' => $format,
            ]),
            'filename' => "{$event->slug}-{$format->value}.png",
        ], CoverFormat::cases());
    }

    /**
     * Store the 16:9 cover exported by the browser as the event image shown on the website.
     */
    public function saveWebsiteCover(): void
    {
        $this->validate([
            'websiteCover' => ['required', 'image', 'mimes:png', 'max:10240'],
        ]);

        /** @var Event $event */
        $event = $this->getRecord();
        $previousPath = $event->image_path;

        $path = $this->websiteCover->storeAs(
            self::GENERATED_DIRECTORY,
            $event->slug.'-'.now()->format('YmdHis').'.png',
            'public',
        );

        $event->update(['image_path' => $path]);

        if (filled($previousPath) && str_starts_with($previousPath, self::GENERATED_DIRECTORY.'/')) {
            Storage::disk('public')->delete($previousPath);
        }

        $this->websiteCover = null;

        Notification::make()
            ->success()
            ->title('Immagine del sito aggiornata')
            ->send();
    }
}
