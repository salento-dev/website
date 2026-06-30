# ADR-0001 · Pagine pubbliche come componenti Livewire full-page

- **Stato:** Accettata
- **Data:** 2026-06-30 (retroattiva)

## Contesto

Il frontend pubblico deve gestire interattività non banale (ricerca con debounce, filtri, ordinamento, paginazione) su eventi, risorse e storie. Il team lavora prevalentemente in PHP/Laravel e si vuole evitare di introdurre un layer API separato o un framework JS frontend dedicato. Livewire 4 consente di collegare un URL direttamente a un componente full-page, che riceve i parametri di route in `mount()` e supporta nativamente il route model binding.

## Decisione

Adottiamo i **componenti Livewire full-page** come unità delle pagine pubbliche: ogni rotta in `routes/web.php` punta direttamente a un componente in `app/Livewire/` (es. `EventsPage`, `EventDetailPage`), che gestisce sia la logica/stato sia il rendering della view. Non si usano controller classici per il dominio pubblico.

## Conseguenze

**Positive**
- Logica, stato e template **co-localizzati** in un unico componente; nessun boilerplate controller + view.
- **Reattività nativa** (ricerca/filtri/paginazione) senza endpoint API aggiuntivi.
- Le proprietà pubbliche **sono** lo stato della pagina; niente passaggio dati controller→view.
- Il **route model binding** (`{event:slug}`) funziona come nei controller.
- **Uniformità**: tutte le pagine pubbliche seguono lo stesso pattern.

**Negative / trade-off**
- **Overhead** per pagine puramente statiche (round-trip Livewire non necessari) — per questo `/cosa-facciamo` resta una `Route::view`.
- **Deviazione dal MVC** classico: curva di apprendimento iniziale.
- Rischio "**God Component**" se un componente accumula troppe responsabilità.
- Ciclo di vita Livewire (mount→hydrate→update→dehydrate) aggiunge strati da comprendere in debug.

## Alternative considerate

- **Controller + Blade** (standard Laravel), con Livewire solo per widget interattivi → più separazione MVC ma duplicazione di pattern.
- **Inertia.js + Vue/React** → adatto a interattività molto elevata, ma richiede competenze frontend separate.
- **Approccio ibrido**: controller per le statiche, Livewire full-page per le pagine con ricerca/filtri (di fatto adottato per `/cosa-facciamo`).

## Fonti

- [Livewire 4 — Pages](https://livewire.laravel.com/docs/4.x/pages)
- [Laracasts — Full-page components vs Controller](https://laracasts.com/discuss/channels/livewire/livewire-full-page-components-vs-controller)
- [Laravel Daily — Full-page components replace controllers](https://laraveldaily.com/lesson/livewire-3/full-page-components-replace-laravel-controllers)

> Dettagli implementativi: [Pagine pubbliche](../03_codice/01_pagine_pubbliche.md) · [Architettura](../02_architettura/01_architettura.md).
