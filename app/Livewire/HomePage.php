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
        $nextEvent = Schema::hasTable('events')
            ? Event::query()
                ->where('is_published', true)
                ->where('work_in_progress', false)
                ->where(function ($query): void {
                    $query->whereNotNull('ends_at')
                        ->where('ends_at', '>=', now())
                        ->orWhere(function ($query): void {
                            $query->whereNull('ends_at')
                                ->where('starts_at', '>=', now()->startOfDay());
                        });
                })
                ->orderBy('starts_at')
                ->first()
            : null;

        $pastEvents = Schema::hasTable('events')
            ? Event::query()
                ->where('is_published', true)
                ->where('work_in_progress', false)
                ->where(function ($query): void {
                    $query->whereNotNull('ends_at')
                        ->where('ends_at', '<', now())
                        ->orWhere(function ($query): void {
                            $query->whereNull('ends_at')
                                ->where('starts_at', '<', now()->startOfDay());
                        });
                })
                ->orderByDesc('starts_at')
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
            'nextEvent' => $nextEvent,
            'pastEvents' => $pastEvents,
            'resources' => $resources,
        ])->layout('layouts.app', [
            'pageTitle' => 'Home',
            'metaDescription' => 'Community di sviluppatori nel Salento: eventi, documenti e iniziative.',
        ]);
    }
}
