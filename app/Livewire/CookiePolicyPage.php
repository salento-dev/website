<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\View\View;
use Livewire\Component;

final class CookiePolicyPage extends Component
{
    public function render(): View
    {
        return view('livewire.cookie-policy-page')
            ->layout('layouts.app', [
                'pageTitle' => 'Cookie Policy',
                'metaDescription' => 'Informativa sull\'uso dei cookie sul sito salento.dev ai sensi del Provvedimento del Garante Privacy e del GDPR.',
            ]);
    }
}
