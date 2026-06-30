# Contribuire a salento.dev

Grazie per voler contribuire al sito della community! Questo file è una **guida rapida**; la documentazione completa è nella cartella [`docs/`](docs/README.md).

## In breve

1. **Branch dedicati da `main`** — mai commit diretti su `main`. Nome `tipo/descrizione` (`feat/`, `fix/`, `docs/`, `refactor/`, `chore/`).
2. **Parti da una issue** — idealmente ogni branch nasce da una issue; la Pull Request la chiude con `Closes #N`.
3. **Prima della PR**: esegui Pint e i test.
   ```bash
   ./vendor/bin/sail bin pint --dirty --format agent
   ./vendor/bin/sail artisan test --compact
   ```
4. **Apri la PR** verso `main` usando il template proposto.

## Setup rapido

```bash
git clone git@github.com:salento-dev/website.git
cd website
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail npm install && ./vendor/bin/sail npm run build
```

Dettagli e troubleshooting: [`docs/04_sviluppo_e_qualita/01_ambiente_locale.md`](docs/04_sviluppo_e_qualita/01_ambiente_locale.md).

## Issue e label

- Apri le issue con uno dei **template** (Bug, Funzionalità, Cantiere delle idee): applicano già la label corretta.
- **Usa solo le label esistenti** (`gh label list -R salento-dev/website`); per proporne di nuove, discutile prima.
- Tutto il ciclo di vita di issue/label/milestone è descritto in [`docs/04_sviluppo_e_qualita/05_gestione_task_e_label.md`](docs/04_sviluppo_e_qualita/05_gestione_task_e_label.md).

## Guide complete (in `docs/`)

| Tema | Documento |
|------|-----------|
| Ambiente di sviluppo locale | [01_ambiente_locale](docs/04_sviluppo_e_qualita/01_ambiente_locale.md) |
| Workflow Git, code style, checklist PR | [03_contribuire](docs/04_sviluppo_e_qualita/03_contribuire.md) |
| Gestione task e label | [05_gestione_task_e_label](docs/04_sviluppo_e_qualita/05_gestione_task_e_label.md) |
| Testing | [02_testing](docs/04_sviluppo_e_qualita/02_testing.md) |
| Architettura | [01_architettura](docs/02_architettura/01_architettura.md) |
| Decisioni architetturali (ADR) | [07_decisioni_architetturali](docs/07_decisioni_architetturali/README.md) |

## Code style in sintesi

- Formattazione con **Laravel Pint** (preset del progetto).
- Tipi di ritorno e parametri **espliciti**; constructor property promotion; graffe sempre, anche per i corpi a riga singola.
- Pagine pubbliche come **componenti Livewire**; admin con **Filament Resources**; link via **nome della route**.

Per i dettagli completi vedi [`docs/04_sviluppo_e_qualita/03_contribuire.md`](docs/04_sviluppo_e_qualita/03_contribuire.md).
