<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\Resource;
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
                ->limit(2)
                ->get()
            : new Collection;

        $resources = Schema::hasTable('resources')
            ? Resource::query()
                ->where('is_published', true)
                ->latest()
                ->limit(5)
                ->get()
            : new Collection;

        return view('livewire.home-page', [
            'events' => $events,
            'resources' => $resources,
        ])->layout('layouts.app', [
            'pageTitle' => 'Home',
            'metaDescription' => 'Community di sviluppatori nel Salento: eventi, documenti e iniziative.',
        ]);
    }
}
