# 01 · Ambiente di sviluppo locale

Il progetto usa **Laravel Sail** (Docker) per l'ambiente locale. Tutti i comandi `php`, `artisan`, `composer`, `npm` vanno eseguiti **tramite Sail** (`./vendor/bin/sail ...`), così da girare dentro i container con le estensioni e le versioni corrette.

> Documenti correlati: per scrivere ed eseguire i test vedi [Testing](./02_testing.md); per il workflow di contribuzione vedi [Contribuire](./03_contribuire.md); per i punti di attenzione noti vedi [Note e debito tecnico](./04_note_e_debito_tecnico.md).

---

## Prerequisiti

| Strumento | Obbligatorio | Note |
|-----------|--------------|------|
| **Docker Engine** | Sì | Sail gira interamente in container Linux. |
| **Docker Compose v2** | Sì | Il file `compose.yaml` usa la sintassi Compose v2 (comando `docker compose`). |
| **Git** | Sì | Per clonare il repository e contribuire. |
| **Bash** (o shell POSIX) | Consigliato | Lo script `vendor/bin/sail` è una shell script. Su Windows usare WSL2. |
| Node.js / npm in locale | Opzionale | La build può girare in Sail; un `npm install` fuori container è utile solo per IDE/linter JS. |
| PHP / Composer in locale | **Non necessari** | Vedi più sotto il **bootstrap via container** usa-e-getta. |

### I servizi di `compose.yaml`

Il file `compose.yaml` definisce due servizi sulla rete bridge `sail`:

- **`laravel.test`** — costruito da `./vendor/laravel/sail/runtimes/8.3` (immagine `sail-8.3/app`, **PHP 8.3**, con `intl` e tutte le estensioni richieste da Filament). Espone:
  - porta **`${APP_PORT:-80}:80`** → l'app HTTP (default `http://localhost`);
  - porta **`${VITE_PORT:-5173}`** → il dev server Vite (HMR).
  - Variabili rilevanti: `WWWUSER` (UID host per i permessi), `XDEBUG_MODE`/`XDEBUG_CONFIG` (debug PHP, off di default), `LARAVEL_SAIL=1`.
  - Monta l'intero progetto in `/var/www/html` (`.:/var/www/html`) e dipende da `mysql`.
- **`mysql`** — immagine **`mysql:8.4`**. Espone **`${FORWARD_DB_PORT:-3306}:3306`** verso l'host. Crea il database applicativo (`MYSQL_DATABASE=${DB_DATABASE}`) e l'utente (`${DB_USERNAME}`/`${DB_PASSWORD}`). Monta lo script `create-testing-database.sh` che genera automaticamente un secondo database **`testing`** (usato dalla suite PHPUnit). Healthcheck via `mysqladmin ping`. I dati persistono nel volume `sail-mysql`.

> **Porte vs rete interna:** dentro la rete Docker l'app raggiunge il DB all'host **`mysql`** (nome del servizio) sulla porta 3306. La variabile `FORWARD_DB_PORT` serve **solo** a esporre il DB sull'host (es. per un client GUI): cambiarla non tocca la connessione app→mysql.

### Le variabili `.env` chiave

Il `.env.example` di default punta a **SQLite** (`DB_CONNECTION=sqlite`). Per l'ambiente Sail/MySQL vanno impostate le variabili DB (vedi sotto). Altre variabili rilevanti già presenti in `.env.example`:

| Variabile | Default `.env.example` | Note |
|-----------|------------------------|------|
| `APP_NAME` | `Laravel` | Impostare `"salento.dev"`. |
| `APP_URL` | `http://localhost` | Base URL usata da Vite e dai link assoluti. |
| `DB_CONNECTION` | `sqlite` | Per Sail impostare `mysql`. |
| `SESSION_DRIVER` | `database` | Le sessioni alimentano la deduplica delle visite (`TrackPageView`). |
| `QUEUE_CONNECTION` | `database` | Le code girano via `php artisan queue:listen` (incluso in `composer run dev`). |
| `CACHE_STORE` | `database` | La cache ospita anche i profili GitHub degli autori (24h). |
| `VITE_HMR_HOST` | *(vuoto)* | Impostare l'IP della macchina per l'HMR via LAN (`vite.config.js:17`). |

