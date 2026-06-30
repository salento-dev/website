# 08 · Design system & frontend

> Fonte primaria del design system: `resources/css/app.css` (token Tailwind v4) e `.claude/design.md` (linee guida). Dove i due divergono, **fa fede il CSS**; le discrepanze sono segnalate.

---

## Principi generali

- **Dark theme esclusivo** (`<html class="dark">`), nessuna modalità light.
- Stile **cyber/terminal**: angoli tagliati, decorazioni geometriche, pattern a puntini/griglia.
- **Border radius piccolo** di default (`--radius: 0.25rem`); pill (`rounded-full`) solo per badge/CTA/tag.
- Animazioni via **GSAP** (complesse) e **Alpine.js** (micro-interazioni).
- Font: **Outfit** (heading), **Albert Sans** (body), **Roboto Mono** (mono).

---

## Token Tailwind v4 (`resources/css/app.css`, blocco `@theme`)

| Token | Valore | Uso |
|-------|--------|-----|
| `--font-outfit` / `--font-headline` | "Outfit" | Titoli |
| `--font-albert-sans` / `--font-label` | "Albert Sans" | Corpo |
| `--font-roboto-mono` | "Roboto Mono Variable" | Monospace |
| `--color-primary-*` | scala **cyan** | Accenti, link, icone (`primary`=cyan-500) |
| `--color-secondary` | **violet-500** | Accenti secondari |
| `--color-tertiary` | **fuchsia-500** | Accenti terziari |
| `--color-surface-*` | alias scala **mist** | Sfondi (`surface`=mist-950) |
| `--color-border` | surface-800 | Bordi |
| `--breakpoint-max` | 87.5rem | Larghezza max layout |
| `--breakpoint-nav` | 50rem | Soglia navbar desktop/mobile |
| `--radius` | 0.25rem | Border radius default (più `-lg`, `-xl`, `-2xl`, `-full`) |

> ⚠️ Nel codice **`surface` è un alias di `mist`** e `--color-surface` = mist-950. La descrizione storica in `.claude/design.md` ("surface = #0a0a0a") va letta in questa chiave. `primary` è **cyan**, non hardcoded.

---

## Utility CSS custom (`app.css`)

**Sfondi decorativi:** `.bg-x-pattern`, `.bg-dots-pattern` (puntini), `.bg-grid` (griglia 40px), `.bg-gradient-radial` (radiale ambra), `.bg-gradient-mesh`.

**Testo/glow:** `.text-gradient` (amber→orange), `.text-glow` (text-shadow ambra), `.border-glow`.

**Ombre:** `.shadow-soft`, `.shadow-card`, `.shadow-glow`, `.shadow-glow-purple` (in realtà arancio), legacy `.shadow-neon*`.

**Componenti utility:**
- `.item` — pill tag piccola (`h-8 min-w-9 rounded-xs bg-surface-900`).
- `.btn` — bottone base (`h-10 rounded-xs bg-surface-900 hover:bg-surface-800 active:scale-95`) con modificatori `.btn-sm`, `.btn-large`, `.btn.active`, e varianti colore applicate inline (es. `.btn btn-primary`).

**Decorazioni via attributi data-:**
- `[data-triangle-decoration]` (varianti `bottom`/`top`) — triangolini d'angolo.
- `[data-corner-cut]` (varianti `sm`/`lg`) — angolo tagliato via `clip-path`.
- `[data-button-pulse]` — animazione pulse su hover.

**Tipografia base** (`@layer base`): heading e classi `.heading-1..6` usano `font-outfit`.

---

## JavaScript (`resources/js/app.js`)

- Importa `./bootstrap` (espone `window.axios` con header `X-Requested-With`).
- Librerie: `@formkit/auto-animate`, `gsap` + plugin **Draggable** e **SplitText** (`gsap.registerPlugin`), `@motionone/dom` (`inView`).
- Helper: `hover(element, onEnter)` e `horizontalLoop(items, config)` (loop orizzontale infinito).
- **Esposizioni globali:** `window.gsap`, `window.Draggable`, `window.SplitText`, `window.motion = { inView, hover }`, `window.FilamentAnimations = { horizontalLoop }`, `window.autoAnimate`, `window.__heroAnimations = []`.

> ⚠️ **Alpine.js NON è importato in `app.js`**: è fornito dal bundle **Livewire** (`@livewireScripts`). I componenti usano comunque liberamente `x-data`/`x-init`/`x-show`.

---

## Inventario dei componenti Blade (`resources/views/components/`)

### `cards/` (7)
| Componente | Props | Scopo |
|------------|-------|-------|
| `alert` | `icon`, `title`, `description` | Box avviso con icona |
| `author` | `author`, `size` | Card autore (avatar, nome, ruolo) |
| `partner` | `partner` | Card partner (logo, nome, descrizione) |
| `principle` | `image_path`, `title`, `description` | Card "principio" con animazioni GSAP |
| `resource-row` | `resource` | Riga risorsa (lista terminale, espandibile) |
| `simple` | `title`, `description`, `icon`, `variant` | Card generica (default/danger) |
| `small` | `title`, `description`, `icon`, `variant` | Variante compatta di `simple` |

