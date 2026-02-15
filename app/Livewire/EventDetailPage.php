<?php

namespace App\Livewire;

use App\Models\Event;
use Illuminate\View\View;
use Livewire\Component;

class EventDetailPage extends Component
{
    public Event $event;

    public function mount(Event $event): void
    {
        abort_unless($event->is_published, 404);

        $this->event = $event;
    }

    public function render(): View
    {
        $relatedEvents = Event::query()
            ->where('is_published', true)
            ->whereKeyNot($this->event->getKey())
            ->orderBy('starts_at')
            ->limit(3)
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
