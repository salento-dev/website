# 04 · Note e debito tecnico

Raccolta delle incongruenze e dei punti di attenzione emersi dall'analisi del codice. Sono **osservazioni fattuali** verificate sul sorgente: per ciascuna è indicato **dove si manifesta**, l'**impatto** e una **proposta di intervento** concreta. Servono a orientare future correzioni e a evitare sorprese; ogni intervento andrebbe discusso col team e svolto in una PR dedicata.

> Documenti correlati: i comportamenti citati sono descritti in [Funzionalità](../01_analisi_funzionale/02_funzionalita.md); ambiente e test in [Ambiente locale](./01_ambiente_locale.md) e [Testing](./02_testing.md).

---

## Sicurezza

### S1 — Nessuna autorizzazione granulare nell'admin

- **Dove:** `app/Models/User.php:51` — `canAccessPanel(Panel $panel): bool { return true; }`.
- **Dettaglio:** qualsiasi record nella tabella `users` accede all'**intero** pannello Filament. Non esistono ruoli, policy o gate. Non c'è distinzione tra utenti.
- **Impatto:** **Alto in produzione**. Ogni utente registrato = super-admin con pieno controllo su eventi, risorse, storie, partner, autori e utenti.
- **Fix proposto:** introdurre un discriminante (es. campo `is_admin` o un sistema di ruoli) e restringere l'accesso:

  ```php
  public function canAccessPanel(Panel $panel): bool
  {
      return $this->is_admin === true;
      // oppure: return str_ends_with($this->email, '@salento.dev') && $this->is_admin;
  }
  ```

  Affiancare Policy per le Resource sensibili (almeno `UserResource`).

### S2 — Credenziali di default in chiaro

- **Dove:** `database/seeders/DatabaseSeeder.php:31-36` crea `admin@salento.dev` / `password123` via `make:filament-user`.
- **Impatto:** accettabile in sviluppo, **pericoloso** se il seed gira in ambienti esposti.
- **Fix proposto:** leggere le credenziali da variabili d'ambiente con fallback solo in `local`, oppure non creare l'utente in `production`:

  ```php
  if (app()->environment('local')) {
      Artisan::call('make:filament-user', [/* ... */]);
  }
  ```

### S3 — Chiamate HTTP esterne in fase di editing/rendering

- **Dove:** `app/Models/Author.php` — accessor `display_bio`/`display_role`/`display_website_url`/`display_twitter_url` invocano `githubProfile()` → `fetchGithubProfile()` (`Author.php:127-150`), che chiama `https://api.github.com/users/...`.
- **Dettaglio:** mitigato da `Cache::remember(..., 24h)`, ma dipende da un servizio esterno e dai suoi **rate limit** (60 richieste/ora non autenticate).
- **Impatto:** **Medio**. Lentezza o edge case se GitHub non risponde; possibile esaurimento del rate limit in admin con molti autori privi di campi locali.
- **Fix proposto:** spostare l'arricchimento in un **job in coda** che persiste i dati localmente (avatar/bio/ruolo) invece di interrogare GitHub a ogni rendering; gestire esplicitamente il timeout (`Http::timeout(3)`).

> **Mitigazione XSS già presente:** i corpi Markdown di eventi e storie sono resi con `Str::markdown(..., ['html_input' => 'strip', 'allow_unsafe_links' => false])`.

---

## Correttezza / bug

### C1 — Ricerca eventi su colonna inesistente (`description`)

- **Dove:** `app/Livewire/EventsPage.php:99` — `->orWhere('description', 'like', ...)` in `buildPastEventsQuery()`.
- **Dettaglio:** la colonna `description` è stata **rinominata in `body`** dalla migrazione `2026_04_10_162306_rename_description_to_body_on_events_table.php`. La condizione `when(filled($this->search), ...)` attiva la `WHERE` solo quando l'utente digita qualcosa: con ricerca **non vuota** la query solleva `SQLSTATE ... Unknown column 'description'`.
- **Impatto:** **Alto** — la ricerca eventi è di fatto rotta non appena si digita un termine.
- **Fix proposto:** sostituire `description` con `body` (o `excerpt`):

  ```php
  $subQuery->where('title', 'like', '%'.$this->search.'%')
      ->orWhere('location', 'like', '%'.$this->search.'%')
      ->orWhere('body', 'like', '%'.$this->search.'%');
  ```

  Aggiungere un feature test sulla ricerca per prevenire regressioni.

### C2 — Ricerca storie su colonna rimossa (`author`)

