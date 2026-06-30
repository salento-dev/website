# 10 · Testing

Il progetto usa **PHPUnit 11**. Tutti i test sono classi PHPUnit (non Pest).

---

## Configurazione (`phpunit.xml`)

- Bootstrap `vendor/autoload.php`, `colors="true"`.
- Due testsuite: **Unit** (`tests/Unit`) e **Feature** (`tests/Feature`).
- Coverage source: directory `app/`.
- Ambiente di test (override env):

| Variabile | Valore |
|-----------|--------|
| `APP_ENV` | `testing` |
| `DB_DATABASE` | `testing` |
| `CACHE_STORE` | `array` |
| `SESSION_DRIVER` | `array` |
| `QUEUE_CONNECTION` | `sync` |
| `MAIL_MAILER` | `array` |
| `BCRYPT_ROUNDS` | `4` |
| `BROADCAST_CONNECTION` | `null` |

> Il driver DB concreto non è fissato in `phpunit.xml` (solo il nome database `testing`): viene ereditato da `config/database.php`/`.env.testing`. L'immagine MySQL di Sail crea automaticamente un database `testing` (script `create-testing-database.sh`).

---

## `tests/TestCase.php`

Classe base astratta minimale che estende `Illuminate\Foundation\Testing\TestCase`. Nessun trait custom condiviso.

---

## Test esistenti

| File | Tipo | Cosa verifica |
|------|------|---------------|
| `tests/Feature/PartnersPageTest.php` | Feature (`RefreshDatabase`) | `GET /partners` → 200; mostra i partner pubblicati e **non** mostra quelli non pubblicati (verifica il filtro `is_published`). |
| `tests/Feature/ExampleTest.php` | Feature | `GET /` → 200. |
| `tests/Unit/ExampleTest.php` | Unit | Placeholder (`true === true`). |

**Esempio** (`PartnersPageTest`): crea via factory un partner pubblicato e uno no, poi asserisce `assertSee`/`assertDontSee` sui rispettivi nomi.

---

## Eseguire i test

```bash
# Tutti
./vendor/bin/sail artisan test --compact

# Un file
./vendor/bin/sail artisan test --compact tests/Feature/PartnersPageTest.php

# Per nome
./vendor/bin/sail artisan test --compact --filter=test_partners_page_displays_published_partners
```

Lo script Composer `test` esegue prima `artisan config:clear` poi `artisan test`.

---

## Linee guida per scrivere test

- Creare nuovi test con `./vendor/bin/sail artisan make:test --phpunit {Nome}` (aggiungere `--unit` per gli unit test). La maggior parte dovrebbe essere **feature test**.
- Usare le **factory** dei modelli (e i loro stati custom, es. `User::factory()->unverified()`).
- Per i componenti Livewire/Filament usare `Livewire::test(...)` / `livewire(...)`; autenticare prima di testare il pannello.
- Coprire happy path, percorsi di errore e casi limite.
- Faker: seguire la convenzione esistente (`fake()` / `$this->faker`).
- Dopo aver modificato un test, eseguire **quel singolo test**; quando passa, valutare l'esecuzione dell'intera suite.

> Stato attuale: la copertura è minima (smoke test su `/` e `/partners`). C'è ampio spazio per estendere i test su eventi, risorse, storie, tracking visite e risorse Filament.
