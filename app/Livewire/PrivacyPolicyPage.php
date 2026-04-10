<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\View\View;
use Livewire\Component;

final class PrivacyPolicyPage extends Component
{
    public function render(): View
    {
        return view('livewire.privacy-policy-page')
            ->layout('layouts.app', [
                'pageTitle' => 'Privacy Policy',
                'metaDescription' => 'Informativa sul trattamento dei dati personali ai sensi del Regolamento UE 2016/679 (GDPR) per il sito salento.dev.',
            ]);
    }
}
