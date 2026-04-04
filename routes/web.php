<?php

use App\Livewire\EventDetailPage;
use App\Livewire\EventsPage;
use App\Livewire\HomePage;
use App\Livewire\PartnersPage;
use App\Livewire\ResourcesPage;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class)->name('home');

Route::view('/cosa-facciamo', 'pages.static', [
    'pageTitle' => 'Cosa facciamo',
    'metaDescription' => 'Cosa facciamo',
])->name('services');

Route::get('/events', EventsPage::class)->name('events');
Route::get('/events/{event:slug}', EventDetailPage::class)->name('events.show');

Route::get('/resources', ResourcesPage::class)->name('resources');

Route::get('/partners', PartnersPage::class)->name('partners');
