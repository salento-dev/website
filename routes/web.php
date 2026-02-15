<?php

use App\Livewire\DocumentsPage;
use App\Livewire\EventsPage;
use App\Livewire\HomePage;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class)->name('home');

Route::view('/chi-siamo', 'pages.static', [
    'pageTitle' => 'Chi siamo',
    'metaDescription' => 'Chi siamo',
])->name('about');

Route::view('/cosa-facciamo', 'pages.static', [
    'pageTitle' => 'Cosa facciamo',
    'metaDescription' => 'Cosa facciamo',
])->name('services');

Route::get('/eventi', EventsPage::class)->name('events');

Route::get('/documenti-slide', DocumentsPage::class)->name('documents-slides');

Route::view('/contatti', 'pages.static', [
    'pageTitle' => 'Contatti',
    'metaDescription' => 'Contatti',
])->name('contacts');
