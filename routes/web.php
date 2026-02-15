<?php

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

Route::view('/eventi', 'pages.static', [
    'pageTitle' => 'Eventi',
    'metaDescription' => 'Eventi',
])->name('events');

Route::view('/documenti-slide', 'pages.static', [
    'pageTitle' => 'Documenti & Slide',
    'metaDescription' => 'Documenti e Slide',
])->name('documents-slides');

Route::view('/contatti', 'pages.static', [
    'pageTitle' => 'Contatti',
    'metaDescription' => 'Contatti',
])->name('contacts');
