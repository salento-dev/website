<?php

namespace App\Livewire;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Livewire\Component;

class PartnersPage extends Component
{
    public function render(): View
    {
        $partners = Schema::hasTable('partners')
            ? Partner::query()
                ->where('is_published', true)
                ->orderBy('name')
                ->get()
            : new Collection;

        return view('livewire.partners-page', [
            'partners' => $partners,
        ])->layout('layouts.app', [
            'pageTitle' => 'Partner',
            'metaDescription' => 'I nostri partner della comunità',
        ]);
    }
}
