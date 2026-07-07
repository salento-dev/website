# 02 · Stack frontend — riferimenti e approfondimenti

Documento di riferimento sullo stack **frontend** di salento.dev, basato su documentazione ufficiale e best practice version-specific (ricerca giugno 2026). Le fonti sono in fondo.

> **Stack frontend** (da `package.json`): **Tailwind CSS 4**, **Alpine.js 3** (fornito da Livewire 4), **GSAP 3** (+ Draggable, SplitText), **Motion One** (`@motionone/dom`), **AutoAnimate** (`@formkit/auto-animate`), **Vite 7**, `laravel-vite-plugin`, `@tailwindcss/vite`, `@tailwindcss/typography`.

---

## Indice

1. [Tailwind CSS 4](#1-tailwind-css-4)
2. [Alpine.js 3](#2-alpinejs-3)
3. [GSAP 3 + plugin](#3-gsap-3--plugin-draggable-splittext)
4. [Motion One](#4-motion-one)
5. [AutoAnimate](#5-autoanimate)
6. [Vite 7](#6-vite-7)
7. [laravel-vite-plugin](#7-laravel-vite-plugin)
8. [@tailwindcss/vite](#8-tailwindcssvite)
9. [@tailwindcss/typography](#9-tailwindcsstypography)
10. [Come questo progetto applica lo stack](#10-come-questo-progetto-applica-lo-stack-frontend)
11. [Risorse per approfondire](#11-risorse-per-approfondire)
12. [Fonti](#12-fonti)

---

## 1. Tailwind CSS 4

**Versione**: `^4.0` (rilascio gennaio 2025). Riscrittura completa su engine **Lightning CSS**.

### Novità chiave e differenze da v3
Approccio **CSS-first**: tutta la configurazione avviene in CSS, niente `tailwind.config.js`.

**Da `tailwind.config.js` a `@theme`:**

```css
@import "tailwindcss";

@theme {
  --color-primary: var(--color-cyan-500);
  --font-outfit: "Outfit", "Albert Sans", sans-serif;
  --breakpoint-nav: 50rem;
}
```

`@theme` definisce i token come variabili CSS **e** genera le utility corrispondenti (`text-primary`, `font-outfit`). Differenza con `:root`: quest'ultimo dichiara solo variabili, senza generare classi.

**Import unico** (sostituisce `@tailwind base/components/utilities`):

```css
@import "tailwindcss";
```

**Utility custom con `@utility`** (non più `@layer utilities`), che rispetta i modificatori (responsive/hover/dark):

```css
@utility text-2xs { font-size: 0.625rem; line-height: 1rem; }
```

**`@source`** per content detection esplicita (vendor, viste compilate); **`@plugin`** per i plugin CSS-first:

```css
@plugin "@tailwindcss/typography";
```

### Breaking changes principali da v3 a v4

| Elemento | v3 | v4 |
|---|---|---|
| Configurazione | `tailwind.config.js` | `@theme` in CSS |
| Import | `@tailwind ...` | `@import "tailwindcss"` |
| Utility custom | `@layer utilities {}` | `@utility {}` |
| Plugin | `plugins: [require(...)]` | `@plugin "..."` |
| `!important` | `!flex` | `flex!` |
| `shadow-sm` / `shadow` | — | ora `shadow-xs` / `shadow-sm` |
| `rounded-sm` | — | ora `rounded-xs` |
| Opacità | `bg-opacity-50` | modificatore `/50` (`bg-black/50`) |
| Gradiente | `bg-gradient-to-r` | `bg-linear-to-r` |
| Content | array `content: []` | automatica + `@source` |

### Dark mode
Default `media`. Per la strategia a classe: `@variant dark (&:where(.dark, .dark *));`. Il progetto usa **dark theme esclusivo** senza toggle.

---

## 2. Alpine.js 3

**Versione**: `^3.x`, **incluso automaticamente da Livewire 4** (non è dipendenza npm diretta).

### Modello mentale

```html
<div x-data="{ open: false }">
  <button @click="open = !open">Toggle</button>
  <div x-show="open" x-transition>Contenuto</div>
</div>
```

Directive principali: `x-data`, `x-bind`/`:`, `x-on`/`@`, `x-text`, `x-html`, `x-model`, `x-show`, `x-if`, `x-for`, `x-transition`, `x-effect`, `x-ref`, `x-cloak`, `x-teleport`, `x-init`, `x-id`. Magic: `$el`, `$refs`, `$store`, `$watch`, `$dispatch`, `$nextTick`, `$root`, `$id`.

### Integrazione con Livewire 4
Alpine è fornito da Livewire. Il bridge è l'oggetto magico **`$wire`**:

```html
<input x-model="$wire.search" type="text">
<button @click="$wire.save()">Salva</button>
```

> In Livewire 4 `@entangle` Blade è deprecato: usare `$wire.property` direttamente da Alpine. Passare dati PHP con `@js(...)`.

### Pattern idiomatici
- `Alpine.data('dropdown', () => ({ ... }))` per componenti complessi.
- `Alpine.store('theme', { ... })` per stato globale.
- `x-show` mantiene l'elemento nel DOM; `x-if` lo rimuove/crea.
- `x-cloak` + `[x-cloak]{display:none}` previene il FOUC.
- Modifier concatenabili: `@click.prevent.stop`, `@keydown.enter`, `@click.outside`.

---

## 3. GSAP 3 + plugin (Draggable, SplitText)

**Versione**: `^3.x`. **Novità cruciale**: da GSAP **3.13.0** (2024, grazie a Webflow) l'intera suite — inclusi SplitText, Draggable, MorphSVG, DrawSVG — è **gratuita anche per uso commerciale**.

### Registrazione plugin (una sola volta all'avvio)

```js
import { gsap } from 'gsap';
import { Draggable } from 'gsap/Draggable';
import { SplitText } from 'gsap/SplitText';
gsap.registerPlugin(Draggable, SplitText);
```

### Core

```js
gsap.to('.el', { x: 100, opacity: 1, duration: 0.6, ease: 'power2.out' });
gsap.from('.el', { opacity: 0, y: 30, duration: 0.5 });
const tl = gsap.timeline({ defaults: { ease: 'power2.out' } });
tl.from('.hero-title', { opacity: 0, y: 40 })
  .from('.hero-cta', { opacity: 0, scale: 0.95 }, '-=0.1');
```

**Performance**: animare solo `transform` (x/y/scale/rotation) e `opacity` (GPU); evitare `top/left/width/height`.

### SplitText
Suddivide il testo in caratteri/parole/righe per animazioni staggerate:

```js
const split = SplitText.create('.headline', { type: 'chars,words,lines' });
gsap.from(split.chars, { opacity: 0, y: 20, stagger: 0.02, duration: 0.4 });
split.revert();   // ripristina l'HTML originale
```

`autoSplit: true` con callback `onSplit` per re-split su resize/caricamento font. Rispettare `prefers-reduced-motion`; non splittare prima del caricamento dei font.

### Draggable

```js
Draggable.create('.card', { type: 'x,y', bounds: '#container', inertia: true });
```

Opzioni: `type`, `bounds`, `inertia`, `snap`/`liveSnap`, `lockAxis`, `autoScroll`. Hit testing con `Draggable.hitTest('#a', '#b', '50%')`.

---

## 4. Motion One

**Pacchetto**: `@motionone/dom`. Espone `inView`, costruita sull'**Intersection Observer** (off-thread, ~0.5 KB).

```js
import { inView } from '@motionone/dom';

inView('[data-animate]', (el) => {
  gsap.from(el, { opacity: 0, y: 30, duration: 0.5 });
}, { margin: '0px 0px -100px 0px', amount: 0.3 });
```

- Il callback si esegue **una sola volta** all'ingresso; restituendo una funzione, questa viene chiamata all'uscita.
- `inView` restituisce una funzione `stop()` per fermare il monitoraggio.
- Per scroll-linked complesse usare GSAP ScrollTrigger; `inView` è ideale per trigger semplici.

---

## 5. AutoAnimate

**Pacchetto**: `@formkit/auto-animate`. **Zero-config**: anima automaticamente i figli di un container su add/remove/move.

```js
import autoAnimate from '@formkit/auto-animate';
autoAnimate(document.querySelector('#events-list'));
```

Con Livewire (liste che cambiano su ricerca/filtri), inizializzare via Alpine: `x-init="autoAnimate($el)"`. Rispetta automaticamente `prefers-reduced-motion`. Agisce sui figli diretti; con flexbox dare `width` esplicita agli item; usare `wire:key` sugli elementi.

---

## 6. Vite 7

**Versione**: `^7.x` (2025). Richiede Node.js 20.19+ / 22.12+. Nuovo target browser default `baseline-widely-available`. Introduce **Rolldown** (bundler Rust, sperimentale). Rimossa Sass Legacy API e `splitVendorChunkPlugin`.

### Configurazione del progetto

```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [
    laravel({ input: ['resources/css/app.css', 'resources/js/app.js'], refresh: true }),
    tailwindcss(),
  ],
  server: {
    host: true, port: 5173, strictPort: true,
    hmr: { host: process.env.VITE_HMR_HOST ?? 'localhost', protocol: 'ws' },
    watch: { ignored: ['**/storage/framework/views/**'] },
  },
});
```

`host: true` e `hmr.host` sono essenziali per **Docker/Sail/WSL2**. `refresh: true` fa un full reload su modifica Blade/PHP.

---

## 7. laravel-vite-plugin

Collega gli asset versionati di Vite ai template Blade via direttiva `@vite` e `public/build/manifest.json`.

```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

Opzioni: `input`, `refresh` (boolean o array di path), `buildDirectory`, `hotFile`, `detectTls`. Le variabili `VITE_*` del `.env` sono esposte al client via `import.meta.env`. Prefetch asset: `Vite::prefetch(concurrency: 3)` nel `AppServiceProvider`.

---

## 8. @tailwindcss/vite

Plugin Vite ufficiale per Tailwind 4 (preferito a `@tailwindcss/postcss` per integrazione nativa più efficiente).

```js
import tailwindcss from '@tailwindcss/vite';
export default defineConfig({ plugins: [tailwindcss()] });
```

```css
/* resources/css/app.css */
@import "tailwindcss";
```

Ordine raccomandato con Laravel: `laravel(...)` prima, `tailwindcss()` dopo. Nessuna config aggiuntiva: il rilevamento template è automatico.

---

## 9. @tailwindcss/typography

Aggiunge la classe `prose` per stilizzare HTML rich-text (es. Markdown renderizzato).

```css
@import "tailwindcss";
@plugin "@tailwindcss/typography";
```

```html
<article class="prose prose-invert prose-headings:font-outfit prose-a:text-primary">
  {!! $markdownRendered !!}
</article>
```

Dimensioni `prose-sm…prose-2xl`; `prose-invert` per dark; element modifiers (`prose-a:`, `prose-code:`, `prose-img:`). Personalizzazione in v4 via variabili CSS `--tw-prose-*`.

---

## 10. Come questo progetto applica lo stack frontend

### Tailwind 4 — `resources/css/app.css`
Token via `@theme` (font Outfit/Albert Sans/Roboto Mono; palette `primary` cyan, `secondary` violet, `tertiary` fuchsia; scala `surface` su `mist`; breakpoint `--breakpoint-nav`; `--radius: 0.25rem`). Utility custom via `@utility` (`container`, `text-2xs`). `@source` per Blade/JS/paginazione. Effetti visivi (`.bg-grid`, `.bg-dots-pattern`, `.text-gradient`, `.shadow-glow`) e componenti (`.btn`, `.item`) in `@layer utilities`.

### Vite — `vite.config.js`
Pattern corretto per **Sail/Docker**: `host: true`, `hmr.host` override via `VITE_HMR_HOST`, `watch.ignored` sulle viste compilate.

### JavaScript — `resources/js/app.js`
Esposizione globale via `window` (`window.gsap`, `window.Draggable`, `window.SplitText`, `window.motion = { inView, hover }`, `window.FilamentAnimations = { horizontalLoop }`, `window.autoAnimate`), coerente col pattern Blade (script inline nei componenti). Registrazione centralizzata dei plugin GSAP. Helper `horizontalLoop` (loop infinito, usato in `<x-partials.skills-loop>`) e `hover` (ritorna cleanup).

### Mappa scenario → soluzione

| Scenario | Soluzione |
|---|---|
| Hero complessi (timeline) | GSAP + SplitText |
| Loop infinito orizzontale | GSAP (`horizontalLoop`) |
| Trigger scroll | `motion.inView` |
| Liste Livewire add/remove | `autoAnimate` |
| Micro-interazioni (toggle/show) | Alpine `x-show` + `x-transition` |
| Drag | GSAP Draggable |
| Countdown live | Alpine `x-data` + `setInterval` |

Dettagli implementativi: [Design system & frontend](../03_codice/03_design_system_frontend.md).

---

## 11. Risorse per approfondire

**Tailwind 4** — [Annuncio v4](https://tailwindcss.com/blog/tailwindcss-v4) · [Upgrade guide](https://tailwindcss.com/docs/upgrade-guide) · [Vite install](https://tailwindcss.com/docs/installation/using-vite) · [`@theme`](https://tailwindcss.com/docs/theme)

**Alpine 3** — [Start here](https://alpinejs.dev/start-here) · [Directives](https://alpinejs.dev/directives/data) · [Alpine + Livewire 4](https://livewire.laravel.com/docs/4.x/alpine)

**GSAP 3** — [Docs](https://gsap.com/docs/v3/) · [SplitText](https://gsap.com/docs/v3/Plugins/SplitText/) · [Draggable](https://gsap.com/docs/v3/Plugins/Draggable/) · [Pricing (free)](https://gsap.com/pricing/)

**Motion One** — [inView](https://motion.dev/docs/inview) · [Scroll-triggered](https://motion.dev/tutorials/js-scroll-triggered)

**AutoAnimate** — [Sito](https://auto-animate.formkit.com/) · [GitHub](https://github.com/formkit/auto-animate)

**Vite 7** — [Annuncio](https://vite.dev/blog/announcing-vite7) · [Migration](https://vite.dev/guide/migration)

**Laravel Vite** — [Docs](https://laravel.com/docs/12.x/vite) · **Typography** — [GitHub](https://github.com/tailwindlabs/tailwindcss-typography)

---

## 12. Fonti

**Tailwind 4**: https://tailwindcss.com/blog/tailwindcss-v4 · https://tailwindcss.com/docs/upgrade-guide · https://tailwindcss.com/docs/installation/using-vite · https://tailwindcss.com/docs/theme

**Alpine 3**: https://alpinejs.dev/start-here · https://alpinejs.dev/directives/data · https://livewire.laravel.com/docs/4.x/alpine

**GSAP 3**: https://gsap.com/docs/v3/Plugins/SplitText/ · https://gsap.com/docs/v3/Plugins/Draggable/ · https://gsap.com/pricing/

**Motion One**: https://motion.dev/docs/inview · https://motion.dev/tutorials/js-scroll-triggered

**AutoAnimate**: https://auto-animate.formkit.com/ · https://github.com/formkit/auto-animate

**Vite 7**: https://vite.dev/blog/announcing-vite7 · https://vite.dev/guide/migration

**Laravel Vite**: https://laravel.com/docs/12.x/vite

**Typography**: https://github.com/tailwindlabs/tailwindcss-typography
