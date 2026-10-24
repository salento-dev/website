<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\View\View;
use Livewire\Component;

final class WhatWeDoPage extends Component
{
    public function render(): View
    {
        return view('livewire.what-we-do-page')
            ->layout('layouts.app', [
                'pageTitle' => 'Cosa facciamo',
                'metaDescription' => 'Meetup, workshop, AperiDev e occasioni di confronto: cosa fa la community di sviluppatori '.config('community.name').'.',
            ]);
    }
}
