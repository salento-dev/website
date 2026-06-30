# 01 · Stack backend — riferimenti e approfondimenti

Documento di riferimento sullo stack **backend** di salento.dev, basato su documentazione ufficiale e best practice version-specific (ricerca giugno 2026). Le fonti sono in fondo.

> **Versioni reali installate** (da `composer.lock`): PHP 8.2+ (runtime Sail 8.3/8.5), **Laravel 12**, **Livewire v4.2.4**, **Filament v5.6.0**, **PHPUnit 11**, Laravel Sail 1.x, Laravel Pint 1.x.
>
> ⚠️ L'header dello stack nel `CLAUDE.md` cita "Livewire 3", ma la versione effettivamente installata è **Livewire 4** (richiesta da Filament 5). Vedi [Note e debito tecnico](../04_sviluppo_e_qualita/04_note_e_debito_tecnico.md).

---

## Indice

1. [PHP 8.2+](#1-php-82)
2. [Laravel 12](#2-laravel-12)
3. [Livewire 4](#3-livewire-4)
4. [Filament 5](#4-filament-5)
5. [PHPUnit 11](#5-phpunit-11)
6. [Laravel Sail](#6-laravel-sail)
7. [Laravel Pint](#7-laravel-pint)
8. [Composer](#8-composer)
9. [Come questo progetto applica lo stack](#9-come-questo-progetto-applica-lo-stack)
10. [Risorse per approfondire](#10-risorse-per-approfondire)
11. [Fonti](#fonti)

---

## 1. PHP 8.2+

**Versione minima richiesta**: PHP 8.2 (`"php": "^8.2"` in `composer.json`; il runtime Sail usa 8.3/8.5).

### Novità rilevanti da PHP 8.0 a 8.3+

**Constructor Property Promotion (8.0)** — elimina la dichiarazione separata delle proprietà:

```php
class EventService
{
    public function __construct(
        private readonly EventRepository $repository
    ) {}
}
```

**Enums (8.1)** — sostituiscono costanti e stringhe magiche con tipi safe; si integrano col cast Eloquent (`'status' => EventStatus::class`):

```php
enum EventStatus: string
{
    case Published = 'published';
    case Draft     = 'draft';
}
```

**Readonly properties / classes (8.1 / 8.2)** — assegnabili una sola volta, prevengono mutazioni accidentali (ideali per DTO/Value Object). **Standalone types `null`/`false`** e **DNF types** (8.2). **Typed class constants** (8.3).

### Best practice PHP 8.x per questo stack
- Constructor property promotion nei service/middleware.
- `readonly` per DTO e Value Object.
- Enum per stati/ruoli/categorie invece di stringhe magiche.
- Return type espliciti su tutti i metodi (incluso `void`).
- PHPDoc con array shape per array complessi.

---

## 2. Laravel 12

**Versione**: 12.x (rilascio 24 febbraio 2025). PHP supportato 8.2–8.5. Fine bug fix: agosto 2026; fine security: febbraio 2027.

### Cosa cambia rispetto a Laravel 10
Laravel 11 ha introdotto la "Streamlined Application Structure", mantenuta in 12. La differenza più visibile è la **scomparsa dei Kernel**:

| Elemento rimosso (pre-11) | Sostituto in 11/12 |
|---|---|
| `app/Http/Kernel.php` | `bootstrap/app.php` → `withMiddleware()` |
| `app/Console/Kernel.php` | `bootstrap/app.php` + `routes/console.php` |
| `app/Exceptions/Handler.php` | `withExceptions()` in `bootstrap/app.php` |
| Provider multipli | `AppServiceProvider` + `bootstrap/providers.php` |

### `bootstrap/app.php` — cuore della configurazione

```php
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',                       // health check built-in
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->appendToGroup('web', TrackPageView::class);
    })
    ->withExceptions(fn (Exceptions $e) => /* ... */)
    ->create();
```

### Route Model Binding con slug

```php
Route::get('/events/{event:slug}', EventDetailPage::class);
```

Eloquent risolve automaticamente l'`Event` per colonna `slug`.

### `casts()` come metodo (non proprietà)

```php
protected function casts(): array
{
    return [
        'starts_at'    => 'datetime',
        'is_published' => 'boolean',
        'status'       => EventStatus::class,
    ];
}
```

### Eager loading nativo con limite (Laravel 12)

```php
Event::query()->with(['categories' => fn ($q) => $q->latest()->limit(5)])->get();
```

### Gotcha comuni
- `env()` solo nei file di config; altrove `config('chiave')`.
- `update()` ritorna `bool`, non il model; usare `fresh()` se serve l'istanza aggiornata.
- N+1 in Livewire: ogni re-render chiama `render()`, quindi eager-load sempre.
- `change()` in migration richiede di ridichiarare TUTTI gli attributi della colonna.

---

## 3. Livewire 4

**Versione installata**: v4.2.4 (verificata in `composer.lock`).

> Il `CLAUDE.md` cita "Livewire 3" nell'header, ma la versione reale è **v4** (richiesta da Filament 5). Le linee guida Laravel Boost riportano correttamente `livewire/livewire - v4`.

### Cosa cambia rispetto a v2

| Comportamento | v2 | v3/v4 |
|---|---|---|
| `wire:model` default | live (ogni keystroke) | **deferred** |
| Comunicazione componenti | `$this->emit()` | `$this->dispatch()` |
| Integrazione Alpine | manuale | **bundlata** automaticamente |
| Form Object | assente | classe `Form` dedicata |
| Proprietà locked | assente | `#[Locked]` |
| Navigazione SPA-like | assente | `wire:navigate` |
| Reactive props | assente | `#[Reactive]` |

### Componenti full-page — pattern del progetto

```php
Route::get('/events', EventsPage::class)->name('events');
```

```php
public function render(): View
{
    return view('livewire.events-page', [
        'events' => $this->buildQuery()->paginate(12),
    ])->layout('layouts.app', [
        'pageTitle'       => 'Eventi',
        'metaDescription' => 'Calendario eventi della community Salento.dev.',
    ]);
}
```

### Stato reattivo e `updated*()`

```php
public string $search = '';
public function updatedSearch(): void
{
    $this->resetPage();   // reset paginazione quando cambia la ricerca
}
```

`wire:model` è deferred per default; per la ricerca live: `wire:model.live.debounce.250ms="search"`.

### Gotcha comuni
- Non passare oggetti Eloquent come proprietà pubbliche (serializzati ad ogni request): passare ID o usare il binding di route.
- Ricordare `resetPage()` quando cambiano i filtri.
- Preferire componenti Blade per UI senza stato (comunicano automaticamente col parent).
- `emit()` è rimosso: usare `dispatch()`.

---

## 4. Filament 5

**Versione**: 5.6.0. Requisiti: PHP 8.2+, Laravel 11.28+, **Livewire 4**, Tailwind CSS 4.

### Perché esiste Filament 5
Il major bump da v4 a v5 serve essenzialmente a portare il supporto a **Livewire 4** senza breaking change funzionali nelle app v4.

### Struttura di un Resource (pattern del progetto)

```
app/Filament/Resources/Events/
├── EventResource.php        ← model, nav, pages, relations
├── Pages/                   ← List / Create / Edit
├── Schemas/EventForm.php    ← form estratto in classe dedicata
├── Tables/EventsTable.php   ← tabella estratta in classe dedicata
└── RelationManagers/        ← (Events e Resources)
```

### Namespace corretti — mappa (fonte di errori comuni)

| Componente | Namespace |
|---|---|
| Form fields (`TextInput`, `Select`, `FileUpload`, `Toggle`, `DateTimePicker`, `Repeater`, `MarkdownEditor`) | `Filament\Forms\Components\` |
| Infolist entries (read-only) | `Filament\Infolists\Components\` |
| Layout (`Grid`, `Section`, `Fieldset`, `Tabs`, `Wizard`) | `Filament\Schemas\Components\` |
| Utility schema (`Get`, `Set`) | `Filament\Schemas\Components\Utilities\` |
| Actions (`Action`, `DeleteAction`, `EditAction`, ...) | `Filament\Actions\` |
| Icone | `Filament\Support\Icons\Heroicon` (enum) |

> Non esiste `Filament\Tables\Actions\`: tutte le azioni stanno in `Filament\Actions\`.

### Breaking change: visibilità file privata di default

```php
FileUpload::make('image_path')
    ->disk('public')
    ->directory('events')
    ->visibility('public');   // OBBLIGATORIO per file pubblici
```

### Breaking change: Grid/Section/Fieldset non occupano più tutte le colonne
Usare `->columnSpanFull()` quando serve l'intera larghezza.

### Conditional fields con `Get` + `.live()`

```php
Select::make('source_type')->options(['file' => 'File', 'url' => 'URL'])->live();

FileUpload::make('file_path')
    ->visible(fn (Get $get): bool => $get('source_type') === 'file')
    ->required(fn (Get $get): bool => $get('source_type') === 'file');
```

### Panel provider con discovery

```php
->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
->discoverWidgets(in: app_path('Filament/Widgets'),   for: 'App\Filament\Widgets')
```

### Gotcha comuni
- Namespace errati (il caso più frequente: `TextInput` importato da `Schemas` invece che da `Forms`).
- Dimenticare `.live()` su `Select` per i campi condizionali.
- File senza `->visibility('public')` → URL non funzionanti.
- Il metodo del Resource riceve `Filament\Schemas\Schema`, non `Form`.

---

## 5. PHPUnit 11

**Versione**: 11.5.x (PHP 8.2+).

### Novità: attributi PHP 8 come standard
Le annotazioni docblock (`@test`, `@dataProvider`, `@group`) sono **deprecate** in PHPUnit 11 e rimosse in 12. Usare gli **attributi**:

```php
use PHPUnit\Framework\Attributes\{Test, DataProvider, Group, CoversClass};

#[CoversClass(EventResource::class)]
#[Group('filament')]
class EventResourceTest extends TestCase
{
    #[Test]
    #[DataProvider('eventStateProvider')]
    public function it_shows_correct_status(bool $published, string $expected): void { /* ... */ }

    public static function eventStateProvider(): array
    {
        return ['published' => [true, 'Pubblicato'], 'draft' => [false, 'Bozza']];
    }
}
```

Attributi principali: identificazione (`#[Test]`, `#[TestDox]`), data provider (`#[DataProvider]`, `#[TestWith]`), coverage (`#[CoversClass]`, `#[UsesClass]`), organizzazione (`#[Group]`), lifecycle (`#[Before]`, `#[After]`), skip (`#[RequiresPhp]`, `#[RequiresPhpExtension]`).

### Gotcha comuni
- Le annotazioni docblock generano deprecation warning: migrare agli attributi.
- `createStub()` con expectations è hard-deprecated: usare `createMock()`.
- Test senza asserzioni → "risky"; usare `#[DoesNotPerformAssertions]` solo se intenzionale.
- I file di test devono terminare con `Test.php`.

---

## 6. Laravel Sail

**Versione**: 1.x. CLI leggera per gestire l'ambiente Docker; cuore in `compose.yaml` + `vendor/bin/sail`.

### Comandi essenziali

```bash
./vendor/bin/sail up -d          # avvio in background
./vendor/bin/sail down           # stop + rimuovi container
./vendor/bin/sail down -v        # + rimuovi volumi
./vendor/bin/sail shell          # bash nel container app
./vendor/bin/sail tinker
./vendor/bin/sail artisan migrate
./vendor/bin/sail composer install
./vendor/bin/sail npm run dev
./vendor/bin/sail test
./vendor/bin/sail build --no-cache   # rebuild immagini
```

### Database di test
Sail crea automaticamente un database `testing` separato (script `create-testing-database.sh`), referenziato da `phpunit.xml` (`DB_DATABASE=testing`).

### Gotcha comuni
- Eseguire `php`/`composer` senza `sail` usa la versione PHP dell'host, non quella Docker.
- Permessi root sui file: allineare con `WWWUSER`/`WWWGROUP`.
- Porta 3306 occupata: configurare `FORWARD_DB_PORT` nel `.env`.

---

## 7. Laravel Pint

**Versione**: 1.x. Code style fixer basato su PHP CS Fixer.

```bash
# comando del progetto
./vendor/bin/sail bin pint --dirty --format agent
```

- `--dirty`: solo file modificati non committati.
- `--format agent`: output per AI/CI.
- `--test`: ispezione senza modifiche (per CI).
- `--diff=main`: confronto con un branch.

Preset: `laravel` (default), `psr12`, `per`, `symfony`, `empty`. Configurazione in `pint.json`.

### Esempio CI (GitHub Actions)

```yaml
name: Code Style
on: [push, pull_request]
jobs:
  pint:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v5
      - uses: shivammathur/setup-php@v2
        with: { php-version: 8.4, tools: pint }
      - run: pint --test
```

---

## 8. Composer

Gestisce dipendenze, autoload PSR-4, script e hook.

### Autoloading

```json
"autoload": {
  "psr-4": {
    "App\\": "app/",
    "Database\\Factories\\": "database/factories/",
    "Database\\Seeders\\": "database/seeders/"
  }
}
```

### Script del progetto (estratto)

```json
"post-autoload-dump": [
  "@php artisan package:discover --ansi",
  "@php artisan filament:upgrade"
]
```

`filament:upgrade` ripubblica le risorse JS/CSS di Filament ad ogni install/update — fondamentale.

---

## 9. Come questo progetto applica lo stack

- **Architettura Livewire-first**: ogni route pubblica punta a un componente full-page (niente controller). `Route → Livewire → Eloquent → Blade`.
- **Pattern `EventsPage`**: stato in proprietà pubbliche, `updated*()` per side effect, `WithPagination`, `with([])` per eager loading, `match()` per l'ordinamento.
- **Filament con classi Strategy**: form/tabella estratti in `EventForm`/`EventsTable`, mantenendo i Resource < 60 righe.
- **`casts()` come metodo** sui modelli; accessor classici per gli attributi calcolati.
- **Middleware in `bootstrap/app.php`**: `TrackPageView` appeso al gruppo `web`, senza Kernel.
- **`FileUpload` con `visibility('public')`** esplicita (breaking change Filament 5).
- **Discovery automatica** di Resources/Widgets nel panel provider.
- **`composer run dev`**: avvia in parallelo `serve`, `queue:listen`, `pail`, `npm run dev` via `concurrently`.

Dettagli implementativi: [Architettura](../02_architettura/01_architettura.md), [Modelli](../02_architettura/03_modelli.md), [Pagine pubbliche](../03_codice/01_pagine_pubbliche.md), [Pannello admin](../03_codice/02_pannello_admin.md).

---

## 10. Risorse per approfondire

**PHP** — [PHP 8.2](https://www.php.net/releases/8.2/en.php) · [New in PHP 8.2 (Stitcher)](https://stitcher.io/blog/new-in-php-82) · [PHP 8.3 (Kinsta)](https://kinsta.com/blog/php-8-3/) · [Readonly classes](https://php.watch/versions/8.2/readonly-classes)

**Laravel 12** — [Release Notes](https://laravel.com/docs/12.x/releases) · [Structure](https://laravel.com/docs/12.x/structure) · [Eloquent](https://laravel.com/docs/12.x/eloquent) · [Middleware](https://laravel.com/docs/12.x/middleware) · [Route Model Binding](https://laravel.com/docs/12.x/routing#route-model-binding)

**Livewire 4** — [Docs](https://livewire.laravel.com/docs) · [Upgrade v3→v4](https://livewire.laravel.com/docs/upgrading)

**Filament 5** — [Getting Started](https://filamentphp.com/docs/5.x) · [Upgrade v4→v5](https://filamentphp.com/docs/5.x/upgrade-guide) · [Laravel News](https://laravel-news.com/filament-5)

**PHPUnit 11** — [Announcement](https://phpunit.de/announcements/phpunit-11.html) · [Attributes](https://docs.phpunit.de/en/11.5/attributes.html)

**Sail / Pint / Composer** — [Sail](https://laravel.com/docs/12.x/sail) · [Pint](https://laravel.com/docs/12.x/pint) · [Composer](https://getcomposer.org/doc/) · [PSR-4](https://www.php-fig.org/psr/psr-4/)

---

## Fonti

| Tema | URL |
|---|---|
| Laravel 12 Release Notes | https://laravel.com/docs/12.x/releases |
| Laravel 12 Structure | https://laravel.com/docs/12.x/structure |
| Laravel 12 Eloquent | https://laravel.com/docs/12.x/eloquent |
| Laravel 12 Middleware | https://laravel.com/docs/12.x/middleware |
| Laravel Sail | https://laravel.com/docs/12.x/sail |
| Laravel Pint | https://laravel.com/docs/12.x/pint |
| Livewire Docs | https://livewire.laravel.com/docs |
| Livewire Upgrade | https://livewire.laravel.com/docs/upgrading |
| Filament 5 | https://filamentphp.com/docs/5.x |
| Filament 5 Upgrade | https://filamentphp.com/docs/5.x/upgrade-guide |
| Filament 5 — Laravel News | https://laravel-news.com/filament-5 |
| PHPUnit 11 | https://phpunit.de/announcements/phpunit-11.html |
| PHPUnit 11 Attributes | https://docs.phpunit.de/en/11.5/attributes.html |
| PHP 8.2 | https://www.php.net/releases/8.2/en.php |
| Readonly classes | https://php.watch/versions/8.2/readonly-classes |
| New in PHP 8.2 | https://stitcher.io/blog/new-in-php-82 |
| PHP 8.3 | https://kinsta.com/blog/php-8-3/ |
| Composer | https://getcomposer.org/doc/ |
| PSR-4 | https://www.php-fig.org/psr/psr-4/ |
