# Salento.dev · website

Il sito della community **[Salento.dev](https://salento.dev)**: developer del Salento che si incontrano dal vivo, a rotazione nei comuni del territorio, e restano in contatto nel gruppo WhatsApp.

Il sito raccoglie **eventi**, **risorse** (slide, link, materiali dei talk), **storie** dei membri e **partner**. Lo staff gestisce i contenuti da un pannello admin, senza toccare codice.

## Stack

| Layer | Tecnologia |
|---|---|
| Backend | PHP 8.3 · Laravel 12 · Livewire |
| Admin | Filament 5 |
| Frontend | Tailwind CSS 4 · Alpine.js · GSAP |
| Build | Vite |
| Database | MySQL 8.4 |
| Ambiente locale | Laravel Sail (Docker) |
| Test e stile | PHPUnit · Laravel Pint |

Le pagine pubbliche sono componenti Livewire full-page; il pannello `/admin` usa le Resource di Filament. Le analitiche sono interne, senza tracker di terze parti.

## Avvio in locale

Serve solo **Docker** (con Compose v2): PHP, Composer e Node girano nei container di Sail.

```bash
git clone git@github.com:salento-dev/website.git
cd website
cp .env.example .env            # poi imposta DB_CONNECTION=mysql e DB_HOST=mysql (vedi la guida)

# installa vendor/ con un container usa-e-getta, se non hai Composer in locale
docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" -w /var/www/html \
    laravelsail/php83-composer:latest composer install --ignore-platform-req=ext-intl

./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail npm install && ./vendor/bin/sail npm run build
```

Il sito è su http://localhost e l'admin su http://localhost/admin. Il seeder crea l'utente admin con le credenziali `ADMIN_EMAIL` / `ADMIN_PASSWORD` del `.env`; se lasci vuota la password, ne genera una casuale e la mostra nel terminale.

Guida completa, variabili d'ambiente e troubleshooting: [`docs/04_sviluppo_e_qualita/01_ambiente_locale.md`](docs/04_sviluppo_e_qualita/01_ambiente_locale.md).

### Comandi utili

```bash
./vendor/bin/sail composer run dev                     # server, coda, log e Vite insieme
./vendor/bin/sail artisan test --compact               # test
./vendor/bin/sail bin pint --dirty                     # formattazione
```

## Documentazione

La documentazione (in italiano) è in [`docs/`](docs/README.md): analisi funzionale, architettura, codice, sviluppo e qualità, decisioni architetturali (ADR). Le regole del design system sono in [`DESIGN.md`](DESIGN.md).

## Contribuire

I contributi sono benvenuti, anche piccoli. Parti da [`CONTRIBUTING.md`](CONTRIBUTING.md) e dalle [issue aperte](https://github.com/salento-dev/website/issues): quelle con label `good first issue` sono un buon punto di partenza.

Partecipando ti impegni a rispettare il [Codice di condotta](CODE_OF_CONDUCT.md). Per segnalare una vulnerabilità **non aprire una issue pubblica**: segui [`SECURITY.md`](SECURITY.md).

## Brand

Logo, colori, tipografia e regole d'uso stanno nel repository [`salento-dev/brand`](https://github.com/salento-dev/brand) e nel [brand book](https://salento-dev.github.io/brand/).

## Licenza

- **Codice:** [MIT](LICENSE).
- **Logo, wordmark e nome «Salento.dev»:** tutti i diritti riservati, uso solo previa autorizzazione.
- **Foto, loghi dei partner e contenuti di terzi** nei seeder: dei rispettivi titolari, non concessi in licenza.
- **Documentazione:** CC BY 4.0. **Font:** SIL OFL 1.1.

Dettagli in [`LICENSE-ASSETS.md`](LICENSE-ASSETS.md). Se riusi il codice per un altro progetto, sostituisci logo e nome con i tuoi.
