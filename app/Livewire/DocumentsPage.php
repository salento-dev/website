<?php

namespace App\Livewire;

use App\Models\Document;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Livewire\Component;

class DocumentsPage extends Component
{
    public function render(): View
    {
        $documents = Schema::hasTable('documents')
            ? Document::query()
                ->where('is_published', true)
                ->latest()
                ->get()
            : new Collection;

        return view('livewire.documents-page', [
            'documents' => $documents,
        ])->layout('layouts.app', [
            'pageTitle' => 'Documenti & Slide',
            'metaDescription' => 'Raccolta documenti e slide della community Salento.dev.',
        ]);
    }
}
