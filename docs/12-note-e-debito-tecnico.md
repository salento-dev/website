# 12 · Note e debito tecnico

Raccolta delle incongruenze e dei punti di attenzione emersi dall'analisi del codice. Sono **osservazioni fattuali**, non necessariamente bug: servono a orientare future correzioni e a evitare sorprese. Ogni voce indica dove si manifesta.

---

## Sicurezza

| # | Punto | Dettaglio | Impatto |
|---|-------|-----------|---------|
| S1 | **Nessuna autorizzazione granulare nell'admin** | `User::canAccessPanel()` ritorna sempre `true`. Qualsiasi record in `users` accede all'intero pannello; non esistono ruoli/policy. | Alto in produzione: ogni utente = super-admin. |
| S2 | **Credenziali di default in chiaro** | `DatabaseSeeder` crea `admin@salento.dev` / `password123`. | Da cambiare/disabilitare in produzione. |
| S3 | **Chiamate HTTP esterne in editing** | Il form Autori e gli accessor `display_*` interrogano l'API GitHub (`Author::fetchGithubProfile`). Mitigate da cache 24h, ma dipendono da un servizio esterno e dai rate limit. | Medio: lentezza/edge case se GitHub non risponde. |

> Mitigazione XSS già presente: i corpi Markdown di eventi e storie sono resi con `Str::markdown(..., ['html_input' => 'strip', 'allow_unsafe_links' => false])`.

---

## Correttezza / performance

| # | Punto | Dettaglio |
|---|-------|-----------|
| C1 | **`partners` non eager-loaded in `EventDetailPage`** | La view referenzia `$event->partners`, ma `mount()` eager-load solo `media`/`categories`/`resources` (con guardia `Schema::hasTable`). I partner vengono quindi **lazy-loaded** e non sono protetti dalla guardia. Possibile query extra / errore se la tabella manca. |
| C2 | **Asimmetria guardie tra i due dettagli** | `EventDetailPage::mount()` usa `Schema::hasTable` per gli eager-load; `StoryDetailPage::mount()` no (usa `load([...])` diretto). |
| C3 | **Stato di ricerca non in query string** | Nessun componente lista usa `#[Url]`/`$queryString`: ricerca/filtri/ordinamento non sono bookmarkabili né condivisibili via URL. Scelta di design da confermare. |

---

## Disallineamenti factory/seeder ↔ schema

| # | Punto | Dettaglio |
|---|-------|-----------|
| F1 | **`ResourceFactory` obsoleta** | Genera ancora i campi `slug` e `image_path`, **rimossi** dalla tabella `resources` (migrazioni `2026_04_20_*`). Non è nel percorso di seeding attivo, ma fallirebbe se usata direttamente. |
| F2 | **`ResourceSeeder` obsoleto** | Stesso problema (referenzia campi rimossi). Non invocato da `DatabaseSeeder`. |
| F3 | **Seeder factory non collegati** | `EventSeeder` e `ResourceSeeder` esistono ma non sono richiamati da `DatabaseSeeder` (che usa i seeder `Real*`). |

---

## Documentazione storica non allineata

| # | Punto | Dettaglio |
|---|-------|-----------|
| D1 | **`CLAUDE.md` datato** | La tabella delle route non include **Storie** né **Autori**; l'elenco modelli non cita `Story`, `StoryCategory`, `Author`, `PageView`. La presente `docs/` è la fonte aggiornata. |
| D2 | **`.claude/design.md` incompleto** | Non elenca diversi componenti realmente presenti: tutta la cartella `stories/`, `events/wip-event`, e vari `ui/` (`author-chips`, `category-tag`, `countdown-bars`, `countdown-minimal`, `detail-layout`, `empty-state`, `event-meta`, `lightbox`, `read-stats`, `spark-link`, `status-badge`, `title-indicator`). |
| D3 | **Semantica token `surface`** | `.claude/design.md` descrive `surface` con valori esadecimali; nel CSS `surface` è **alias della scala `mist`** (`--color-surface` = mist-950). Allineare la descrizione. |

---

## Varie

| # | Punto | Dettaglio |
|---|-------|-----------|
| V1 | **`FilamentInfoWidget` importato ma non registrato** | In `AdminPanelProvider` l'import esiste ma il widget non è nell'array `widgets()`. |
| V2 | **Delete inline mancante su Partners e Users** | Le tabelle Filament di Partner e Utenti hanno solo `EditAction` inline; la cancellazione è possibile solo via bulk action. Verificare se intenzionale. |
| V3 | **Copertura di test minima** | Solo smoke test su `/` e `/partners`. Eventi, risorse, storie, tracking visite e risorse Filament non sono coperti. |
| V4 | **Nessuna CI configurata** | In `.github/` non sono presenti workflow (solo un file skill). Non esiste pipeline automatica per test/Pint sulle PR. |
| V5 | **Asset di build versionati** | `public/build/` è tracciato nel repository: ogni `npm run build` produce diff rumorosi (nuovi hash). Valutare con il team se spostarlo in `.gitignore` e gestire la build in fase di deploy. |

---

## Suggerimenti di intervento (priorità indicativa)

1. **Sicurezza admin (S1, S2):** introdurre ruoli/policy e rimuovere/rifornire le credenziali di default prima di qualsiasi deploy pubblico.
2. **Eager-load partner (C1):** allineare `EventDetailPage::mount()` precaricando `partners` con la stessa guardia degli altri.
3. **Pulizia factory/seeder (F1–F3):** aggiornare `ResourceFactory`/`ResourceSeeder` allo schema corrente o rimuoverli.
4. **CI (V4):** aggiungere un workflow GitHub Actions che esegua Pint e la suite di test su ogni PR.
5. **Allineamento documentazione storica (D1–D3):** aggiornare `CLAUDE.md` e `.claude/design.md`, oppure rimandare a `docs/`.

> Queste sono proposte: ogni intervento andrebbe discusso con il team e svolto in una PR dedicata.
