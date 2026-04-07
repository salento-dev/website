# Design System — salento.dev

## Principi Generali

- Dark theme esclusivo — nessuna modalità light
- Stile "cyber/terminal" con angoli tagliati e decorazioni geometriche
- Border radius piccolo (`rounded-xs` = 0.25rem) come default — no border radius grandi
- Pills (`rounded-full`) solo per badge, button CTA e tag
- Animazioni via **GSAP** (complesse) o **Alpine.js x-transition** (micro-interazioni)
- Font heading: **Outfit** (`font-outfit`) — Font body: **Albert Sans** (`font-albert-sans`) — Font mono: **Roboto Mono** (`font-roboto-mono`)

---

## Palette Colori

### Colori Semantici (da usare sempre)

| Token                  | Valore base     | Uso                                  |
|------------------------|-----------------|--------------------------------------|
| `text-primary`         | Cyan-500        | Accenti, icone, link, eyebrow        |
| `text-primary-dark`    | Cyan-700        | Hover su elementi primary            |
| `text-secondary`       | Violet-500      | Accenti secondari                    |
| `text-tertiary`        | Fuchsia-500     | Accenti terziari                     |
| `text-mist-50`         | Quasi bianco    | Titoli, testo principale             |
| `text-mist-200`        | Grigio chiaro   | Testo secondario                     |
| `text-mist-400`        | Grigio medio    | Testo muted, meta info               |
| `text-mist-600`        | Grigio scuro    | Decorazioni, elementi disabilitati   |
| `bg-surface`           | #0a0a0a         | Background pagina (più scuro)        |
| `bg-surface-800`       | Grigio scuro    | Card, input, elementi interattivi    |
| `bg-surface-900`       | Grigio molto scuro | Card base, sfondo componenti      |
| `bg-surface-950`       | Quasi nero      | Background sezioni                   |
| `border-border`        | surface-800     | Bordi standard                       |

### Colori Effetti

| Classe                 | Effetto                                      |
|------------------------|----------------------------------------------|
| `.text-glow`           | Text shadow arancio (`accent-orange`)        |
| `.border-glow`         | Bordo + box-shadow arancio sottile           |
| `.shadow-glow`         | Box shadow arancio                           |
| `.shadow-glow-purple`  | Box shadow arancio variante viola            |
| `.text-gradient`       | Gradient testo amber → orange                |

### Background Decorativi

| Classe                 | Effetto                                      |
|------------------------|----------------------------------------------|
| `.bg-dots-pattern`     | Pattern puntini ripetuto                     |
| `.bg-grid`             | Griglia linee sottili                        |
| `.bg-gradient-radial`  | Gradiente radiale ambrato dal top            |
| `.bg-gradient-mesh`    | Gradiente mesh angoli                        |

---

## Componenti UI (`resources/views/components/ui/`)

### `<x-ui.badge>`
Badge con punto pulsante animato (ping). Nessuna prop — usa `$slot`.
```blade
<x-ui.badge>Novità</x-ui.badge>
```

### `<x-ui.button>`
CTA principale con animazione GSAP su hover (arrow + character shine).
```blade
<x-ui.button
    href="{{ route('events') }}"
    text="Vedi tutti gli eventi"
    variant="default"     {{-- default | primary | secondary --}}
    icon=true             {{-- mostra icona freccia --}}
    width="auto"          {{-- auto | full | qualsiasi classe Tailwind --}}
/>
```

### `<x-ui.section-header>`
Header di sezione con eyebrow, titolo, descrizione e link opzionale. Wrappa il `$slot`.
```blade
<x-ui.section-header
    eyebrow="Prossimi"
    title="<span class='text-primary'>Events</span>"
    description="Testo descrittivo opzionale"
    :link="['href' => route('events'), 'text' => 'Tutti gli eventi']"
    info="42 eventi"
    :bordered="false"
>
    {{-- contenuto della sezione --}}
</x-ui.section-header>
```

### `<x-ui.section-title>`
Titolo sezione con icona Heroicon, linea decorativa e link azione.
```blade
<x-ui.section-title
    title="EVENTI"
    icon="calendar"           {{-- nome icona heroicon senza prefisso --}}
    actionLabel="Vedi tutti"  {{-- opzionale --}}
    actionHref="{{ route('events') }}"
/>
```

### `<x-ui.input-search>`
Input di ricerca con Alpine.js, debounce 250ms, integrazione Livewire.
```blade
<x-ui.input-search
    placeholder="Cerca eventi..."
    srLabel="Cerca eventi"
    wireModel="search"        {{-- nome della proprietà Livewire --}}
/>
```

### `<x-ui.countdown>`
Countdown live (giorni/ore/minuti/secondi) via Alpine.js.
```blade
<x-ui.countdown :timestamp="$event->starts_at->timestamp" />
```

### `<x-ui.item-count>` · `<x-ui.sort-select>` · `<x-ui.paginator>`
Usati nelle pagine con lista + filtri. Sempre in coppia con Livewire.

### `<x-ui.terminal>`
Display stile terminale per mostrare contenuto tecnico/risorse.

### `<x-ui.slideshow>` + `<x-ui.slideshow-slide>`
Carosello/slideshow per eventi in evidenza. Usare sempre in coppia.

