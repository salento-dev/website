# 01 · Panoramica del progetto

## Cos'è salento.dev

**salento.dev** è una community di sviluppatori del Salento. Questo repository contiene il **sito web istituzionale** della community, che assolve a tre funzioni principali:

1. **Vetrina pubblica** — presenta la community, i suoi eventi, le risorse condivise, le storie dei membri e i partner.
2. **Gestione dei contenuti** — un pannello di amministrazione (Filament) consente allo staff di creare e curare eventi, risorse, storie, autori, partner e categorie.
3. **Analytics interne** — un sistema di tracciamento delle visite alimenta widget statistici nel pannello admin.

Il sito adotta un'estetica **dark/cyber-terminal** con animazioni curate (GSAP, Alpine.js) ed è interamente in lingua italiana per i contenuti rivolti all'utenza.

---

## Domini funzionali

Il sito ruota attorno a cinque entità di dominio:

| Dominio | Descrizione | Pagina pubblica | Gestione admin |
|---------|-------------|-----------------|----------------|
| **Eventi** | Meet-up, workshop, talk, hackathon della community. Hanno data, luogo, categorie, media, risorse e partner collegati. | `/events`, `/events/{slug}` | Sì |
| **Risorse** | Documenti e slide condivisi (link esterni o file caricati), attribuibili a uno o più autori. | `/resources` | Sì |
| **Storie** | Articoli/racconti della community (esperimenti, tutorial, riflessioni) con autori e protagonisti. | `/stories`, `/stories/{slug}` | Sì |
| **Partner** | Aziende e community partner/sponsor, con logo e sito. | `/partners` | Sì |
| **Autori** | Profili delle persone che firmano risorse e storie; arricchibili automaticamente dal profilo GitHub. | (mostrati nei dettagli) | Sì |

A supporto esistono le entità **EventCategory**, **StoryCategory**, **EventMedia**, **PageView** (analytics) e **User** (account admin).

> La tabella delle route nel `CLAUDE.md` storico **non** include ancora Storie e Autori: la sezione [06 · Pagine pubbliche](../03_codice/01_pagine_pubbliche.md) e [09 · Funzionalità](./02_funzionalita.md) riportano lo stato reale.

---

## Stack tecnologico

| Layer | Tecnologia | Versione |
|-------|------------|----------|
| Linguaggio | PHP | `^8.2` (testato anche su 8.3/8.5) |
| Framework backend | Laravel | `^12.0` |
| Componenti reattivi | Livewire | 3 (con Alpine.js bundlato) |
| Pannello admin | Filament | `^5.0` |
| CSS | Tailwind CSS | `^4.0` |
| Interattività | Alpine.js | `^3.15` (fornito da Livewire) |
| Animazioni | GSAP (+ Draggable, SplitText), Motion One, AutoAnimate | `gsap ^3.14` |
| Build tool | Vite | `^7.0` |
| Database | MySQL | 8.4 (via Docker/Sail) |
| Ambiente locale | Laravel Sail (Docker) | `^1` |
| Test | PHPUnit | 11 |
| Formatter | Laravel Pint | `^1` |
| Icone | `ublabs/blade-simple-icons` (social) + Heroicons (Filament) | — |

### Dipendenze Composer dirette

```
php                         ^8.2
filament/filament           ^5.0
laravel/framework           ^12.0
laravel/tinker              ^2.10.1
ublabs/blade-simple-icons   ^0.196.0
```

### Dipendenze npm principali

- **Runtime:** `alpinejs`, `gsap`, `@motionone/dom`, `@formkit/auto-animate`
- **Build/dev:** `tailwindcss ^4`, `@tailwindcss/vite`, `@tailwindcss/typography`, `laravel-vite-plugin`, `vite ^7`, `axios`, `concurrently`

---

## Pubblico e sezioni

- **Pubblico (anonimo):** sezione vetrina (home, eventi, risorse, storie, partner, pagine legali).
- **Staff/admin (autenticato):** pannello `/admin` per la gestione completa dei contenuti e le analitiche.

Per i dettagli funzionali completi vedi [09 · Funzionalità](./02_funzionalita.md).