- **Dove:** `app/Livewire/StoriesPage.php:100` — `->orWhere('author', 'like', ...)` in `buildStoriesQuery()`.
- **Dettaglio:** le colonne `author`/`author_role` sono state **rimosse** dalla tabella `stories` (migrazione `2026_04_07_220415_remove_author_fields_from_stories_table.php`); gli autori sono ora una relazione molti-a-molti (`author_story`). Con ricerca non vuota la query solleva `Unknown column 'author'`.
- **Impatto:** **Alto** — la ricerca storie è rotta quando si digita un termine.
- **Fix proposto:** rimuovere la condizione su `author` e, se serve cercare per autore, usare `whereHas('authors', ...)`:

  ```php
  $sub->where('title', 'like', '%'.$this->search.'%')
      ->orWhere('excerpt', 'like', '%'.$this->search.'%')
      ->orWhereHas('authors', fn ($a) => $a->where('name', 'like', '%'.$this->search.'%'));
  ```

### C3 — `partners` non eager-loaded in `EventDetailPage`

- **Dove:** `app/Livewire/EventDetailPage.php:15-47` — `mount()` precarica `media`/`categories`/`resources` con guardia `Schema::hasTable`, ma **non** `partners`, mentre la view referenzia `$event->partners`.
- **Impatto:** **Medio** — i partner vengono **lazy-loaded** (query extra per ogni rendering) e, non essendo protetti dalla guardia, possono causare errore se la tabella pivot `event_partner` manca.
- **Fix proposto:** allineare al pattern degli altri:

  ```php
  if (Schema::hasTable('event_partner')) {
      $relations[] = 'partners';
  }
  // ... e setRelation('partners', new Collection) nel ramo else
  ```

### C4 — Asimmetria delle guardie tra i due dettagli

- **Dove:** `EventDetailPage::mount()` usa `Schema::hasTable` per gli eager-load; `StoryDetailPage::mount()` (`app/Livewire/StoryDetailPage.php:17`) usa `load([...])` diretto senza guardia.
- **Impatto:** **Basso** — incoerenza di stile; il caso "tabella mancante" è plausibile solo in transitori di migrazione.
- **Fix proposto:** uniformare la strategia (entrambe con guardia, oppure entrambe senza, documentando la scelta).

### C5 — Stato di ricerca non in query string

- **Dove:** nessun componente lista usa `#[Url]`/`$queryString` (`EventsPage`, `StoriesPage`, `ResourcesPage`).
- **Impatto:** **Basso** — ricerca/filtri/ordinamento non sono bookmarkabili né condivisibili via URL, e si perdono al refresh.
- **Fix proposto (se desiderato):** annotare le proprietà pubbliche con `#[Url]` per persisterle in query string. Da confermare come scelta di design.

---

## Disallineamenti factory/seeder ↔ schema

### F1 — `ResourceFactory` obsoleta

- **Dove:** `database/factories/ResourceFactory.php:24-32` genera i campi `slug` e `image_path`.
- **Dettaglio:** entrambi **rimossi** dalla tabella `resources` (migrazioni `2026_04_20_123158_drop_slug_from_resources_table.php` e `2026_04_20_122802_drop_image_path_from_resources_table.php`).
- **Impatto:** la factory **non** è nel percorso di seeding attivo, ma fallirebbe se usata direttamente (es. in un test sulle risorse).
- **Fix proposto:** rimuovere `slug` e `image_path` dalla `definition()`, mantenendo `title`, `description`, `source_type`, `external_url`, `file_path`, `is_published`.

### F2 — `ResourceSeeder` obsoleto

- **Dove:** `database/seeders/ResourceSeeder.php` imposta `image_path` (campo rimosso) e dipende dalla factory disallineata.
- **Impatto:** non invocato da `DatabaseSeeder` (la riga è commentata, `DatabaseSeeder.php:27`), ma fallirebbe se richiamato.
- **Fix proposto:** aggiornarlo allo schema corrente oppure rimuoverlo.

### F3 — Seeder factory non collegati

- **Dove:** `EventSeeder` e `ResourceSeeder` esistono ma `DatabaseSeeder` richiama solo i seeder `Real*` (`RealAuthorsSeeder`, `EventCategorySeeder`, `RealPartnersSeeder`, `RealEventsSeeder`, `StoryCategorySeeder`, `RealStoriesSeeder`).
- **Impatto:** **Basso** — codice morto/di scorta che può divergere dallo schema.
- **Fix proposto:** decidere se mantenerli come generatori di dati fittizi (allineandoli) o rimuoverli per ridurre rumore.

---

## Documentazione storica non allineata

### D1 — `CLAUDE.md` datato

