# Documentazione — salento.dev

Documentazione tecnica e funzionale del sito istituzionale di **salento.dev**, la community di sviluppatori del Salento.

Il sito è un'applicazione **Laravel 12 + Livewire 3 + Filament 5** che gestisce eventi, risorse, storie e partner della community, con un pannello di amministrazione e una sezione pubblica animata.

> **Stato della documentazione:** allineata al codice sorgente del branch `main` al momento della stesura. Dove il codice diverge dalle indicazioni storiche (`CLAUDE.md`, `.claude/design.md`) la presente documentazione fa fede e segnala la discrepanza.

---

## Indice

La documentazione è organizzata in **5 aree tematiche** (cartelle numerate). All'interno di ogni cartella i file seguono lo schema `NN_nome.md`.

### 📁 `01_analisi_funzionale/`
| File | Contenuto |
|------|-----------|
| [01_panoramica](./01_analisi_funzionale/01_panoramica.md) | Cos'è salento.dev, obiettivi, stack tecnologico, dominio funzionale |
| [02_funzionalita](./01_analisi_funzionale/02_funzionalita.md) | Documentazione funzionale per dominio (eventi, risorse, storie, partner, analytics) |

### 📁 `02_architettura/`
| File | Contenuto |
|------|-----------|
| [01_architettura](./02_architettura/01_architettura.md) | Architettura applicativa, ciclo di vita di una richiesta, struttura delle directory |
| [02_database](./02_architettura/02_database.md) | Schema completo, diagramma ER, tabelle, pivot, migrazioni |
| [03_modelli](./02_architettura/03_modelli.md) | Attributi, cast, relazioni, accessor e logica di dominio per ogni modello |

### 📁 `03_codice/`
| File | Contenuto |
|------|-----------|
| [01_pagine_pubbliche](./03_codice/01_pagine_pubbliche.md) | Routing, componenti Livewire, layout, tracking visite |
| [02_pannello_admin](./03_codice/02_pannello_admin.md) | Panel, risorse, form, tabelle, widget, autenticazione |
| [03_design_system_frontend](./03_codice/03_design_system_frontend.md) | Token Tailwind, utility CSS, componenti Blade, JavaScript, build |

### 📁 `04_sviluppo_e_qualita/`
| File | Contenuto |
|------|-----------|
| [01_ambiente_locale](./04_sviluppo_e_qualita/01_ambiente_locale.md) | Prerequisiti, setup con Laravel Sail, comandi, troubleshooting |
| [02_testing](./04_sviluppo_e_qualita/02_testing.md) | Configurazione PHPUnit, test esistenti, come scrivere ed eseguire test |
| [03_contribuire](./04_sviluppo_e_qualita/03_contribuire.md) | Workflow Git, branch, Pull Request, code style, convenzioni |
| [04_note_e_debito_tecnico](./04_sviluppo_e_qualita/04_note_e_debito_tecnico.md) | Incongruenze note, lazy-load non protetti, sicurezza, allineamenti da fare |

### 📁 `05_community_e_strategia/`
| File | Contenuto |
|------|-----------|
| [01_attivita_e_strategia](./05_community_e_strategia/01_attivita_e_strategia.md) | Attività svolte, nuovi format, apertura ad altre associazioni, uso strategico del sito |
| [02_analisi_competitor](./05_community_e_strategia/02_analisi_competitor.md) | Peer locali (Puglia), benchmark nazionali, confronto piattaforme, posizionamento |

---

## Per iniziare in fretta

- **Vuoi far girare il sito in locale?** → [03 · Ambiente di sviluppo locale](./04_sviluppo_e_qualita/01_ambiente_locale.md)
- **Vuoi capire come è strutturato il codice?** → [02 · Architettura](./02_architettura/01_architettura.md)
- **Devi aprire la tua prima Pull Request?** → [11 · Contribuire](./04_sviluppo_e_qualita/03_contribuire.md)
- **Cerchi lo schema del database?** → [04 · Database](./02_architettura/02_database.md)
- **Ti interessa la crescita della community e i competitor?** → [13 · Attività e strategia](./05_community_e_strategia/01_attivita_e_strategia.md) · [14 · Analisi dei competitor](./05_community_e_strategia/02_analisi_competitor.md)

---

## Convenzioni della documentazione

- I **termini tecnici** e gli identificatori di codice restano in inglese (`Event`, `BelongsToMany`, `slug`, ...).
- I riferimenti al codice usano la notazione `percorso/file.php:riga` quando utile alla navigazione.
- I **diagrammi** sono in [Mermaid](https://mermaid.js.org/): GitHub li renderizza nativamente nei file Markdown.
- I blocchi **⚠️ Nota** segnalano incongruenze o aspetti da verificare; sono raccolti anche nel documento [12](./04_sviluppo_e_qualita/04_note_e_debito_tecnico.md).
