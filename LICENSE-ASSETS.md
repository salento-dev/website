# Licenze degli asset

Il codice di questo repository è rilasciato sotto licenza [MIT](LICENSE). Alcuni materiali hanno licenze
diverse, in linea con il repository del brand [`salento-dev/brand`](https://github.com/salento-dev/brand),
che è la fonte di verità per logo, colori e regole d'uso
([`LICENSE-ASSETS.md`](https://github.com/salento-dev/brand/blob/main/LICENSE-ASSETS.md)).

## 1. Logo, wordmark e nome «Salento.dev» — tutti i diritti riservati

Copyright © 2026 Salento.dev. Tutti i diritti riservati.

Il logo, il wordmark e il nome «Salento.dev» sono segni distintivi protetti e **non** sono coperti dalla
licenza MIT. Qualsiasi utilizzo è subordinato a preventiva autorizzazione e deve rispettare le
[linee guida ufficiali](https://salento-dev.github.io/brand/#logo). Per chiedere l'autorizzazione:
salento.dev@gmail.com.

Rientrano qui, tra gli altri:

- `public/assets/logo.svg`;
- il componente `resources/views/components/ui/logo.blade.php` e ogni altra copia del logo nel sito;
- `public/favicon.ico`.

Se riusi questo codice per un altro progetto, **sostituisci logo e nome** con i tuoi.

## 2. Contenuti di terzi — dei rispettivi titolari

Foto degli eventi, loghi dei partner, immagini delle storie e materiali allegati (in `database/seeders/events/`,
`database/seeders/partners/`, `database/seeders/stories/` e quelli caricati dal pannello admin) appartengono ai
rispettivi autori e titolari. Sono inclusi solo per popolare l'ambiente di sviluppo con dati realistici e
**non** sono concessi in licenza: non riutilizzarli fuori da questo progetto.

Lo stesso vale per i nomi, i ruoli e i profili pubblici delle persone presenti nei seeder (speaker, autori,
protagonisti delle storie), ripresi da quanto già pubblicato sul sito. Se compari nei seeder e vuoi essere
rimosso, scrivi a salento.dev@gmail.com.

## 3. Documentazione — CC BY 4.0

La documentazione in `docs/` è rilasciata sotto
**Creative Commons Attribution 4.0 International (CC BY 4.0)**:
https://creativecommons.org/licenses/by/4.0/legalcode.it

Attribuzione suggerita: «Documentazione di Salento.dev (https://salento.dev), CC BY 4.0».

## 4. Font e icone di terze parti

| Materiale | Dove | Licenza |
|---|---|---|
| Outfit, Albert Sans, Roboto Mono | `resources/fonts/` | SIL Open Font License 1.1 (testi in `resources/fonts/OFL-*.txt`) |
| Inter (pannello admin, pubblicato da Filament) | `public/fonts/filament/` | SIL Open Font License 1.1 |
| Heroicons | `public/assets/*.svg`, componenti `x-heroicon-*` | MIT |
| GitHub Octicons | `public/assets/octo.svg` | MIT |
| Simple Icons (icone dei social) | pacchetto `ublabs/blade-simple-icons` | CC0 1.0; i marchi raffigurati restano dei rispettivi titolari |
| Asset JS/CSS di Filament | `public/js/filament/`, `public/css/filament/` | MIT |

Le dipendenze installate con Composer e npm mantengono le proprie licenze.
