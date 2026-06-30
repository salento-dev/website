# 02 · Testing

Il progetto usa **PHPUnit 11** (`phpunit/phpunit ^11.5`). Tutti i test sono classi PHPUnit (non Pest). Questo documento descrive la struttura, la configurazione, come testare i componenti Livewire/Filament tipici di questo stack e una roadmap di copertura.

> Documenti correlati: per l'ambiente e i comandi vedi [Ambiente locale](./01_ambiente_locale.md); per il workflow di PR vedi [Contribuire](./03_contribuire.md); per lo stato della copertura vedi [Note e debito tecnico](./04_note_e_debito_tecnico.md).

---

## Struttura della suite

```
tests/
├── TestCase.php            # classe base astratta (estende il TestCase di Laravel)
├── Unit/
│   └── ExampleTest.php     # placeholder: assertTrue(true)
└── Feature/
    ├── ExampleTest.php     # smoke test: GET / → 200
    └── PartnersPageTest.php # GET /partners + filtro is_published
```

Due **testsuite** sono definite in `phpunit.xml`: **Unit** (`tests/Unit`) e **Feature** (`tests/Feature`). La distinzione idiomatica Laravel:

- **Unit test** — estendono `PHPUnit\Framework\TestCase` (come `tests/Unit/ExampleTest.php`): niente boot del framework, niente DB. Adatti a logica pura (accessor, value object, helper).
- **Feature test** — estendono `Tests\TestCase` (che estende `Illuminate\Foundation\Testing\TestCase`): bootano l'app, possono usare il container, le route, il DB. **La maggior parte dei test dovrebbe essere qui.**

`tests/TestCase.php` è una classe base **astratta e minimale**: nessun trait condiviso. Eventuali helper comuni (factory ricorrenti, autenticazione admin) andrebbero aggiunti qui.

---

## Configurazione (`phpunit.xml`) spiegata

```xml
bootstrap="vendor/autoload.php"   <!-- autoload Composer prima di ogni test -->
colors="true"                     <!-- output colorato -->
```

- **`<testsuites>`** — registra le due directory `tests/Unit` e `tests/Feature`.
- **`<source><include><directory>app</directory>`** — l'analisi di coverage considera **solo** `app/` (esclude config, vendor, ecc.).
- **`<php>`** — override d'ambiente attivi **solo durante i test**:

| Variabile | Valore | Perché |
|-----------|--------|--------|
| `APP_ENV` | `testing` | Attiva configurazioni di test e disabilita servizi esterni. |
| `DB_DATABASE` | `testing` | Punta al database `testing` creato dall'immagine MySQL di Sail (`create-testing-database.sh`). |
| `BCRYPT_ROUNDS` | `4` | Hashing rapido: i test non hanno bisogno di robustezza crittografica. |
| `CACHE_STORE` | `array` | Cache in-memory effimera per test isolati. |
| `SESSION_DRIVER` | `array` | Sessione in-memory (rilevante per testare la deduplica di `TrackPageView`). |
| `QUEUE_CONNECTION` | `sync` | I job vengono eseguiti in modo sincrono, senza worker. |
| `MAIL_MAILER` | `array` | Le mail non vengono inviate; possono essere asserite. |
| `BROADCAST_CONNECTION` | `null` | Nessun broadcasting durante i test. |
| `APP_MAINTENANCE_DRIVER` | `file` | Driver di manutenzione deterministico. |
| `PULSE_ENABLED` / `TELESCOPE_ENABLED` / `NIGHTWATCH_ENABLED` | `false` | Disattiva strumenti di osservabilità opzionali nei test. |

> Il **driver DB concreto** non è fissato in `phpunit.xml` (solo il nome `testing`): viene ereditato da `config/database.php`/`.env`. Con Sail si usa MySQL; l'immagine crea automaticamente il database `testing`.

---

## Strategie di test

### `RefreshDatabase`