---

## Setup iniziale da zero

### 1. Clonare il repository

```bash
git clone git@github.com:salento-dev/website.git
cd website
```

### 2. Creare il file `.env`

```bash
cp .env.example .env
```

Configurare le variabili per MySQL/Sail (sovrascrivendo il default SQLite):

```dotenv
APP_NAME="salento.dev"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql          # nome del servizio Docker, NON 127.0.0.1
DB_PORT=3306
DB_DATABASE=salento
DB_USERNAME=sail
DB_PASSWORD=password

# Porta MySQL esposta sull'host (cambiala se la 3306 è già occupata)
FORWARD_DB_PORT=3306
```

> `DB_HOST=mysql` perché l'app comunica con il database **all'interno della rete Docker**.

### 3. Installare le dipendenze PHP (`vendor/`) — bootstrap senza PHP locale

Lo script `vendor/bin/sail` esiste **solo dopo** che `vendor/` è stato popolato: c'è quindi un problema dell'uovo e della gallina se non hai PHP/Composer in locale. Lo si risolve con un container Composer **usa-e-getta**:

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-req=ext-intl
```

> `--ignore-platform-req=ext-intl` serve **solo** in questo container di bootstrap (privo dell'estensione `intl` richiesta da Filament). L'immagine runtime di Sail include `intl`, quindi a regime il sito funziona senza alcun workaround.

Se hai Composer in locale, è sufficiente `composer install`.

### 4. Avviare i container

```bash
./vendor/bin/sail up -d --build
```

### 5. Generare la chiave applicativa

```bash
./vendor/bin/sail artisan key:generate
```

### 6. Migrazioni + dati di esempio

```bash
./vendor/bin/sail artisan migrate --seed
```

Il seeding (`DatabaseSeeder`) popola **autori, categorie eventi, partner, eventi, categorie storie e storie reali** tramite i seeder `Real*` e crea l'utente admin (vedi sotto). I seeder `EventSeeder`/`ResourceSeeder` basati su factory **non** sono invocati.

### 7. Build del frontend

```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

Il sito è ora su **http://localhost** e il pannello admin su **http://localhost/admin**.

### Scorciatoia: `composer run setup`

In alternativa ai passi 3–7, dopo aver creato `.env` e popolato `vendor/`, lo script aggregato esegue tutto in sequenza:

```bash
./vendor/bin/sail composer run setup
# composer install · copia .env · key:generate · migrate --force · npm install · npm run build
```

---

## Utente amministratore

Il `DatabaseSeeder` crea automaticamente un utente Filament invocando `make:filament-user`:

- **Email:** `admin@salento.dev`
- **Password:** `password123`

> ⚠️ Credenziali di default **solo per sviluppo**. In produzione vanno cambiate o disabilitate, e va introdotta un'autorizzazione granulare (oggi `canAccessPanel()` ritorna sempre `true`). Vedi [Note e debito tecnico](./04_note_e_debito_tecnico.md).

Per crearne uno manualmente:

```bash
./vendor/bin/sail artisan make:filament-user
```

---

## Comandi principali

```bash
# --- Container ---
./vendor/bin/sail up -d            # avvia in background
./vendor/bin/sail up -d --build    # ricostruisce le immagini
./vendor/bin/sail down             # ferma e rimuove i container
./vendor/bin/sail ps               # stato dei servizi

# --- Artisan ---
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan migrate:fresh --seed   # reset completo + seed
./vendor/bin/sail artisan tinker

# --- Sviluppo "tutto in uno" (server + queue + log + vite, via concurrently) ---
./vendor/bin/sail composer run dev

# --- Frontend ---
./vendor/bin/sail npm run dev      # Vite con hot-reload (HMR)
./vendor/bin/sail npm run build    # build di produzione

# --- Test ---
./vendor/bin/sail artisan test --compact
./vendor/bin/sail artisan test --compact --filter=test_partners_page_loads_successfully

# --- Code style (SEMPRE prima di finalizzare modifiche PHP) ---
./vendor/bin/sail bin pint --dirty --format agent
```