- **Dettaglio:** la tabella delle route non include **Storie** né **Autori**; l'elenco modelli non cita `Story`, `StoryCategory`, `Author`, `PageView`.
- **Fix proposto:** aggiornare `CLAUDE.md` con le route `/stories`, `/stories/{slug}` e i modelli mancanti, oppure rimandare esplicitamente a `docs/` come fonte di verità.

### D2 — `.claude/design.md` incompleto

- **Dettaglio:** non elenca diversi componenti realmente presenti: l'intera cartella `stories/`, `events/wip-event`, e vari `ui/` (`author-chips`, `category-tag`, `countdown-minimal`, `detail-layout`, `empty-state`, `event-meta`, `lightbox`, `read-stats`, `spark-link`, `status-badge`, `title-indicator`).
- **Fix proposto:** completare il catalogo componenti o linkare [Design system](../03_codice/03_design_system_frontend.md) come riferimento aggiornato.

### D3 — Semantica del token `surface`

- **Dettaglio:** `.claude/design.md` descrive `surface` con valori esadecimali; nel CSS `surface` è **alias della scala `mist`** (`--color-surface` ≈ `mist-950`).
- **Fix proposto:** allineare la descrizione del token alla definizione CSS reale.

---

## Varie

### V1 — `FilamentInfoWidget` importato ma non registrato

- **Dove:** `app/Providers/Filament/AdminPanelProvider.php:19` importa `Filament\Widgets\FilamentInfoWidget`, ma l'array `widgets()` (`AdminPanelProvider.php:46-53`) registra `PageViewStatsWidget`, `PageViewsChartWidget`, `TopEventsWidget`, `TopStoriesWidget`, `TopReferrersWidget`, `AccountWidget` — **non** `FilamentInfoWidget`.
- **Impatto:** **Trascurabile** — solo un `use` inutilizzato (Pint/PHPCS lo segnalerebbe).
- **Fix proposto:** rimuovere l'import oppure aggiungere il widget all'array se lo si vuole in dashboard.

### V2 — Delete inline mancante su Partner e Utenti

- **Dove:** `PartnersTable.php` e `UsersTable.php` espongono `EditAction` inline + `DeleteBulkAction` (toolbar), ma **non** `DeleteAction` inline; `EventsTable.php:85` invece ha anche la `DeleteAction` inline.
- **Impatto:** **Basso** — incoerenza UX: la cancellazione singola di un partner/utente passa solo dalla bulk action.
- **Fix proposto:** se intenzionale, documentarlo; altrimenti aggiungere `DeleteAction::make()` inline per coerenza con la tabella eventi.

### V3 — Copertura di test minima

- **Dove:** solo `tests/Feature/PartnersPageTest.php`, `tests/Feature/ExampleTest.php`, `tests/Unit/ExampleTest.php`.
- **Impatto:** **Alto** in ottica regressioni — i bug C1/C2 sarebbero stati intercettati da semplici test di ricerca.
- **Fix proposto:** seguire la roadmap di copertura in [Testing](./02_testing.md), partendo da eventi, storie e analytics.

### V4 — Nessuna CI configurata

- **Dove:** `.github/` non contiene workflow (solo un file skill).
- **Impatto:** nessuna garanzia automatica che Pint/test passino sulle PR.
- **Fix proposto:** aggiungere un workflow GitHub Actions che, su ogni PR, esegua `pint --test` e `artisan test` su un servizio MySQL (o SQLite per velocità).

### V5 — Asset di build versionati

- **Dove:** `public/build/` è tracciato nel repository.
- **Impatto:** ogni `npm run build` produce diff rumorosi (nuovi hash dei file).
- **Fix proposto:** valutare con il team lo spostamento in `.gitignore` e la generazione della build in fase di deploy.

---

## Suggerimenti di intervento (priorità indicativa)

1. **Bug di ricerca (C1, C2):** correggere subito le colonne `description`→`body` e rimuovere il filtro su `author`; aggiungere test di ricerca. Sono malfunzionamenti visibili all'utente.
2. **Sicurezza admin (S1, S2):** introdurre ruoli/policy e gestire le credenziali di default prima di qualsiasi deploy pubblico.
3. **Eager-load partner (C3):** allineare `EventDetailPage::mount()` precaricando `partners` con la stessa guardia degli altri.
4. **Pulizia factory/seeder (F1–F3):** aggiornare `ResourceFactory`/`ResourceSeeder` allo schema corrente o rimuoverli.
5. **CI (V4):** aggiungere un workflow che esegua Pint e la suite di test su ogni PR.
6. **Allineamento documentazione storica (D1–D3):** aggiornare `CLAUDE.md` e `.claude/design.md`, oppure rimandare a `docs/`.

> Queste sono proposte: ogni intervento andrebbe discusso con il team e svolto in una PR dedicata, con test a copertura del comportamento corretto.