I feature test che toccano il DB usano il trait `Illuminate\Foundation\Testing\RefreshDatabase` (come in `PartnersPageTest`): migra lo schema in una transazione e fa rollback a fine test, garantendo **isolamento** tra i casi.

```php
use Illuminate\Foundation\Testing\RefreshDatabase;

class PartnersPageTest extends TestCase
{
    use RefreshDatabase;
    // ...
}
```

### Factory e stati

Usare **sempre** le factory dei modelli invece di costruire record a mano (regola del progetto). Sono disponibili: `AuthorFactory`, `EventFactory`, `PartnerFactory`, `ResourceFactory`, `StoryCategoryFactory`, `StoryFactory`, `UserFactory`. Esempio dal test esistente:

```php
$published   = Partner::factory()->create(['is_published' => true]);
$unpublished = Partner::factory()->create(['is_published' => false]);
```

> ⚠️ La `ResourceFactory` è **disallineata** allo schema (genera i campi rimossi `slug`/`image_path`): usarla direttamente in un test fallirebbe. Vedi [Note e debito tecnico](./04_note_e_debito_tecnico.md). Prima di scrivere test sulle risorse, allineare la factory.

### Faker

Seguire la convenzione esistente (`fake()` nelle factory: es. `fake()->company()`, `fake()->boolean(85)`).

---

## Esempio attuale: `PartnersPageTest`

```php
public function test_partners_page_loads_successfully(): void
{
    $response = $this->get(route('partners'));
    $response->assertStatus(200);
}

public function test_partners_page_displays_published_partners(): void
{
    $published   = Partner::factory()->create(['is_published' => true]);
    $unpublished = Partner::factory()->create(['is_published' => false]);

    $this->get(route('partners'))
        ->assertStatus(200)
        ->assertSee($published->name)
        ->assertDontSee($unpublished->name);
}
```

Verifica sia il caricamento della pagina sia la regola di visibilità `is_published`.

---

## Testare componenti Livewire

Le pagine pubbliche sono **componenti Livewire full-page**: vanno testate con `Livewire::test(...)`. Esempi idiomatici per questo stack:

```php
use App\Livewire\EventsPage;
use App\Models\Event;
use Livewire\Livewire;

public function test_events_page_lists_only_published_upcoming_events(): void
{
    $visible = Event::factory()->create([
        'is_published' => true, 'work_in_progress' => false,
        'starts_at' => now()->addWeek(),
    ]);
    $hidden = Event::factory()->create(['is_published' => false]);

    Livewire::test(EventsPage::class)
        ->assertSee($visible->title)
        ->assertDontSee($hidden->title);
}

public function test_events_search_resets_pagination(): void
{
    Livewire::test(EventsPage::class)
        ->set('search', 'laravel')
        ->assertSet('search', 'laravel');
    // NB: la ricerca eventi è attualmente affetta dal bug "description" (vedi debito tecnico)
}
```

Per il **dettaglio** (con route-model binding e `abort_unless`), si verifica anche il 404:

```php
public function test_unpublished_event_detail_returns_404(): void
{
    $event = Event::factory()->create(['is_published' => false]);
    $this->get(route('events.show', $event))->assertNotFound();
}
```

---

## Testare il pannello Filament

Filament è costruito su Livewire: i test usano `Livewire::test()` sulle pagine della Resource e richiedono **autenticazione** preventiva (`actingAs`). Schema tipico:

```php
use App\Filament\Resources\Partners\Pages\CreatePartner;
use App\Filament\Resources\Partners\Pages\ListPartners;
use App\Models\User;
use Livewire\Livewire;

protected function setUp(): void
{
    parent::setUp();
    $this->actingAs(User::factory()->create()); // canAccessPanel() == true per ogni utente
}

public function test_admin_can_create_partner(): void
{
    Livewire::test(CreatePartner::class)
        ->fillForm(['name' => 'ACME', 'is_published' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('partners', ['name' => 'ACME']);
}

public function test_partners_table_lists_records(): void
{
    $partners = Partner::factory()->count(3)->create();
    Livewire::test(ListPartners::class)
        ->assertCanSeeTableRecords($partners);
}
```