### `events/` (3)
| Componente | Props | Scopo |
|------------|-------|-------|
| `event` | `event`, `variant` (default/simple) | Card evento (non cliccabile se WIP) |
| `featured-event` | `event` | Card evento in evidenza (immagine, countdown, isLive) |
| `wip-event` | `event` | Card evento "work in progress" |

### `stories/` (2)
| Componente | Props | Scopo |
|------------|-------|-------|
| `featured-story` | `story` | Card storia in evidenza |
| `story` | `story`, `variant` | Card storia per griglia |

### `sections/` (6 + `search/`)
| Componente | Scopo |
|------------|-------|
| `activities` | Sezione attività community |
| `code-of-conduct` | Sezione codice di condotta |
| `home-hero` | Hero della homepage |
| `page-hero` (`badge`, `title`, `description`) | Hero pagine interne |
| `principles` | Sezione principi (4 card) |
| `scopes` | Sezione ambiti (8 card) |
| `search/feature-chip` | Chip filtro feature (Alpine) |
| `search/filters` | Pannello filtri ricerca |
| `search/tab-switcher` | Tab switcher radiogroup CSS-only |
| `search/tag-chip` | Chip tag selezionabile |

### `partials/` (7)
| Componente | Scopo |
|------------|-------|
| `header` | Header sticky (WhatsApp da `config('community.social')`) |
| `footer` | Footer (descrizione + social + nav filtrata `footer===true`) |
| `desktop-navbar` / `desktop-navbar-item` | Navbar desktop (da `config('navigation.items')`) |
| `mobile-navbar` / `mobile-navbar-item` | Menu mobile (Alpine, badge "You're here") |
| `skills-loop` | Loop orizzontale infinito GSAP (18 skill hardcoded) |

### `ui/` (28)
`author-chips`, `badge`, `button`, `category-tag`, `corners`, `countdown`, `countdown-bars`, `countdown-minimal`, `detail-layout`, `empty-state`, `event-meta`, `input-search`, `item-count`, `lightbox`, `logo`, `paginator`, `read-stats`, `section-header`, `section-title`, `slideshow`, `slideshow-slide`, `sort-select`, `spark-link`, `status-badge`, `terminal`, `title-indicator` (più varianti countdown).

Componenti UI più usati nelle pagine:

| Componente | Props principali | Scopo |
|------------|------------------|-------|
| `badge` | `$slot` | Badge pill con dot animato |
| `button` | `href`, `text`, `variant`, `icon`, `width` | CTA pill animata |
| `input-search` | `placeholder`, `srLabel`, `wireModel` | Input ricerca integrato con `$wire` |
| `sort-select` | `options`, `currentValue`, `wireProperty` | Dropdown ordinamento Livewire |
| `paginator` | `paginator` | Paginazione Livewire |
| `item-count` | `collection`, `label` | Conteggio risultati |
| `empty-state` | `message`, `icon` | Stato vuoto |
| `terminal` | `path` | Display stile terminale |
| `detail-layout` | `stickyTop` | Layout pagina di dettaglio (article + sidebar) |
| `read-stats` | `readTime`, `pageViews` | Minuti di lettura + visite |
| `countdown` | `timestamp` | Countdown live (Alpine) |
| `corners` | `hover`, `color`, `size` | Decorazione angoli SVG |
| `slideshow` + `slideshow-slide` | `count`, `interval` / `index` | Carosello |

> ⚠️ `.claude/design.md` non elenca diversi componenti realmente presenti (tutta la cartella `stories/`, `events/wip-event`, vari `ui/`): vedi [12 · Note e debito tecnico](12-note-e-debito-tecnico.md).

---

## Build & asset

- **Entrypoint Vite** (`vite.config.js`): `resources/css/app.css` + `resources/js/app.js`, con `refresh: true`. Dev server su porta **5173** (`strictPort`), HMR via `VITE_HMR_HOST`.
- Plugin Vite: `laravel-vite-plugin`, `@tailwindcss/vite`.
- Inclusione asset: `@vite([...])` in `layouts/app.blade.php` (condizionato a manifest/hot) e in `welcome.blade.php`.
- **Font** via Google Fonts (preconnect): Outfit, Albert Sans, Roboto Mono.
- **Icone:** Heroicons (`@svg('heroicon-...')`, `<x-heroicon-o-*>`) forniti da Filament; icone social via `ublabs/blade-simple-icons` (`<x-simpleicon-*>`).
- Comandi: `npm run build` (produzione), `npm run dev` (HMR), `composer run dev` (server+queue+log+vite via `concurrently`).

---

## Linee guida per nuovi componenti

1. Verifica se esiste già un componente simile in `ui/`, `cards/`, `sections/`.
2. Colloca il componente nella cartella corretta (`ui/` atomico, `cards/` card, `sections/` blocco di pagina).
3. Dichiara le props con `@props([])` e valori default.
4. Usa `rounded-xs` come default (no `rounded-lg`+).
5. Sfondi: `bg-surface-900` per card, `bg-surface-800` per input/interattivi.
6. Testi: principali `text-mist-50`, secondari `text-mist-400`.
7. Accenti sempre via `text-primary` (cyan) — non hardcodare colori.
8. Icone da Heroicons.