### `<x-ui.corners>`
Decorazione angoli geometrici SVG. Elemento puramente decorativo.

### `<x-ui.logo>`
Logo del sito. Nessuna prop.

---

## Componenti Card (`resources/views/components/cards/`)

### `<x-events.event>`
Card evento — wrappa un modello `Event`. Gestisce automaticamente stati WIP, passato, futuro.
```blade
<x-events.event :event="$event" variant="default" />
{{-- variant: default (con immagine) | simple (solo testo, per griglia compatta) --}}
```

### `<x-events.featured-event>`
Card evento in evidenza — più grande, usata nello slideshow hero.
```blade
<x-events.featured-event :event="$event" />
```

### `<x-cards.alert>`
Box informativo/avviso con icona Heroicon.
```blade
<x-cards.alert
    icon="o-information-circle"
    title="Titolo opzionale"
    description="Testo del messaggio"
/>
```

### `<x-cards.partner>`
Card partner/sponsor con logo e nome.
```blade
<x-cards.partner :partner="$partner" />
```

### `<x-cards.resource-row>`
Riga risorsa per lista verticale (stile terminale).
```blade
<x-cards.resource-row :resource="$resource" />
```

### `<x-cards.simple>` · `<x-cards.small>` · `<x-cards.principle>`
Card generiche per contenuti testuali/principi. Usare `$slot` per il contenuto.

---

## Componenti Sezioni (`resources/views/components/sections/`)

| Componente                          | Uso                                               |
|-------------------------------------|---------------------------------------------------|
| `<x-sections.home-hero>`            | Hero della homepage con animazioni GSAP           |
| `<x-sections.page-hero>`            | Hero delle pagine interne                         |
| `<x-sections.activities>`           | Sezione attività della community                  |
| `<x-sections.principles>`           | Sezione principi/valori                           |
| `<x-sections.code-of-conduct>`      | Sezione codice di condotta                        |
| `<x-sections.scopes>`               | Sezione ambiti/tematiche                          |
| `<x-sections.search.filters>`       | Pannello filtri ricerca (Livewire)                |
| `<x-sections.search.feature-chip>`  | Chip filtro feature                               |
| `<x-sections.search.tag-chip>`      | Chip filtro tag                                   |
| `<x-sections.search.tab-switcher>`  | Tab switcher (es. Upcoming / Past)                |

---

## Componenti Partials (`resources/views/components/partials/`)

| Componente                           | Uso                                              |
|--------------------------------------|--------------------------------------------------|
| `<x-partials.header>`                | Header sito (incluso nel layout)                 |
| `<x-partials.footer>`                | Footer sito (incluso nel layout)                 |
| `<x-partials.desktop-navbar>`        | Navbar desktop                                   |
| `<x-partials.desktop-navbar-item>`   | Singolo item navbar desktop                      |
| `<x-partials.mobile-navbar>`         | Navbar mobile                                    |
| `<x-partials.mobile-navbar-item>`    | Singolo item navbar mobile                       |
| `<x-partials.skills-loop>`           | Loop infinito orizzontale con GSAP               |

---

## Utility CSS (`.btn`)

Per bottoni non-CTA (es. nei filtri, nell'admin):
```html
<button class="btn bg-surface-800 text-mist-200 hover:bg-surface-700">
    Filtra
</button>
```

`.item` — Pill tag piccola (es. categorie inline):
```html
<span class="item">PHP</span>
```

---

## Animazioni

### GSAP (animazioni complesse)
Disponibile globalmente come `window.gsap`. Plugin disponibili: `SplitText`, `Draggable`.
```js
// Esempio: animare un titolo al mount
gsap.from('[data-hero-title]', { opacity: 0, y: 30, duration: 0.6 });
```

### Alpine.js (micro-interazioni e stato)
Usare `x-transition` per show/hide, `x-data` per stato locale.
```blade
<div x-data="{ open: false }">
    <button @click="open = !open">Toggle</button>
    <div x-show="open" x-transition>Contenuto</div>
</div>
```

### motion.inView (scroll trigger)
```js
motion.inView('[data-animate]', (el) => {
    gsap.from(el, { opacity: 0, y: 20, duration: 0.5 });
});
```

### autoAnimate (liste dinamiche)
Per liste che cambiano (Livewire search/filter):
```js
autoAnimate(document.querySelector('#lista'));
```

---

## Regole per Nuovi Componenti

1. **Controlla sempre** se esiste già un componente simile in `ui/`, `cards/`, `sections/`
2. Metti i nuovi componenti nella cartella corretta (`ui/` se atomico, `cards/` se è una card, `sections/` se è un blocco di pagina)
3. Usa `@props([])` per dichiarare esplicitamente tutte le props con valori default
4. Usa `rounded-xs` come border radius default — no `rounded-lg` o superiori
5. Surface scuro di default: `bg-surface-900` per card, `bg-surface-800` per input/elementi interattivi
6. I testi principali sono `text-mist-50`, quelli secondari `text-mist-400`
7. Gli accenti usano sempre `text-primary` (cyan) — non hardcodare colori
8. Le icone vengono da **Heroicons** via `<x-heroicon-o-nome />` o `@svg('heroicon-o-nome', 'classi')`
