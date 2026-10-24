---
name: Salento.dev
description: Sito della community di sviluppatori del Salento — console notturna, cyan su nero.
colors:
  signal-cyan: "oklch(71.5% 0.143 215.221)"
  signal-cyan-deep: "oklch(52% 0.105 223.128)"
  signal-cyan-pale: "oklch(86.5% 0.127 207.078)"
  signal-cyan-wash: "oklch(98.4% 0.019 200.873)"
  reserve-violet: "oklch(60.6% 0.25 292.717)"
  reserve-fuchsia: "oklch(66.7% 0.295 322.15)"
  void-black: "#000000"
  console-black: "oklch(14.8% 0.004 228.8)"
  panel-graphite: "oklch(21.8% 0.008 223.9)"
  control-graphite: "oklch(27.5% 0.011 216.9)"
  raised-graphite: "oklch(37.8% 0.015 216)"
  bracket-grey: "oklch(45% 0.017 213.2)"
  muted-mist: "oklch(72.3% 0.014 214.4)"
  body-mist: "oklch(87.2% 0.007 219.6)"
  soft-mist: "oklch(92.5% 0.005 214.3)"
  bright-mist: "oklch(98.7% 0.002 197.1)"
  whatsapp-green: "oklch(79.2% 0.209 151.711)"
  wip-amber: "oklch(76.9% 0.188 70.08)"
typography:
  display:
    fontFamily: "Outfit, Albert Sans, sans-serif"
    fontSize: "4.5rem"
    fontWeight: 700
    lineHeight: 1.1
    letterSpacing: "-0.025em"
  display-page:
    fontFamily: "Outfit, Albert Sans, sans-serif"
    fontSize: "4.5rem"
    fontWeight: 900
    lineHeight: 1.25
    letterSpacing: "-0.05em"
  headline:
    fontFamily: "Outfit, Albert Sans, sans-serif"
    fontSize: "3rem"
    fontWeight: 700
    lineHeight: 1
  title:
    fontFamily: "Outfit, Albert Sans, sans-serif"
    fontSize: "1.125rem"
    fontWeight: 700
    lineHeight: 1.5
  body:
    fontFamily: "Albert Sans, Verdana, sans-serif"
    fontSize: "1rem"
    fontWeight: 400
    lineHeight: 1.5
  body-lead:
    fontFamily: "Albert Sans, Verdana, sans-serif"
    fontSize: "1.125rem"
    fontWeight: 400
    lineHeight: 1.625
  eyebrow:
    fontFamily: "Albert Sans, Verdana, sans-serif"
    fontSize: "0.75rem"
    fontWeight: 600
    letterSpacing: "0.05em"
  telemetry:
    fontFamily: "Roboto Mono, ui-monospace, monospace"
    fontSize: "0.625rem"
    fontWeight: 400
    lineHeight: 1
    letterSpacing: "0.1em"
rounded:
  xs: "0.125rem"
  sm: "0.25rem"
  lg: "0.375rem"
  full: "9999px"
spacing:
  bracket: "0.625rem"
  control: "2.5rem"
  card: "1.25rem"
  page-x: "4rem"
  section: "5rem"
  hero-y: "8rem"
