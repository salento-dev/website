<?php

namespace App\Livewire;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Livewire\Component;

class EventDetailPage extends Component
{
    public Event $event;

    public function mount(Event $event): void
    {
        abort_unless($event->is_published, 404);
        abort_unless(! $event->work_in_progress, 404);

        $relations = [];

        if (Schema::hasTable('event_media')) {
            $relations[] = 'media';
        }

        if (Schema::hasTable('event_categories') && Schema::hasTable('event_category_event')) {
            $relations[] = 'categories';
        }

        if (Schema::hasTable('event_resource')) {
            $relations[] = 'resources';
        }

        $this->event = $event->load($relations);

        if (! in_array('media', $relations, true)) {
            $this->event->setRelation('media', new Collection);
        }

        if (! in_array('categories', $relations, true)) {
            $this->event->setRelation('categories', new Collection);
        }

        if (! in_array('resources', $relations, true)) {
            $this->event->setRelation('resources', new Collection);
        }
    }

    public function toEmbedUrl(?string $url): ?string
    {
        if (blank($url)) {
            return null;
        }

        $trimmedUrl = trim((string) $url);

        if (preg_match('~youtube\.com/watch\?v=([\w-]+)~', $trimmedUrl, $matches) === 1) {
            return 'https://www.youtube.com/embed/'.$matches[1];
        }

        if (preg_match('~youtu\.be/([\w-]+)~', $trimmedUrl, $matches) === 1) {
            return 'https://www.youtube.com/embed/'.$matches[1];
        }

        if (preg_match('~vimeo\.com/(\d+)~', $trimmedUrl, $matches) === 1) {
            return 'https://player.vimeo.com/video/'.$matches[1];
        }

        return null;
    }

    public function render(): View
    {
        $relatedEvents = Event::query()
            ->where('is_published', true)
            ->whereKeyNot($this->event->getKey())
            ->with('categories:id,name,slug')
            ->orderBy('starts_at')
            ->limit(2)
            ->get();

        return view('livewire.event-detail-page', [
            'event' => $this->event,
            'relatedEvents' => $relatedEvents,
        ])->layout('layouts.app', [
            'pageTitle' => $this->event->title,
            'metaDescription' => $this->event->description,
        ]);
    }
}
