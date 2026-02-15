<?php

namespace App\Livewire;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Livewire\Component;

class EventsPage extends Component
{
    public function render(): View
    {
        $events = Schema::hasTable('events')
            ? Event::query()
                ->where('is_published', true)
                ->orderBy('starts_at')
                ->get()
            : new Collection;

        return view('livewire.events-page', [
            'events' => $events,
        ])->layout('layouts.app', [
            'pageTitle' => 'Eventi',
            'metaDescription' => 'Calendario eventi della community Salento.dev.',
        ]);
    }
}
