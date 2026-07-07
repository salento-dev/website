# ADR-0004 · Analytics server-side nel middleware (privacy-first)

- **Stato:** Accettata
- **Data:** 2026-06-30 (retroattiva)

## Contesto

Serve misurare il traffico (pagine viste, eventi/storie più popolari, sorgenti) senza i problemi di conformità di Google Analytics, classificato non conforme al GDPR in più paesi UE (Italia inclusa) per il trasferimento di dati personali — IP compresi — verso gli USA. Si vuole inoltre evitare cookie di profilazione e script di terze parti.

## Decisione

Adottiamo un'**analytics server-side** implementata nel middleware `TrackPageView` (gruppo `web`), che registra in `terminate()` un record `PageView` per le richieste idonee. Vengono salvati `path`, `url`, `referrer`, `user_agent` e un `viewable` polimorfico (Event/Story). **Non viene salvato l'indirizzo IP**, non si usano cookie di tracciamento, si filtrano i bot e si deduplica per sessione (30 minuti).

## Conseguenze

**Positive**
- **GDPR-friendly per design**: niente IP, niente cookie di profilazione, dati non direttamente identificativi → nessun cookie banner necessario (vedi [Sicurezza & Privacy](../04_sviluppo_e_qualita/06_sicurezza_e_privacy.md)).
- **Resistente agli ad-blocker** (nessun dominio di terze parti) e **zero impatto** sul frontend (nessuno script JS di tracking).
- **Dati propri**, nel database del progetto; nessuna dipendenza da servizi esterni.
- Più conservativo persino di Plausible/Fathom, che calcolano un hash temporaneo dell'IP: qui l'IP non viene proprio toccato.

**Negative / trade-off**
- **Meno granularità**: senza identificatore persistente non si tracciano sessioni multi-visita o funnel nel tempo.
- **Bot filtering manuale**: la lista di pattern user-agent va mantenuta.
- **Codice proprietario** da mantenere (a differenza di una soluzione SaaS).
- Nessun conversion tracking per campagne pubblicitarie esterne.

## Alternative considerate

- **Google Analytics 4 + consenso**: legale con banner conforme, ma peggiora la UX e riduce i dati per chi non consente.
- **Plausible / Fathom / Umami**: analytics cookieless mature; ottime ma SaaS o servizio self-hosted aggiuntivo da gestire.
- **Nessuna analytics**: scartata (serve misurare per migliorare).

## Fonti

- [Laravel Blog — Ditching Google Analytics for Fathom](https://blog.laravel.com/ditching-google-analytics-for-fathom)
- [Plausible — Data policy (no IP stored)](https://plausible.io/data-policy)
- [Garante Privacy — Linee guida cookie (2021)](https://www.garanteprivacy.it/home/docweb/-/docweb-display/docweb/9677876)

> Implementazione: [Pagine pubbliche → TrackPageView](../03_codice/01_pagine_pubbliche.md) · approfondimento normativo: [Sicurezza & Privacy](../04_sviluppo_e_qualita/06_sicurezza_e_privacy.md).
