# ADR-0003 · Routing basato su slug con route model binding

- **Stato:** Accettata
- **Data:** 2026-06-30 (retroattiva)

## Contesto

Le pagine di dettaglio di eventi e storie hanno URL pubbliche, condivisibili e indicizzabili. Esporre la chiave primaria numerica (`/events/42`) è poco leggibile, poco SEO-friendly e rivela la sequenza dei record. Laravel supporta nativamente il binding del modello su una colonna univoca con la sintassi `{model:column}`.

## Decisione

Adottiamo il **routing basato su slug** con route model binding: `/events/{event:slug}` e `/stories/{story:slug}`. Le colonne `slug` sono `UNIQUE`. I link si generano sempre con i **nomi di route** (`route('events.show', $event)`).

## Conseguenze

**Positive**
- **SEO e leggibilità**: URL descrittive, raccomandate esplicitamente da Google (parole, non ID; trattini come separatori).
- **Sicurezza**: non espone la chiave primaria né la sequenzialità dei record.
- **Brand & condivisibilità**: link parlanti e copiabili.
- **Zero configurazione extra**: il binding è gestito da Laravel; funziona anche nei componenti Livewire via `mount()`.

**Negative / trade-off**
- **Unicità** dello slug da garantire (colonna `UNIQUE` + generazione con suffisso in caso di collisione).
- **Immutabilità**: cambiare il titolo non dovrebbe cambiare lo slug; se cambia, serve un redirect 301 / canonical per non rompere i link.
- **Costo query**: il lookup è su `slug`, non sulla PK → serve un **indice** su `slug` (presente, dato il vincolo unique).

## Alternative considerate

- **ID numerico** (`/events/{event}`): default, zero logica, ma pessimo per SEO/leggibilità.
- **ID + slug** (`/events/42-laravel-meetup`): lookup veloce per ID con slug decorativo; più complesso.
- **UUID/ULID**: opaco e non sequenziale, ma nessun beneficio SEO.

## Fonti

- [Google Search Central — URL structure best practices](https://developers.google.com/search/docs/crawling-indexing/url-structure)
- [Laravel — Route model binding](https://laravel.com/docs/12.x/routing#route-model-binding)
- [hafiz.dev — Route model binding best practices](https://hafiz.dev/blog/laravel-route-model-binding-best-practices-for-cleaner-code)

> Dettagli: [Pagine pubbliche](../03_codice/01_pagine_pubbliche.md) · [Modelli](../02_architettura/03_modelli.md).