components:
  button-cta:
    backgroundColor: "{colors.bright-mist}"
    textColor: "{colors.panel-graphite}"
    rounded: "{rounded.full}"
    padding: "0 5px 0 20px"
    height: "3.25rem"
  button-cta-icon:
    backgroundColor: "{colors.signal-cyan-wash}"
    textColor: "{colors.panel-graphite}"
    rounded: "{rounded.full}"
    size: "2.75rem"
  button-utility:
    backgroundColor: "{colors.panel-graphite}"
    textColor: "{colors.bright-mist}"
    rounded: "{rounded.xs}"
    padding: "0 12px"
    height: "2.5rem"
  button-utility-hover:
    backgroundColor: "{colors.control-graphite}"
  input-search:
    backgroundColor: "{colors.control-graphite}"
    textColor: "{colors.bright-mist}"
    rounded: "{rounded.xs}"
    height: "2.75rem"
  category-tag:
    backgroundColor: "{colors.control-graphite}"
    textColor: "{colors.soft-mist}"
    typography: "{typography.telemetry}"
    rounded: "{rounded.xs}"
    padding: "0 8px"
    height: "1.5rem"
  status-pill-upcoming:
    textColor: "{colors.signal-cyan}"
    typography: "{typography.telemetry}"
    rounded: "{rounded.full}"
    padding: "4px 12px"
  status-pill-wip:
    textColor: "{colors.wip-amber}"
    typography: "{typography.telemetry}"
    rounded: "{rounded.full}"
    padding: "4px 12px"
  card-event:
    backgroundColor: "{colors.panel-graphite}"
    textColor: "{colors.body-mist}"
    rounded: "{rounded.xs}"
    padding: "{spacing.card}"
  nav-link:
    textColor: "{colors.body-mist}"
    padding: "6px 8px"
  nav-link-active:
    textColor: "{colors.bright-mist}"
---

# Design System: Salento.dev

## Overview

**Creative North Star: "The Night Console"**

Il sito è un pannello di controllo acceso di notte. Un telaio nero punteggiato fa da banco; sopra, un unico pannello incorniciato da bordi a 1px ospita tutto il contenuto, diviso in moduli da linee sottili e piccoli triangoli di giunzione. Dentro il pannello la luce è una sola: il cyan, usato come spia accesa — un'etichetta, un link, lo stato "prossimo evento" che pulsa. Tutto il resto è grafite e nebbia.

La densità è media e ordinata: titoli grandi in Outfit, testo di lettura in Albert Sans, e una fascia di etichette mono maiuscole e spaziate che leggono come telemetria (`root@salento.dev`, `Upcoming: 3`, `Events // Community`). Le staffe d'angolo SVG sono la firma: marcano le card come mirini, e all'hover si allargano o compaiono. A riposo l'interfaccia è ferma; all'interazione risponde con piccoli movimenti GSAP precisi.

Il sistema è esclusivamente scuro. Non esiste una variante chiara e non va inventata.

**Key Characteristics:**
- Telaio nero a puntini + pannello centrale con bordi 1px e giunzioni triangolari.
- Un solo accento attivo (cyan); violet e fuchsia sono riserva.
- Profondità solo tonale: nessuna ombra.
- Angoli quasi vivi; pill solo per CTA, badge e stati.
- Etichette mono maiuscole come voce "di sistema".
- Staffe d'angolo come firma interattiva.

## Colors

Grafite fredda appena tinta di blu (famiglia Tailwind `mist`), con una sola spia cyan.

### Primary
- **Signal Cyan** (`primary`, cyan-500): eyebrow di sezione, parola chiave nei titoli hero, link d'azione, icone, punto pulsante del badge, testo dello stato "prossimo evento", staffe d'angolo delle card in evidenza.
- **Signal Cyan Deep** (`primary-dark`, cyan-700): hover dei link primary; `primary-800` riempie la barra "Read" della card evento all'hover.
- **Signal Cyan Pale** (`primary-light`, cyan-300) e **Signal Cyan Wash** (cyan-50): staffe della navbar e cerchio icona della CTA. Usi minori, mai su grandi aree.

### Secondary
- **Reserve Violet** (`secondary`, violet-500): riserva. Oggi appare solo nell'hover del bottone "Clear" della ricerca.

### Tertiary
- **Reserve Fuchsia** (`tertiary`, fuchsia-500): riserva, attualmente inutilizzato.

