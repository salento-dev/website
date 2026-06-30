# Documentazione — salento.dev

Documentazione tecnica e funzionale del sito istituzionale di **salento.dev**, la community di sviluppatori del Salento.

Il sito è un'applicazione **Laravel 12 + Livewire 3 + Filament 5** che gestisce eventi, risorse, storie e partner della community, con un pannello di amministrazione e una sezione pubblica animata.

> **Stato della documentazione:** allineata al codice sorgente del branch `main` al momento della stesura. Dove il codice diverge dalle indicazioni storiche (`CLAUDE.md`, `.claude/design.md`) la presente documentazione fa fede e segnala la discrepanza.

---

## Indice

| # | Documento | Contenuto |
|---|-----------|-----------|
| — | [README](README.md) | Questo indice |
| 01 | [Panoramica del progetto](01-panoramica.md) | Cos'è salento.dev, obiettivi, stack tecnologico, dominio funzionale |
| 02 | [Architettura](02-architettura.md) | Architettura applicativa, ciclo di vita di una richiesta, struttura delle directory |
| 03 | [Ambiente di sviluppo locale](03-ambiente-locale.md) | Prerequisiti, setup con Laravel Sail, comandi, troubleshooting |
| 04 | [Database](04-database.md) | Schema completo, diagramma ER, tabelle, pivot, migrazioni |
| 05 | [Modelli Eloquent](05-modelli.md) | Attributi, cast, relazioni, accessor e logica di dominio per ogni modello |
| 06 | [Pagine pubbliche (Livewire)](06-pagine-pubbliche.md) | Routing, componenti Livewire, layout, tracking visite |
| 07 | [Pannello admin (Filament)](07-pannello-admin.md) | Panel, risorse, form, tabelle, widget, autenticazione |
| 08 | [Design system & frontend](08-design-system-frontend.md) | Token Tailwind, utility CSS, componenti Blade, JavaScript, build |
| 09 | [Funzionalità](09-funzionalita.md) | Documentazione funzionale per dominio (eventi, risorse, storie, partner, analytics) |
| 10 | [Testing](10-testing.md) | Configurazione PHPUnit, test esistenti, come scrivere ed eseguire test |
| 11 | [Contribuire](11-contribuire.md) | Workflow Git, branch, Pull Request, code style, convenzioni |
| 12 | [Note e debito tecnico](12-note-e-debito-tecnico.md) | Incongruenze note, lazy-load non protetti, sicurezza, allineamenti da fare |
| 13 | [Attività e strategia](13-attivita-e-strategia.md) | Attività svolte, nuovi format, apertura ad altre associazioni, uso strategico del sito |
| 14 | [Analisi dei competitor](14-analisi-competitor.md) | Peer locali (Puglia), benchmark nazionali, confronto piattaforme, posizionamento |

---

## Per iniziare in fretta

- **Vuoi far girare il sito in locale?** → [03 · Ambiente di sviluppo locale](03-ambiente-locale.md)
- **Vuoi capire come è strutturato il codice?** → [02 · Architettura](02-architettura.md)
- **Devi aprire la tua prima Pull Request?** → [11 · Contribuire](11-contribuire.md)
- **Cerchi lo schema del database?** → [04 · Database](04-database.md)
- **Ti interessa la crescita della community e i competitor?** → [13 · Attività e strategia](13-attivita-e-strategia.md) · [14 · Analisi dei competitor](14-analisi-competitor.md)

---

## Convenzioni della documentazione

- I **termini tecnici** e gli identificatori di codice restano in inglese (`Event`, `BelongsToMany`, `slug`, ...).
- I riferimenti al codice usano la notazione `percorso/file.php:riga` quando utile alla navigazione.
- I **diagrammi** sono in [Mermaid](https://mermaid.js.org/): GitHub li renderizza nativamente nei file Markdown.
- I blocchi **⚠️ Nota** segnalano incongruenze o aspetti da verificare; sono raccolti anche nel documento [12](12-note-e-debito-tecnico.md).
