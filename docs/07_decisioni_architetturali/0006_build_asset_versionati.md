# ADR-0006 · Asset di build versionati nel repository

- **Stato:** Accettata (da rivalutare)
- **Data:** 2026-06-30 (retroattiva)

## Contesto

Laravel/Vite di default inserisce `public/build` nel `.gitignore`: gli asset compilati si generano in fase di deploy. Questo progetto invece **committa** `public/build` (manifest + JS/CSS con hash). La scelta ha senso quando l'ambiente di produzione non dispone di Node.js o di una pipeline CI, e si vuole un deploy basato su `git pull`.

## Decisione

**Versioniamo** gli asset di build (`public/build/`) nel repository. Il deploy può quindi avvenire con un semplice `git pull`, senza eseguire `npm run build` sul server.

## Conseguenze

**Positive**
- **Deploy semplice**: `git pull` aggiorna anche gli asset; rollback = checkout di un commit precedente.
- **Zero dipendenze Node.js in produzione**.
- **Riproducibilità**: ogni commit corrisponde a uno stato completo dell'app.

**Negative / trade-off**
- **Diff rumorosi**: ogni `npm run build` rigenera file con nuovi hash → le PR contengono grandi diff machine-generated (già osservato in questo repo).
- **Merge conflict** sui file compilati, non risolvibili manualmente.
- **Repository più pesante** nel tempo.
- **Rischio di incoerenza**: se si dimentica il rebuild, il sorgente è aggiornato ma gli asset no.

> Questo è un **debito tecnico noto**: vedi [Note e debito tecnico](../04_sviluppo_e_qualita/04_note_e_debito_tecnico.md). Lo `Stato` è "da rivalutare" perché l'introduzione di una CI renderebbe preferibile l'alternativa.

## Alternative considerate

- **Build in CI/CD** (approccio standard): la pipeline esegue `npm run build`; gli asset non si committano. Richiede una CI (oggi assente).
- **Build su server via deployment hook** (es. Laravel Forge): build automatica al deploy, senza committare.
- **Artifact repository**: la CI pubblica gli asset compilati come artifact scaricati al deploy.

## Decisione di revisione suggerita

Quando verrà introdotta una CI (vedi proposta nelle note tecniche), **superare** questa decisione con un nuovo ADR che sposta `public/build` in `.gitignore` e demanda la build alla pipeline.

## Fonti

- [Laracasts — /public/build in .gitignore by default](https://laracasts.com/discuss/channels/vite/publicbuild-is-listed-in-gitignore-by-default)
- [Kent C. Dodds — Why I don't commit generated files](https://kentcdodds.com/blog/why-i-dont-commit-generated-files-to-master)
- [laravel/framework — Discussion #42612](https://github.com/laravel/framework/discussions/42612)
