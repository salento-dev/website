# 03 · Ambiente di sviluppo locale

Il progetto usa **Laravel Sail** (Docker) per l'ambiente locale. Tutti i comandi `php`, `artisan`, `composer`, `npm` vanno eseguiti **tramite Sail**.

---

## Prerequisiti

| Strumento | Note |
|-----------|------|
| **Docker** + **Docker Compose v2** | Indispensabile (Sail gira in container). |
| **Git** | Per clonare e contribuire. |
| Node.js / npm | Opzionale in locale: la build può girare in Sail. Utile per `npm install` fuori container. |
| PHP / Composer in locale | **Non necessari**: vedi più sotto il bootstrap via container. |

I servizi definiti in `compose.yaml`:

- **`laravel.test`** — immagine `sail-8.3/app` (PHP 8.3), espone le porte **80** (HTTP) e **5173** (Vite).
- **`mysql`** — `mysql:8.4`, espone la porta **3306** del container (mappata sull'host via `FORWARD_DB_PORT`).

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

Configurare le variabili per MySQL/Sail (il `.env.example` di default punta a SQLite):

```dotenv
APP_NAME="salento.dev"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql          # nome del servizio Docker, NON 127.0.0.1
DB_PORT=3306
DB_DATABASE=salento
DB_USERNAME=sail
DB_PASSWORD=password

# Porta MySQL esposta sull'host (cambiala se la 3306 è occupata da un altro container)
FORWARD_DB_PORT=3306
```

> `DB_HOST=mysql` perché l'app comunica con il database **all'interno della rete Docker**. La porta `FORWARD_DB_PORT` serve solo per connettersi al DB dall'host (es. con un client GUI).

### 3. Installare le dipendenze PHP (`vendor/`)

Sail è uno script in `vendor/bin/sail`: per usarlo serve già `vendor/`. Se **non hai PHP/Composer in locale**, fai il bootstrap con un container Composer usa-e-getta:

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-req=ext-intl
```

> `--ignore-platform-req=ext-intl` serve **solo** in questo container di bootstrap (privo dell'estensione `intl` richiesta da Filament). L'immagine runtime di Sail include `intl`, quindi il sito funziona regolarmente.

Se hai Composer in locale: `composer install`.

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

Il seeding popola autori, categorie, partner, eventi e una storia reali e crea l'utente admin (vedi sotto).

### 7. Build del frontend

```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

Il sito è ora raggiungibile su **http://localhost** e il pannello admin su **http://localhost/admin**.

> In alternativa ai passi 3–7, dopo aver creato `.env` e installato `vendor/`, esiste lo script aggregato `./vendor/bin/sail composer run setup` (install, copia `.env`, key:generate, migrate --force, npm install, npm run build).

---

## Utente amministratore

Il `DatabaseSeeder` crea automaticamente un utente Filament:

- **Email:** `admin@salento.dev`
- **Password:** `password123`

> ⚠️ Credenziali di default solo per sviluppo. In produzione vanno cambiate (vedi [12 · Note e debito tecnico](12-note-e-debito-tecnico.md)).

Per crearne uno manualmente:

```bash
./vendor/bin/sail artisan make:filament-user
```

---

## Comandi principali

```bash
# Container
./vendor/bin/sail up -d          # avvia
./vendor/bin/sail down           # ferma
./vendor/bin/sail up -d --build  # ricostruisce le immagini

# Artisan
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan migrate:fresh --seed
./vendor/bin/sail artisan tinker

# Sviluppo "tutto in uno" (server + queue + log + vite, via concurrently)
./vendor/bin/sail composer run dev

# Frontend
./vendor/bin/sail npm run dev     # Vite con hot-reload
./vendor/bin/sail npm run build   # build di produzione

# Test
./vendor/bin/sail artisan test --compact
./vendor/bin/sail artisan test --compact --filter=test_partners_page_loads_successfully

# Code style (eseguire SEMPRE prima di finalizzare le modifiche PHP)
./vendor/bin/sail bin pint --dirty --format agent
```

---

## Sviluppo frontend: build vs hot-reload

- Gli asset sono inclusi nel layout tramite `@vite([...])` **solo se** esiste `public/build/manifest.json` (build) **oppure** `public/hot` (dev server attivo) — guardia `@if (file_exists(...))` in `layouts/app.blade.php:19-21`.
- Per vedere le modifiche a CSS/JS in tempo reale: `./vendor/bin/sail npm run dev`.
- Per un ambiente "statico": `./vendor/bin/sail npm run build`.

Se una modifica frontend **non compare**, quasi sempre manca la build o il dev server.

---

## Troubleshooting

| Sintomo | Causa | Soluzione |
|---------|-------|-----------|
| `Bind for 0.0.0.0:3306 failed: port is already allocated` | Un altro container/servizio MySQL occupa la 3306 sull'host. | Imposta `FORWARD_DB_PORT=3307` (o altra porta libera) in `.env` e rilancia `sail up -d`. La connessione interna app→mysql non cambia. |
| `composer install` fallisce con `ext-intl ... is missing` | Il container di bootstrap Composer non ha l'estensione `intl`. | Aggiungi `--ignore-platform-req=ext-intl` (solo per il bootstrap). |
| `Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest` | Manca la build degli asset. | `./vendor/bin/sail npm run build` (o `npm run dev`). |
| Il sito risponde ma senza stili | Stesso problema: manifest assente o dev server non attivo. | Vedi sopra. |
| `SQLSTATE[HY000] [2002] Connection refused` | MySQL non ancora pronto o `DB_HOST` errato. | Verifica `DB_HOST=mysql`; attendi che il container `mysql` sia `healthy`. |
| Permessi su `storage/` o `bootstrap/cache/` | UID container ≠ UID host. | Usa l'opzione `-u "$(id -u):$(id -g)"` nei container usa-e-getta; con Sail è già gestito da `WWWUSER`. |

---

## Verifica rapida

```bash
# Stato container
docker ps --filter name=website

# L'app risponde?
curl -s -o /dev/null -w "Home: %{http_code}\n" http://localhost/
curl -s -o /dev/null -w "Admin: %{http_code}\n" http://localhost/admin   # atteso 302 (redirect al login)
```
