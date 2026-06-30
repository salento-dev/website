# Decisioni architetturali (ADR)

Questa cartella raccoglie gli **Architecture Decision Records** (ADR) del progetto: documenti brevi che catturano una **singola decisione architetturale significativa** insieme al suo **contesto** e alle sue **conseguenze**.

Il formato (Michael Nygard, 2011; evoluzione [MADR](https://adr.github.io/madr/)) risolve due problemi: l'*accettazione cieca* (i nuovi membri non capiscono perché una scelta è stata fatta) e la *revoca cieca* (si smonta una decisione senza conoscerne le ragioni). La regola d'oro: documenti **piccoli e modulari** restano aggiornati, i documenti monolitici no.

## Convenzioni

- **Un ADR = una decisione.** File `NNNN_titolo.md` con numerazione progressiva a 4 cifre.
- **Gli ADR accettati non si modificano**: se una decisione cambia, si scrive un nuovo ADR che *sostituisce* il precedente, aggiornandone lo `Status` a `Superseded by ADR-XXXX`. Gli ADR deprecati **restano** come memoria storica.
- Struttura: **Stato · Contesto · Decisione · Conseguenze · Alternative · Fonti**.

### Stati

| Stato | Significato |
|-------|-------------|
| `Proposta` | In discussione, non ancora approvata |
| `Accettata` | Approvata e attiva |
| `Deprecata` | Non più raccomandata, ma ancora in uso |
| `Superseded by ADR-NNNN` | Sostituita da un ADR successivo |

> Gli ADR seguenti sono **retroattivi**: documentano decisioni già in vigore nel codice al momento della stesura (giugno 2026).

## Indice degli ADR

| # | Decisione | Stato |
|---|-----------|-------|
| [ADR-0001](0001_pagine_livewire_full_page.md) | Pagine pubbliche come componenti Livewire full-page | Accettata |
| [ADR-0002](0002_admin_filament.md) | Pannello admin con Filament (Server-Driven UI) | Accettata |
| [ADR-0003](0003_routing_slug.md) | Routing basato su slug con route model binding | Accettata |
| [ADR-0004](0004_analytics_middleware.md) | Analytics server-side nel middleware (privacy-first) | Accettata |
| [ADR-0005](0005_dark_theme.md) | Dark theme esclusivo | Accettata |
| [ADR-0006](0006_build_asset_versionati.md) | Asset di build versionati nel repository | Accettata (da rivalutare) |

## Riferimenti sul metodo

- [Documenting Architecture Decisions — M. Nygard (2011)](https://www.cognitect.com/blog/2011/11/15/documenting-architecture-decisions)
- [MADR — Markdown Any Decision Records](https://adr.github.io/madr/)
- [Martin Fowler — ArchitectureDecisionRecord](https://martinfowler.com/bliki/ArchitectureDecisionRecord.html)