---

## Sviluppo frontend: build vs hot-reload

Gli asset vengono inclusi nel layout tramite `@vite([...])` **solo se** è disponibile uno dei due:

- `public/build/manifest.json` → prodotto da `npm run build` (ambiente "statico");
- `public/hot` → presente quando il **dev server Vite è attivo** (`npm run dev`), che abilita l'**HMR** (hot module replacement).

La guardia `@if (file_exists(...))` in `layouts/app.blade.php` evita la `ViteException` quando nessuno dei due è presente.

- Per vedere le modifiche a CSS/JS **in tempo reale**: `./vendor/bin/sail npm run dev`.
- Per un ambiente che non richiede il dev server: `./vendor/bin/sail npm run build`.
- Per l'HMR via LAN (es. test da telefono sulla stessa rete) impostare `VITE_HMR_HOST` con l'IP della macchina (`vite.config.js` legge `process.env.VITE_HMR_HOST`).

> Se una modifica frontend **non compare**, quasi sempre manca la build o il dev server: è la causa numero uno dei problemi di styling assente.

---

## Troubleshooting

| Sintomo | Causa | Soluzione |
|---------|-------|-----------|
| `Bind for 0.0.0.0:3306 failed: port is already allocated` | Un altro servizio/container MySQL occupa la 3306 sull'host. | Imposta `FORWARD_DB_PORT=3307` (o altra porta libera) in `.env` e rilancia `sail up -d`. La connessione interna app→mysql non cambia. |
| `Bind for 0.0.0.0:80 failed` | La porta 80 è già usata (es. altro web server). | Imposta `APP_PORT=8080` in `.env`; il sito sarà su `http://localhost:8080`. |
| `composer install` fallisce con `ext-intl ... is missing` | Il container di bootstrap Composer non ha l'estensione `intl`. | Aggiungi `--ignore-platform-req=ext-intl` (solo per il bootstrap; il runtime Sail ha `intl`). |
| `Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest` | Manca la build degli asset. | `./vendor/bin/sail npm run build` (oppure `npm run dev`). |
| Il sito risponde ma senza stili | Manifest assente o dev server non attivo. | Vedi riga sopra. |
| `SQLSTATE[HY000] [2002] Connection refused` | MySQL non ancora pronto oppure `DB_HOST` errato. | Verifica `DB_HOST=mysql`; attendi che il container `mysql` sia `healthy` (`sail ps`). |
| `SQLSTATE[HY000] [1045] Access denied` | Credenziali DB non coerenti tra `.env` e volume MySQL già inizializzato. | Allinea `DB_USERNAME`/`DB_PASSWORD`, oppure `sail down -v` per resettare il volume (cancella i dati). |
| Permessi su `storage/` o `bootstrap/cache/` | UID del container ≠ UID dell'host. | Con Sail è gestito da `WWWUSER`; nei container usa-e-getta usa `-u "$(id -u):$(id -g)"`. In caso, `sail artisan storage:link` e correggi i permessi. |
| Le immagini caricate non si vedono | Manca il symlink dello storage pubblico. | `./vendor/bin/sail artisan storage:link`. |
| HMR non aggiorna via LAN | `VITE_HMR_HOST` non impostato. | Imposta l'IP della macchina e riavvia `npm run dev`. |

---

## Verifica rapida

```bash
# Stato container
./vendor/bin/sail ps

# L'app risponde?
curl -s -o /dev/null -w "Home: %{http_code}\n"  http://localhost/
curl -s -o /dev/null -w "Admin: %{http_code}\n" http://localhost/admin   # atteso 302 (redirect al login)

# Endpoint di health di Laravel (configurato in bootstrap/app.php)
curl -s -o /dev/null -w "Up: %{http_code}\n" http://localhost/up
```