### Neutral
- **Void Black** (#000): telaio della pagina dietro il pannello (`bg-black` + `.bg-dots-pattern`) e corpo del terminale.
- **Console Black** (`surface`, mist-950): sfondo del pannello principale, header, hero.
- **Panel Graphite** (`surface-900`): card e moduli; anche lo sfondo di `.btn` e `.item`.
- **Control Graphite** (`surface-800`, = `border`): input, tag, chip; è anche il colore di tutti i bordi e delle giunzioni triangolari.
- **Raised Graphite** (`surface-700`): stato attivo di `.btn`, barra d'azione delle card future, ring delle miniature.
- **Bracket Grey** (`surface-600`): staffe d'angolo a riposo, decorazioni.
- **Muted Mist** (`surface-400`): meta info, descrizioni di sezione, conteggi.
- **Body Mist** (`surface-300`): colore del testo corrente del body.
- **Soft Mist** (`surface-200`): colore base degli heading (regola globale in `@layer base`), testo dei tag.
- **Bright Mist** (`surface-50`): titoli di sezione, testo enfatizzato, sfondo della CTA pill.

### Functional
- **WhatsApp Green** (green-400/500): il verde Tailwind più vicino al marchio WhatsApp. Riservato al canale WhatsApp (link nell'header, spark-link). Lo stato "In corso" oggi lo riusa.
- **WIP Amber** (amber-500 al 20%, testo amber-200): solo eventi in pianificazione (status pill e barra "Coming Soon" della card).

### Named Rules
**The One Signal Rule.** Il cyan è l'unica luce accesa. Va su etichette, link, stati e una parola per titolo, mai su sfondi pieni di grandi superfici. Se una schermata ha più di una manciata di elementi cyan, uno è di troppo.

**The Reserve Rule.** Violet e fuchsia esistono come token ma non sono voci attive. Non introdurli in nuovo lavoro senza una decisione esplicita.

**The Legacy Amber Rule.** Ambra e arancio appartenevano a una palette precedente: le relative utility glow, gradient e ombra sono state rimosse da `app.css`. Non reintrodurre glow, gradienti o ombre ambra/arancio.

**The Brand-Borrow Rule.** Verde e ambra sono prestiti funzionali (canale WhatsApp, WIP), non accenti di marca. Mai come decorazione.

## Typography

**Display Font:** Outfit (con Albert Sans, sans-serif)
**Body Font:** Albert Sans (con Verdana, sans-serif)
**Label/Mono Font:** Roboto Mono (`font-mono`, alias `font-roboto-mono`), con ui-monospace

**Character:** Outfit geometrico e compatto per titoli che pesano; Albert Sans morbido e leggibile per il corpo; il mono maiuscolo spaziato aggiunge la voce "da console".

### Hierarchy
- **Display** (700, 3rem → 4.5rem, line-height 1.1, tracking stretto): hero della home, con la prima riga in cyan.
- **Display Page** (900, 3rem → 4.5rem, maiuscolo, line-height 1.25, tracking molto stretto): hero delle pagine interne (`page-hero`), una parola in cyan.
- **Headline** (700, 2.25rem → 3rem): titolo di `section-header`; variante small 1.5rem → 1.875rem.
- **Title** (500–700, 1.125rem): titoli di card evento e liste.
- **Body** (400, 1rem, 1.5): testo corrente in Body Mist; estratti card a 0.875rem.
- **Prosa lunga** (dettaglio evento e storia): `prose prose-invert prose-mist`, che mappa i colori del plugin typography sui token surface/primary.
- **Body Lead** (300–400, 1.125rem, 1.625): descrizioni hero, max ~42rem (`max-w-2xl`).
- **Eyebrow** (600, 0.75rem, maiuscolo, tracking 0.05em): sopra i titoli di sezione, sempre in cyan.
- **Telemetry** (mono, 0.625rem, maiuscolo, tracking 0.1em): status pill, tag di sezione, header del terminale, badge `page-hero` (qui con tracking 0.3em).

### Named Rules
**The Telemetry Voice Rule.** Le etichette di sistema (stati, conteggi, percorsi, categorie) sono mono maiuscolo spaziato a 0.625rem. Il testo che si legge davvero non è mai mono.

**The One Mono Rule.** `font-mono` e `font-roboto-mono` puntano entrambi a Roboto Mono (self-hosted in `resources/fonts/`, dichiarato con `@font-face` in `app.css`). Nuovo lavoro usa `font-mono`; non introdurre altri monospace.

## Layout

- **Telaio:** body nero con pattern a puntini, padding laterale 0 / 1.25rem / 2.5rem (mobile / sm / lg).
- **Pannello:** un unico `main` centrato, larghezza max 87.5rem (`max-w-350`, = breakpoint `max`), con bordi sinistro/destro/inferiore a 1px. Tutto vive dentro.
- **Moduli:** header sticky (altezza 4rem), hero, contenuto, footer separati da bordi orizzontali a 1px con giunzioni triangolari 5px (`data-triangle-decoration`).
- **Padding pagina:** orizzontale 1.5rem → 2rem → 4rem; verticale contenuto 3rem → 4rem → 5rem. Hero 6rem → 8rem verticale, testo centrato in max 56rem.
- **Ritmo:** sezioni separate da 5rem (`gap-20`); header di sezione 2rem sopra il contenuto.
- **Griglie:** liste di card in auto-fill con minimo 20rem e gap 1.75rem; coppie in 2 colonne da `md`.
- **Breakpoint:** Tailwind standard più `nav` a 50rem (cambio navbar desktop/mobile).

## Elevation & Depth

Piatto, solo toni. La profondità nasce dalla scala nero → mist-950 → 900 → 800 → 700 e dai bordi a 1px in Control Graphite. Nessuna ombra nel sistema (vedi The Legacy Amber Rule). Le immagini in evidenza si separano con overlay a gradiente verso Panel Graphite, non con ombre.

### Named Rules
**The Tonal Step Rule.** Un elemento che deve "salire" passa al gradino grafite successivo o prende un bordo 1px. Mai un box-shadow.

**The Hover Tint Rule.** L'hover di una superficie si esprime con un velo cyan al 5% (`primary-500/5`) o un gradino tonale, non con elevazione.

## Shapes

Angoli quasi vivi: 0.125rem (`rounded-xs`) è il default per card, input, tag, bottoni utility. Il token `--radius` vale 0.25rem; `rounded-lg` e superiori sono praticamente assenti (un uso ciascuno). Le pill (`rounded-full`) sono riservate a CTA, badge e status pill.

Forme ricorrenti:
- **Staffe d'angolo:** SVG a L 11×11 con tratto 1px arrotondato, in Bracket Grey a riposo o cyan in evidenza (`<x-ui.corners>`, card evento, navbar).
- **Giunzioni triangolari:** triangoli 5px in Control Graphite agli estremi dei bordi orizzontali di modulo.
- **Angolo tagliato:** `data-corner-cut` (0.625rem, sm 0.375rem, lg 0.9rem) disponibile via clip-path; poco usato.

## Components

### Buttons
Precisi e reattivi: fermi a riposo, vivi all'hover.
- **CTA pill (`<x-ui.button>`):** pill alta 3.25rem, sfondo Bright Mist, testo grafite scuro, padding 1.25rem a sinistra e cerchio icona 2.75rem (Signal Cyan Wash) con freccia a destra. Hover: freccia che esce ed entra (GSAP), caratteri che scintillano in sequenza, leggero stretch verticale, pulse che si espande di 8px. Varianti `primary` (velo cyan) e `secondary` (Console Black).
- **Utility (`.btn`):** rettangolo 2.5rem con angoli 0.125rem, Panel Graphite → Control Graphite all'hover, scale 0.95 all'active, ring cyan al 50% su focus-visible. Taglie `btn-sm` (2rem) e `btn-large` (3rem). Stato `.active` in Raised Graphite.

### Chips
- **Category tag:** altezza 1.5rem, Control Graphite, testo telemetria in Soft Mist al 70%.
- **Tag inline in card:** altezza 1.75rem, Control Graphite, 0.75rem medium.
- **Status pill:** pill telemetria con bordo e sfondo al 10–30% del colore di stato e puntino `animate-ping`. Upcoming = cyan, In corso = verde, WIP = ambra, Passato = Raised Graphite senza puntino.
- **Badge (`<x-ui.badge>`):** pill con bordo 1px, sfondo Console Black al 50%, testo Muted Mist, puntino cyan pulsante.

### Cards / Containers
- **Card evento:** Panel Graphite, angoli 0.125rem, padding 1.25rem. Miniatura 16:9 con ring Raised Graphite tra due staffe d'angolo opposte. Hover: velo cyan 5%, titolo che scorre di 1.125rem con indicatore animato e vira al cyan, barra d'azione "Read" che passa a cyan profondo.
- **Card in evidenza:** immagine a tutta card con doppio gradiente verso Panel Graphite, staffe cyan che si allargano all'hover, status pill e countdown in alto.
- **Terminale (`<x-ui.terminal>`):** corpo nero, bordo 1px, barra Panel Graphite con tre puntini semaforo e percorso `root@salento.dev:~/…` in telemetria cyan.

### Inputs / Fields
- **Search:** altezza 2.75rem, Control Graphite, angoli 0.125rem, lente disegnata a mano che si specchia al focus, messaggio rotante a destra al focus, bottone "Clear" che compare con scale. Debounce 250ms verso Livewire.
- **Focus:** ring inset; il sistema preferisce ring a outline.

### Navigation
- **Desktop:** link testuali Body Mist, padding 0.375rem 0.5rem. Hover: diventano Bright Mist medium, label scala a 0.9 e due staffe cyan entrano dagli angoli (GSAP). Attivo: staffe fisse, testo Bright Mist.
- **Header:** sticky, 4rem, celle separate da bordi verticali 1px; a destra il link WhatsApp verde con scintille all'hover.
- **Mobile (sotto 50rem):** WhatsApp a sinistra, logo al centro, menu a destra.

### Section Header (firma)
Eyebrow cyan, titolo Headline in Bright Mist, a destra un link cyan con chevron o un tag telemetria bordato (`Upcoming: 3`). Descrizione in Muted Mist sotto.

## Do's and Don'ts

### Do:
- **Do** tenere tutto dentro il pannello bordato: nuove sezioni sono moduli separati da bordi 1px, non blocchi flottanti.
- **Do** usare il cyan per una parola per titolo, eyebrow, link e stati attivi, e basta.
- **Do** dare profondità con gradini grafite (950 → 900 → 800 → 700) e bordi in Control Graphite.
- **Do** usare angoli 0.125rem di default e pill solo per CTA, badge e status.
- **Do** scrivere etichette di sistema in mono maiuscolo 0.625rem spaziato.
- **Do** usare le staffe d'angolo come cornice e segnale d'hover delle superfici cliccabili.
- **Do** animare con GSAP brevi (0.2–0.5s, `power2`/`circ.out`) innescati da hover/focus.

### Don't:
- **Don't** introdurre un tema chiaro o superfici chiare estese (la CTA pill è l'unica eccezione).
- **Don't** usare box-shadow, alone o glow.
- **Don't** usare gradienti o glow sui titoli: l'accento è una parola in cyan pieno.
- **Don't** attivare violet o fuchsia come accenti senza una decisione esplicita.
- **Don't** usare verde o ambra fuori da WhatsApp/live e WIP.
- **Don't** usare `rounded-lg` o raggi maggiori su card e contenitori.
- **Don't** hardcodare colori Tailwind grezzi (`cyan-500`, `zinc-*`): usa i token semantici `primary`, `surface-*`, `border`.
