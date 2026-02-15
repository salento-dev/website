<?php

namespace App\Livewire;

use App\Models\Document;
use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Livewire\Component;

class HomePage extends Component
{
    public function render(): View
    {
        $events = Schema::hasTable('events')
            ? Event::query()
                ->where('is_published', true)
                ->where('starts_at', '>=', now())
                ->orderBy('starts_at')
                ->limit(3)
                ->get()
            : new Collection;

        $documents = Schema::hasTable('documents')
            ? Document::query()
                ->where('is_published', true)
                ->latest()
                ->limit(5)
                ->get()
            : new Collection;

        return view('livewire.home-page', [
            'events' => $events,
            'documents' => $documents,
        ])->layout('layouts.app', [
            'pageTitle' => 'Home',
            'metaDescription' => 'Community di sviluppatori nel Salento: eventi, documenti e iniziative.',
        ]);
    }
}
