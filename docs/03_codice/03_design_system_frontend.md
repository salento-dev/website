# 03 · Design system & frontend

> **Fonte primaria del design system:** `resources/css/app.css` (token Tailwind v4 + utility custom) e `.claude/design.md` (linee guida narrative). Dove i due divergono, **fa fede il CSS**; le discrepanze sono segnalate. Il comportamento dinamico vive in `resources/js/app.js` (GSAP/motion/autoAnimate) e nei singoli componenti Blade (Alpine).

> Vedi anche: [Pagine pubbliche](01_pagine_pubbliche.md) · [Pannello admin](02_pannello_admin.md) · [Note e debito tecnico](../04_sviluppo_e_qualita/04_note_e_debito_tecnico.md).

---

## Principi generali

- **Dark theme esclusivo** — `<html class="dark">` statico (`layouts/app.blade.php:2`), nessuna modalità light.
- Stile **cyber/terminal**: angoli tagliati, decorazioni geometriche (triangoli d'angolo, bracket SVG), pattern a puntini/griglia, prompt da terminale.
- **Border radius piccolo** di default (`--radius: 0.25rem`); le pill (`rounded-full`) sono riservate a badge, CTA e tag.
- Animazioni complesse via **GSAP** (timeline, SplitText, Draggable); micro‑interazioni e stato locale via **Alpine.js** (fornito dal bundle Livewire, non importato in `app.js`); animazioni di lista via **`@formkit/auto-animate`**; trigger on‑scroll via **`@motionone/dom`** (`inView`).
- Font: **Outfit** (heading), **Albert Sans** (body), **Roboto Mono** (mono).

---

## Token Tailwind v4 (`resources/css/app.css`, blocco `@theme`, righe 9‑72)

### Font

| Token | Valore | Uso |
|-------|--------|-----|
| `--font-sans` | `ui-sans-serif, system-ui, …` | Fallback di sistema |
| `--font-mono` | `ui-monospace, SFMono-Regular, …` | Fallback mono di sistema |
| `--font-albert-sans` | `"Albert Sans", Verdana, sans-serif` | Corpo |
| `--font-outfit` | `"Outfit", "Albert Sans", sans-serif` | Titoli |
| `--font-roboto-mono` | `"Roboto Mono Variable", monospace` | Monospace / dettagli "terminale" |
| `--font-headline` | `var(--font-outfit)` | Alias semantico heading |
| `--font-label` | `var(--font-albert-sans)` | Alias semantico body |

### Colori

| Token | Valore | Note |
|-------|--------|------|
| `--color-primary-50…950` | alias di `--color-cyan-50…950` | scala **cyan** |
| `--color-primary` | `var(--color-primary-500)` | accento principale (cyan‑500) |
| `--color-primary-dark` / `-light` | cyan‑700 / cyan‑300 | hover / variante chiara |
| `--color-secondary` (+ `-dark`/`-light`) | **violet**‑500 / 700 / 300 | accento secondario |
| `--color-tertiary` (+ `-dark`/`-light`) | **fuchsia**‑500 / 700 / 300 | accento terziario |
| `--color-surface-50…950` | alias di `--color-mist-50…950` | scala sfondi |
| `--color-surface` | `var(--color-mist-950)` | sfondo base pagina |
| `--color-surface-contrast` | `var(--color-surface-50)` | testo su superfici scure |
| `--color-border` | `var(--color-surface-800)` | bordi standard |

> ⚠️ **`surface` è un alias di `mist`**: `--color-surface-N = --color-mist-N` e `--color-surface = mist-950`. La descrizione storica in `.claude/design.md` ("surface = #0a0a0a") va riletta in questa chiave. **`primary` è cyan**, non un valore hardcoded.

### Layout, z‑index, raggi

| Token | Valore | Uso |
|-------|--------|-----|
| `--breakpoint-max` | `87.5rem` (1400px) | larghezza massima del layout |
| `--breakpoint-nav` | `50rem` (800px) | soglia navbar desktop/mobile (letta a runtime da Alpine nel `<body>`) |
| `--z-100` | `100` | utility `z-100` |
| `--radius` | `0.25rem` | border radius default |
| `--radius-lg` / `-xl` / `-2xl` | `0.375` / `0.5` / `0.75rem` | varianti |
| `--radius-full` | `9999px` | pill |

---

## Utility custom (`@utility`, righe 74‑90)

| Utility | Effetto |
|---------|---------|
| `container` | `margin-inline: auto; padding-inline: 1rem` |
| `text-2xs` | `font-size: 0.625rem; line-height: 1rem` (font extra‑small per eyebrow/meta) |
| `mask-y-from-80%` | mask verticale (fade in basso oltre l'80%) |
| `mask-x-from-35%` | mask orizzontale (fade a destra oltre il 35%) |

---

## Utility CSS (`@layer utilities`, righe 92‑207)

**Sfondi decorativi:**

| Classe | Effetto |
|--------|---------|
| `.bg-x-pattern` | pattern "x" da `images/x-pattern-15.png`, `background-size: 12rem` |
| `.bg-dots-pattern` | pattern a puntini ripetuto da `images/dots-pattern-15.png`, `9rem` (sfondo globale del `<body>`) |
| `.bg-grid` | griglia di linee 40×40px (`rgba(38,38,38,.5)`) |
| `.bg-gradient-radial` | radiale ambra dal top (`rgba(245,158,11,.08)`) |
| `.bg-gradient-mesh` | mesh a due radiali (ambra + arancio) dagli angoli alti |

**Testo / glow / bordi:**

| Classe | Effetto |
|--------|---------|
| `.text-gradient` | testo con gradient `#f59e0b → #f97316` (amber→orange), via `background-clip: text` |
| `.text-glow` | `text-shadow: 0 0 20px rgba(245,158,11,.2)` (ambra) |
| `.border-glow` | bordo + box‑shadow ambra sottile |

**Ombre:**

| Classe | Effetto |
|--------|---------|
| `.shadow-soft` | ombra Filament‑style molto sottile |
| `.shadow-card` | ombra card con hairline ambra |
| `.shadow-glow` | glow ambra (`rgba(245,158,11,.15)`) |
| `.shadow-glow-purple` | glow **arancio** (`rgba(249,115,22,.15)`) — il nome dice "purple" ma il colore è arancio |
| `.shadow-neon` / `.shadow-neon-purple` | alias legacy di `shadow-glow` / `shadow-glow-purple` |

**Componenti utility:**

- **`.item`** — pill tag piccola: `inline-grid h-8 min-w-9 place-items-center rounded-xs bg-surface-900 px-1.5 font-medium text-surface-200`.
- **`.btn`** — bottone base non‑CTA:

  ```css
  .btn {
      @apply inline-flex items-center justify-center gap-1.75 h-10 min-w-10 px-3 rounded-xs;
      @apply bg-surface-900 text-surface-50 hover:bg-surface-800 active:scale-95;
      @apply transition-all duration-300 ease-out;
      @apply focus-visible:ring focus-visible:ring-primary/50;
      @apply disabled:cursor-not-allowed disabled:opacity-50;
  }
  ```

  Modificatori: `.btn-sm` (`h-8 px-2 text-xs`), `.btn-large` (`h-12 px-8 text-lg`), `.btn.active` (`bg-surface-700`). Le varianti colore si applicano inline (es. classi extra accanto a `.btn`).

---

## Tipografia base e decorazioni `data-*`

**`@layer base` (righe 209‑226):** `h1…h6` e le classi `.heading-1..6` usano `font-family: var(--font-outfit)` con colore `var(--color-surface-200)`.

**Decorazioni via attributi (righe 228‑318):**

| Selettore | Effetto |
|-----------|---------|
| `[data-triangle-decoration]` (`bottom` / `top`) | triangolini d'angolo via pseudo‑elementi `::before`/`::after` con `border-color: var(--color-border)`. Usato su hero/footer/header del layout. |
| `[data-corner-cut]` (`sm` / default / `lg`) | angolo in basso a destra tagliato via `clip-path: polygon(...)`; `--corner-cut-size` `.375` / `.625` / `.9rem`. |
| `[data-button-pulse]` | overlay che, in hover, anima un keyframe `button-pulse` (opacity .75→0, `inset` 0→‑8px) in 0.7s. |

---

## JavaScript (`resources/js/app.js`)

L'entrypoint JS importa e configura le librerie di animazione, definisce due helper e li espone come globali (così i componenti Blade possono usarli da `x-init` Alpine).

```js
import './bootstrap';
import autoAnimate from '@formkit/auto-animate';
import { gsap } from 'gsap';
import { Draggable } from 'gsap/Draggable';
import { SplitText } from 'gsap/SplitText';
import { inView } from '@motionone/dom';

gsap.registerPlugin(Draggable, SplitText);
```

- **`hover(element, onEnter)` (righe 12‑22):** registra `mouseenter`/`mouseleave`; `onEnter()` può restituire una funzione di cleanup eseguita all'uscita (pattern usato dai componenti GSAP per costruire/distruggere timeline su hover).
- **`horizontalLoop(items, config)` (righe 25‑73):** costruisce una `gsap.timeline` per un loop orizzontale infinito e fluido (marquee), calcolando larghezze/offset di ogni elemento. È il motore di `<x-partials.skills-loop>`.
- **Esposizioni globali (righe 76‑84):**

  ```js
  window.__heroAnimations = [];
  window.gsap = gsap;
  window.Draggable = Draggable;
  window.SplitText = SplitText;
  window.motion = { inView, hover };
  window.FilamentAnimations = { horizontalLoop };
  window.autoAnimate = autoAnimate;
  ```

  `window.motion.inView` è usato per gli scroll‑trigger; `window.motion.hover` per le animazioni hover; `window.FilamentAnimations.horizontalLoop` per il marquee skill; `window.autoAnimate` per le liste Livewire (eventi/storie) che cambiano in ricerca/filtro; `window.__heroAnimations` è un registro globale di animazioni hero.

**`resources/js/bootstrap.js`** è minimale: importa `axios`, lo espone come `window.axios` e imposta l'header `X-Requested-With: XMLHttpRequest`.

> ⚠️ **Alpine.js NON è importato in `app.js`**: è fornito dal bundle **Livewire** (`@livewireScripts` nel layout). I componenti usano comunque liberamente `x-data` / `x-init` / `x-show` / `x-transition`. Vedi [Note e debito tecnico](../04_sviluppo_e_qualita/04_note_e_debito_tecnico.md).

---

## Pipeline Vite e inclusione asset

**`vite.config.js`:**

- Plugin: `laravel-vite-plugin` (input `['resources/css/app.css', 'resources/js/app.js']`, `refresh: true`) e `@tailwindcss/vite` (Tailwind v4 compilato dal plugin Vite, niente `tailwind.config.js` — la configurazione è interamente nel `@theme` del CSS).
- Dev server: `host: true`, `port: 5173`, `strictPort: true`, HMR su `ws` con host da `VITE_HMR_HOST` (default `localhost`); ignora i view compilati (`storage/framework/views/**`).

**Sorgenti Tailwind (`app.css`, righe 1‑7):** oltre a `@import 'tailwindcss'` e al plugin `@tailwindcss/typography`, i `@source` includono le view di paginazione di Laravel, i view compilati in `storage/framework/views`, tutti i `*.blade.php` e i `*.js` sotto `resources/` — così le classi usate in Blade/JS non vengono "purgate".

**Inclusione nel layout:** `layouts/app.blade.php:19-21` emette `@vite(['resources/css/app.css', 'resources/js/app.js'])` **solo** se esiste `public/build/manifest.json` o `public/hot` (evita la `ViteException` quando gli asset non sono compilati).

**Font:** self-hosted in `resources/fonts/` (woff2 variable, subset latin e latin-ext, licenza OFL), dichiarati con `@font-face` in `resources/css/app.css` e serviti da Vite; nessuna richiesta a Google Fonts. Il layout precarica solo Outfit latin (Outfit, Albert Sans, Roboto Mono). **Icone:** Heroicons via `<x-heroicon-o-*>` o `@svg('heroicon-…')`; icone social via `ublabs/blade-simple-icons` (`<x-simpleicon-*>` o `<x-dynamic-component :component="'simpleicon-'.$icon">`).

**Comandi:** `vendor/bin/sail npm run build` (produzione), `vendor/bin/sail npm run dev` (HMR), `vendor/bin/sail composer run dev` (server + queue + log + vite via `concurrently`).

---

## Inventario dei componenti Blade (`resources/views/components/`)

Tutti i percorsi sono relativi a `resources/views/components/`. Le props sono riportate come dichiarate in `@props([...])`.

### `ui/` (28 componenti)

| Componente | `@props` | Scopo / note |
|------------|----------|--------------|
| `author-chips` | `authors`, `label = null` | Riga di avatar autore cliccabili che aprono una modale Alpine con la card completa. Usa `<x-cards.author>`, `x-transition`, escape via `@keydown.escape`. |
| `badge` | — (`$slot`) | Badge pill con dot primario pulsante (`animate-ping`). |
| `button` | `href='#'`, `text=''`, `ariaLabel=''`, `icon=true`, `variant='default'`, `width='auto'` | CTA principale con animazione GSAP su hover (freccia + shine per‑carattere via SplitText). Variant `primary\|secondary\|default`. |
| `category-tag` | `name` | Pill uppercase per il nome di una categoria. |
| `corners` | `hover=false`, `color='text-surface-400'`, `size='h-2.5'`, `offset=8` | Quattro bracket SVG d'angolo; se `hover` animano verso l'esterno via GSAP (`motion.hover` sul `.group` genitore). Decorativo. |
| `countdown` | `timestamp` | Countdown live g/h/min/sec (Alpine `setInterval`); mostra "Oggi" a fine. |
| `countdown-bars` | `timestamp` | Variante countdown con barre di avanzamento sotto ogni unità. |
| `countdown-minimal` | `timestamp` | Countdown compatto g/h/m (senza secondi). |
| `detail-layout` | `stickyTop='lg:top-22'` | Layout di dettaglio a due colonne (article + sidebar sticky) con slot `mobileMeta`, `article`, `sidebar` e `$slot` extra. |
| `empty-state` | `message='Nessun risultato'`, `icon='o-magnifying-glass'` | Placeholder "nessun risultato" con bordo tratteggiato e Heroicon. |
| `event-meta` | `icon`, `text`, `href=null` | Riga icona + testo (eventualmente link). |
| `input-search` | `placeholder='Search'`, `srLabel='Search'`, `wireModel=null`, `listeningMessages=[…]` | Input ricerca con hint rotanti e clear; se `wireModel` è valorizzato si lega in due vie a una proprietà Livewire (`x-model.debounce.250ms`, `$wire.set`). |
| `item-count` | `collection`, `label='items found'` | Conteggio risultati (gestisce `LengthAwarePaginator` → `total()`). |
| `lightbox` | `src`, `alt=''` | Miniatura che apre un lightbox a schermo intero (`x-teleport="body"`, `x-transition`). |
| `logo` | — (`$attributes`) | Logo SVG inline (`currentColor`). |
| `paginator` | `paginator` | Navigazione paginazione Livewire (`wire:click="gotoPage(n)"`, finestra di pagine). |
| `read-stats` | `readTime=null`, `pageViews=null` | Minuti di lettura + numero visite (`number_format`). |
| `section-header` | `eyebrow=null`, `title=null`, `description=null`, `link=null`, `info=null`, `tag=null`, `bordered=false`, `size='default'` | Header di sezione; `title` reso come **HTML raw** (`{!! !!}`), `link` è un array `['href','text']`. Wrappa `$slot`. |
| `section-title` | `title`, `icon`, `actionLabel=null`, `actionHref='#'` | Titolo sezione con Heroicon, linea decorativa e link azione. |
| `slideshow` | `count=1`, `interval=6000` | Carosello auto‑avanzante (Alpine: `current`, dot, frecce); espone `current`/`start`/`stop` agli slide. |
| `slideshow-slide` | `index=0` | Singolo slide; visibile quando `current === index`; in hover mette in pausa l'autoplay. Da usare dentro `<x-ui.slideshow>`. |
| `sort-select` | `options`, `currentValue`, `wireProperty='sortBy'`, `label='Sort by:'` | Dropdown custom che imposta una proprietà di ordinamento Livewire (`$wire.set`). |
| `spark-link` | `href='#'`, `text=''`, `ariaLabel=''`, `target='_blank'`, `rel='noopener noreferrer'`, `iconSize='4'` | Link "spark" (es. WhatsApp) con GSAP (scale icona + particelle); icona via slot `icon`. |
| `status-badge` | `status='upcoming'`, `label=null` | Pill di stato (upcoming/ongoing/past/wip) con dot pulsante per live/upcoming. |
| `terminal` | `path=''` | Chrome finestra "terminale" (`root@{community}:~{path}` da `config('community.name')`) che wrappa `$slot`. |
| `title-indicator` | — | Cluster di punti rotante/pulsante mostrato in hover del `.group` accanto ai titoli (GSAP). Decorativo. |

**Esempi:**

```blade
<x-ui.button href="{{ route('events') }}" text="Vedi tutti gli eventi" variant="primary" />
<x-ui.input-search placeholder="Cerca eventi..." srLabel="Cerca eventi" :wireModel="'search'" />
<x-ui.sort-select :options="$sortOptions" :currentValue="$sortBy" wireProperty="sortBy" />
<x-ui.read-stats :read-time="$story->read_time_minutes" :page-views="$story->page_views_count" />
<x-ui.detail-layout>
    <x-slot:article>…</x-slot:article>
    <x-slot:sidebar>…</x-slot:sidebar>
</x-ui.detail-layout>
```

### `cards/` (7 componenti)

| Componente | `@props` | Scopo / note |
|------------|----------|--------------|
| `alert` | `icon`, `title=null`, `description=false` | Box informativo/avviso con Heroicon. |
| `author` | `author`, `size='default'` | Card autore (avatar, nome, ruolo, bio, social). `size` `lg\|default`. Usa accessor `avatar_url`, `display_role`, `display_bio`, `github_url`, ecc. |
| `partner` | `partner` | Card partner (logo, nome, descrizione, link sito). Accessor `logo_url`, fallback Heroicon `cpu-chip`. |
| `principle` | `image_path`, `title`, `description` | Card "principio" con effetti GSAP su hover (scale background, scroll del pattern). Incorpora `title-indicator` e `corners`. |
| `resource-row` | `resource` | Riga risorsa stile terminale, cliccabile → modale Alpine con descrizione, autori e CTA download/apri. |
| `simple` | `title`, `description`, `icon`, `variant='default'` | Card centrata con icona; variant `danger` aggiunge bracket e tema rosso. |
| `small` | `title`, `description`, `icon`, `variant='default'` | Variante orizzontale (icona a sinistra) di `simple`. |

```blade
<x-cards.partner :partner="$partner" />
<x-cards.resource-row :resource="$resource" />
<x-cards.simple icon="o-code-bracket" title="Web Dev" description="Frontend, backend" />
```

### `events/` (3 componenti)

| Componente | `@props` | Scopo / note |
|------------|----------|--------------|
| `event` | `event`, `variant='default'` | Card evento; gestisce stati WIP/passato/futuro. Non‑WIP è un link a `route('events.show', $event)`; WIP non cliccabile. `variant='simple'` = solo testo. |
| `featured-event` | `event` | Card evento in evidenza (immagine di sfondo, badge live/upcoming, countdown, meta, CTA); `isLive` calcolato su tz `Europe/Rome`. |
| `wip-event` | `event` | Placeholder "work in progress" con mese target e luogo; usa `status-badge status="wip"`. |

```blade
<x-events.event :event="$event" variant="default" />
<x-events.featured-event :event="$event" />
<x-events.wip-event :event="$event" />
```

### `stories/` (2 componenti)

| Componente | `@props` | Scopo / note |
|------------|----------|--------------|
| `featured-story` | `story` | Card storia in evidenza (immagine + contenuto affiancati, categoria, read time, avatar autori stacked, CTA). |
| `story` | `story`, `variant='default'` | Card storia per griglia; `default` con immagine, altrimenti solo testo. Link a `route('stories.show', $story)`. |

```blade
<x-stories.featured-story :story="$featuredStory" />
<x-stories.story :story="$story" variant="simple" />
```

### `sections/` (6) e `sections/search/` (4)

Le `sections/` sono per lo più wrapper statici che compongono componenti `ui/`/`cards/` (nessuna prop salvo `page-hero`).

| Componente | `@props` | Scopo |
|------------|----------|-------|
| `activities` | — | Sezione "Cosa Facciamo": `section-header` + griglia di `cards/small`. |
| `code-of-conduct` | — | Sezione codice di condotta: `section-header` + tre `cards/simple` (danger) + `cards/alert`. |
| `home-hero` | — | Hero della homepage (badge, titolo, descrizione, CTA `.btn`). Usa `config('community.name')`, `route('events')`/`route('resources')`. |
| `page-hero` | `badge=null`, `title=null`, `description=false` | Hero pagine interne; il titolo grande viene dal `$slot`, con eyebrow `badge` e descrizione opzionale. |
| `principles` | — | Sezione principi: `section-header` + quattro `cards/principle` (GSAP). |
| `scopes` | — | Sezione ambiti: `section-header` + otto `cards/simple` + `cards/alert`. |
| `search/feature-chip` | `state`, `label` | Chip toggle legata a un boolean dello scope Alpine genitore (`pluginListFilters`). Icona via `$slot`. |
| `search/filters` | `wireSearch=null`, `versions=[…]`, `prices=[…]`, `tags=[]`, `tagLabels=[]`, `listeningMessages=[…]` | Pannello completo di ricerca/filtri (`x-data="pluginListFilters({...})"`): input, toggle filtri con conteggio attivi e reset, tab‑switcher versione/prezzo, feature‑chip e tag‑chip. |
| `search/tab-switcher` | `legend`, `legendId`, `name`, `model`, `options=[]`, `idPrefix=null` | Radiogroup segmentato con indicatore scorrevole CSS‑only, legato a un model Alpine. |
| `search/tag-chip` | `tag`, `label` | Chip tag selezionabile (plus → check); usa `toggleTag()`/`isTagSelected()` dello scope genitore. |

```blade
<x-sections.page-hero badge="Eventi" description="Tutti i nostri incontri">Gli Eventi</x-sections.page-hero>
```

> Il sotto‑gruppo `sections/search/*` implementa un sistema di filtri client‑side basato sulla factory Alpine `pluginListFilters` (versione/prezzo/feature/tag) ed è indipendente dallo stato Livewire della pagina; l'`input-search` interno può comunque legarsi a una proprietà `wire`.

### `partials/` (7 componenti)

| Componente | `@props` | Scopo / note |
|------------|----------|--------------|
| `header` | — | Header sticky: spark‑link WhatsApp (da `config('community.social')`), logo home, navbar desktop + mobile. Decorazione `data-triangle-decoration="bottom"`. |
| `footer` | — | Footer: logo, `config('community.description')`, social (`config('community.social')`), nav filtrata per `footer===true`, link legali (`route('privacy-policy')`, `route('cookie-policy')`). |
| `desktop-navbar` | — | Itera `config('navigation.items')` e rende `desktop-navbar-item`. |
| `desktop-navbar-item` | (usa `$route`, `$routeName`) | Link nav desktop con reveal GSAP dei bracket d'angolo su hover; `aria-current` se `request()->routeIs($route)`. |
| `mobile-navbar` | — | Menu mobile a bottom‑sheet con GSAP + `Draggable` (swipe‑to‑close); dipende dallo stato Alpine `showMobileMenu` del `<body>`. Definisce inline `window.initMobileMenu`, `window.initBadgeAnimation`, `window.initShineAnimation`. |
| `mobile-navbar-item` | `route=null`, `routeName=null` | Link nav mobile; l'item attivo mostra un badge "You're here" animato; chiude il menu al click. |
| `skills-loop` | — (lista di 18 skill hardcoded) | Marquee orizzontale infinito via `window.FilamentAnimations.horizontalLoop` + `ResizeObserver`; registra l'animazione in `window.__heroAnimations`. |

**Configurazione consumata dai partial:**

- `config/navigation.php` — array `items` con `route`, `label`, `footer` (flag che decide se la voce compare nel footer). Voci: Home (no footer), Eventi, Risorse, Storie, Partner.
- `config/community.php` — `name`, `email`, `description` e `social` (WhatsApp, Instagram, LinkedIn con `key`/`label`/`url`/`icon`).

---

## Componenti animati con GSAP / Alpine — quadro sintetico

| Tipo di animazione | Componenti coinvolti | Plugin / helper |
|--------------------|----------------------|-----------------|
| Hover timeline | `ui/button`, `ui/corners` (hover), `ui/spark-link`, `ui/title-indicator`, `cards/principle`, `events/featured-event` (via corners), `partials/desktop-navbar-item` | `gsap`, `motion.hover` |
| SplitText (shine testo) | `ui/button` | `SplitText` |
| Drag / swipe | `partials/mobile-navbar` | `Draggable` |
| Marquee infinito | `partials/skills-loop` | `FilamentAnimations.horizontalLoop` |
| Liste reattive | griglie eventi/storie nelle view Livewire | `autoAnimate` |
| Scroll‑trigger | hero / sezioni animate | `motion.inView` |

> ⚠️ `.claude/design.md` non elenca diversi componenti realmente presenti (tutta la cartella `stories/`, `events/wip-event`, vari `ui/` come `countdown-bars`, `countdown-minimal`, `detail-layout`, `read-stats`, `spark-link`, `status-badge`, `title-indicator`, `author-chips`, `lightbox`, `event-meta`, `category-tag`): vedi [Note e debito tecnico](../04_sviluppo_e_qualita/04_note_e_debito_tecnico.md).

---

## Linee guida per nuovi componenti

1. Verifica se esiste già un componente simile in `ui/`, `cards/`, `sections/`, `events/`, `stories/`.
2. Colloca il componente nella cartella corretta (`ui/` atomico, `cards/` card, `sections/` blocco di pagina, `partials/` struttura del layout).
3. Dichiara le props con `@props([])` e valori default espliciti.
4. Usa `rounded-xs` come default (no `rounded-lg` o superiori, salvo pill `rounded-full` per badge/CTA/tag).
5. Sfondi: `bg-surface-900` per le card, `bg-surface-800` per input/elementi interattivi.
6. Testi: principali `text-mist-50` / `text-surface-50`, secondari `text-mist-400` / `text-surface-400`.
7. Accenti sempre via `text-primary` (cyan) — non hardcodare i colori.
8. Icone da Heroicons (`<x-heroicon-o-*>` / `@svg('heroicon-…')`); social via `<x-simpleicon-*>`.
9. Per le animazioni usa gli helper globali (`window.gsap`, `window.motion.hover`, `window.motion.inView`, `window.autoAnimate`) da `x-init`, ricordando che **Alpine arriva dal bundle Livewire**.
