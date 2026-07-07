# ADR-0005 · Dark theme esclusivo

- **Stato:** Accettata
- **Data:** 2026-06-30 (retroattiva)

## Contesto

L'identità visiva del sito è "cyber/terminal": sfondi scuri, decorazioni geometriche, accenti al neon. La maggioranza degli utenti (stime 2024-2025: ~82% su mobile) ha il dark mode come default di sistema, e una community tecnica legge spesso codice/documentazione in ambienti poco illuminati. Mantenere due temi completi (light + dark) raddoppierebbe le variabili CSS e il testing.

## Decisione

Adottiamo un **dark theme esclusivo** (`<html class="dark">`), senza modalità light né toggle. Il background usa `#0a0a0a` (non nero puro) e la palette è definita una sola volta nei token `@theme` di Tailwind 4.

## Conseguenze

**Positive**
- **Allineamento con la preferenza dominante** e con l'estetica del brand.
- **Un solo design system** da mantenere e testare.
- Riduzione affaticamento visivo in ambienti scuri; risparmio batteria su OLED.
- Scelta di `#0a0a0a` (non `#000`) **riduce l'effetto halation** rispetto al nero puro.

**Negative / trade-off**
- **Astigmatismo/halation**: una quota di utenti trova faticoso il testo chiaro su sfondo scuro → non offrire un'alternativa li penalizza.
- **Esclude** chi preferisce il light mode (stime ~42% in certi contesti).
- **Immagini/loghi** pensati per sfondi chiari vanno adattati.
- **WCAG non è automatico**: ogni colore (testo, focus, placeholder, stati errore) va verificato contro il rapporto di contrasto **4.5:1** (testo normale) / **3:1** (testo grande) — criterio [WCAG 1.4.3], valido anche in dark-only.

## Alternative considerate

- **Dark + Light con toggle** (rispettando `prefers-color-scheme`): massima accessibilità, ma raddoppia design e testing.
- **Dark di default + light opzionale**: compromesso che mantiene il brand con una via di fuga per chi ne ha bisogno (candidato per una futura iterazione di accessibilità).
- **Adattamento automatico all'OS**: richiede comunque entrambi i temi completi.

> **Nota di accessibilità**: questa decisione comporta l'impegno a verificare attivamente il contrasto WCAG su tutti i componenti. Un'eventuale opzione "light" resta un possibile miglioramento futuro.

## Fonti

- [W3C WAI — Understanding SC 1.4.3 (Contrast Minimum)](https://www.w3.org/WAI/WCAG21/Understanding/contrast-minimum.html)
- [BOIA — Dark mode doesn't auto-satisfy WCAG contrast](https://www.boia.org/blog/offering-a-dark-mode-doesnt-satisfy-wcag-color-contrast-requirements)
- [accessibilitychecker.org — Dark mode accessibility guide](https://www.accessibilitychecker.org/blog/dark-mode-accessibility/)

> Token e implementazione: [Design system & frontend](../03_codice/03_design_system_frontend.md).