> Suggerimento: poiché `canAccessPanel()` ritorna sempre `true`, autenticare un qualsiasi `User::factory()` è sufficiente. Quando verrà introdotta l'autorizzazione granulare, i test dovranno usare un utente con il ruolo adeguato.

---

## Testare l'analytics (`TrackPageView`)

Il middleware merita test dedicati perché incapsula logica non banale (deduplica, bot filtering, associazione polimorfica):

```php
public function test_visit_to_published_event_creates_pageview(): void
{
    $event = Event::factory()->create(['is_published' => true, 'work_in_progress' => false]);

    $this->get(route('events.show', $event))->assertOk();

    $this->assertDatabaseHas('page_views', [
        'viewable_type' => $event->getMorphClass(),
        'viewable_id'   => $event->getKey(),
    ]);
}

public function test_bot_user_agent_is_not_tracked(): void
{
    $this->withHeader('User-Agent', 'Googlebot/2.1')
        ->get('/')->assertOk();

    $this->assertDatabaseCount('page_views', 0);
}
```

> Con `SESSION_DRIVER=array` la deduplica a 30 minuti è verificabile entro la stessa richiesta/sessione di test.

---

## Eseguire i test

```bash
# Tutti
./vendor/bin/sail artisan test --compact

# Un singolo file
./vendor/bin/sail artisan test --compact tests/Feature/PartnersPageTest.php

# Per nome (consigliato dopo una modifica mirata)
./vendor/bin/sail artisan test --compact --filter=test_partners_page_displays_published_partners
```

Lo script Composer `test` esegue prima `artisan config:clear --ansi` e poi `artisan test`, evitando che config cache obsolete inquinino l'esecuzione.

---

## Linee guida per scrivere test

- Creare nuovi test con `./vendor/bin/sail artisan make:test --phpunit {Nome}` (aggiungere `--unit` per gli unit test). La maggior parte dovrebbe essere **feature test**.
- Usare le **factory** e i loro stati custom (es. `User::factory()->unverified()`).
- Coprire **happy path**, **percorsi di errore** e **casi limite** (404, liste vuote, filtri).
- Dopo aver modificato un test, eseguire **quel singolo test**; quando passa, valutare l'intera suite.
- **Non rimuovere** test esistenti senza accordo: sono parte del codice dell'applicazione.

---

## Roadmap di copertura per dominio

Stato attuale: **copertura minima** (smoke test su `/` e `/partners`). Priorità suggerite, per dominio scoperto:

| Dominio | Test mancanti prioritari |
|---------|--------------------------|
| **Eventi** | lista upcoming/past/WIP; 404 su dettaglio non pubblicato e WIP; ricerca (dopo fix `description`→`body`); `toEmbedUrl()` YouTube/Vimeo; accessor `read_time_minutes`, `local_starts_at`. |
| **Storie** | storia in evidenza; filtro categoria; 404 dettaglio non pubblicato; ricerca (dopo fix `author`); relazioni autori/protagonisti. |
| **Risorse** | (dopo allineamento factory) lista pubblicate; `resource_url` per `link` vs `file`; collegamento eventi/autori. |
| **Autori** | `avatar_url` a cascata; precedenza campi locali sugli accessor `display_*`; cache GitHub (mock di `Http`). |
| **Partner** | già coperto il filtro; aggiungere CTA mail e ordinamento. |
| **Analytics** | creazione `PageView` su evento/storia; esclusione bot/admin/livewire; deduplica 30 min; widget dashboard. |
| **Admin Filament** | create/edit/delete per ogni Resource; validazione form; visibilità per autorizzazione (quando introdotta). |

> Obiettivo: portare i domini critici (eventi, storie, analytics) a una copertura di test significativa prima del deploy pubblico, e aggiungere una **CI** che esegua Pint e la suite su ogni PR (oggi assente, vedi [Note e debito tecnico](./04_note_e_debito_tecnico.md)).
