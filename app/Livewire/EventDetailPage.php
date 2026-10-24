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

        $this->event = $event->load($relations)->loadCount('pageViews');

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

    /**
     * Get the event status for the hero badge: "ongoing" while it runs, "past" once over, "upcoming" otherwise.
     *
     * Without an end date the event counts as ongoing until the end of its start day, as in the featured card.
     */
    protected function eventStatus(): string
    {
        $endsAt = $this->event->ends_at ?? $this->event->starts_at->copy()->tz('Europe/Rome')->endOfDay();

        if (now()->between($this->event->starts_at, $endsAt)) {
            return 'ongoing';
        }

        return $this->event->isPast() ? 'past' : 'upcoming';
    }

    /**
     * Get the next published, non-WIP event, so a past event can point visitors to it.
     */
    private function nextEvent(): ?Event
    {
        return Event::query()
            ->where('is_published', true)
            ->where('work_in_progress', false)
            ->whereKeyNot($this->event->getKey())
            ->where(function ($query): void {
                $query->whereNotNull('ends_at')
                    ->where('ends_at', '>=', now())
                    ->orWhere(function ($query): void {
                        $query->whereNull('ends_at')
                            ->where('starts_at', '>=', now()->startOfDay());
                    });
            })
            ->orderBy('starts_at')
            ->first();
    }

    public function render(): View
    {
        $eventStatus = $this->eventStatus();

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
            'eventStatus' => $eventStatus,
            'nextEvent' => $eventStatus === 'past' ? $this->nextEvent() : null,
        ])->layout('layouts.app', [
            'pageTitle' => $this->event->title,
            'metaDescription' => $this->event->excerpt ?? $this->event->body,
        ]);
    }
}
