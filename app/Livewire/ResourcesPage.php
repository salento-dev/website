<?php

namespace App\Livewire;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Livewire\Component;

class ResourcesPage extends Component
{
    public function render(): View
    {
        $resources = Schema::hasTable('resources')
            ? Resource::query()
                ->where('is_published', true)
                ->latest()
                ->get()
            : new Collection;

        return view('livewire.resources-page', [
            'resources' => $resources,
        ])->layout('layouts.app', [
            'pageTitle' => 'Documenti & Slide',
            'metaDescription' => 'Raccolta documenti e slide della community Salento.dev.',
        ]);
    }
}
