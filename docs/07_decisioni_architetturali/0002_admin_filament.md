# ADR-0002 · Pannello admin con Filament (Server-Driven UI)

- **Stato:** Accettata
- **Data:** 2026-06-30 (retroattiva)

## Contesto

Serve un backoffice per gestire eventi, categorie, risorse, storie, autori, partner e per consultare le analytics. Costruire un'admin custom (controller + Blade + CSS) richiederebbe molto tempo di sviluppo e manutenzione. Filament è un framework **Server-Driven UI** per Laravel: l'interfaccia è definita in PHP tramite oggetti di configurazione (Resource, Schema, Table, Widget) ed è costruito su Livewire 4, Alpine.js e Tailwind 4 — lo stesso stack già in uso.

## Decisione

Adottiamo **Filament 5** per l'intero pannello di amministrazione (`/admin`), con il pattern a classi separate (`<Nome>Resource` + `Schemas/` + `Tables/` + `Pages/`), e la discovery automatica di Resources e Widgets.

## Conseguenze

**Positive**
- **Velocità di sviluppo**: un Resource CRUD completo (lista, form, filtri, relazioni) in minuti.
- Interfaccia definita in **PHP puro**: nessuna "JavaScript fatigue", accessibilità e UI coerenti di default.
- **Integrazione Laravel nativa** (Eloquent, Policy, Form Request, Jobs) senza adattatori.
- Ecosistema di **plugin** (ruoli, media, audit log, multi-tenancy).

**Negative / trade-off**
- **Framework dentro un framework** (Laravel → Livewire → Filament): più strati da debuggare.
- Filosofia **opinionata**: UI molto custom (drag&drop complessi, dataviz avanzate) richiede workaround.
- **Lock-in**: migrare altrove significa riscrivere l'admin.
- Terminologia specifica (Panel, Resource, Schema, Action, Widget) da apprendere.

## Alternative considerate

- **Admin custom** (controller + Blade): massima flessibilità, zero lock-in, ma 3–5× più tempo.
- **Backpack for Laravel**: alternativa CRUD, meno opinionata.
- **Laravel Nova**: admin ufficiale a pagamento; ottima integrazione ma licenza e minore flessibilità per UI complesse.

## Fonti

- [filamentphp.com](https://filamentphp.com/)
- [DEV.to — Filament, an honest review](https://dev.to/tonegabes/how-filament-saved-or-complicated-my-admin-panel-an-honest-review-156b)
- [Backpack — Best Laravel admin panel](https://backpackforlaravel.com/articles/basics/best-laravel-admin-panel)

> Dettagli implementativi: [Pannello admin](../03_codice/02_pannello_admin.md). Rischio di autorizzazione correlato: [Sicurezza & Privacy](../04_sviluppo_e_qualita/06_sicurezza_e_privacy.md).
