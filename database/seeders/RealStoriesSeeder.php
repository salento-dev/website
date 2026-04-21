<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Story;
use App\Models\StoryCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class RealStoriesSeeder extends Seeder
{
    private string $sourceDir;

    public function __construct()
    {
        $this->sourceDir = database_path('seeders/stories');
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryEsperimento = StoryCategory::query()->where('slug', 'esperimento')->firstOrFail();

        $story = Story::query()->updateOrCreate(
            ['slug' => 'storie-dalla-community-00'],
            [
                'story_category_id' => $categoryEsperimento->id,
                'title' => 'Il Duomo di Lecce in 3D navigabile con Gaussian Splatting e Reactylon',
                'excerpt' => 'Simone De Vittorio ha ricreato il Duomo di Lecce come ambiente 3D navigabile da browser, partendo da un video e costruendo una pipeline video → frame → ricostruzione → navigazione interattiva con Reactylon e Gaussian Splatting.',
                'body' => <<<'MARKDOWN'
Con **Storie dalla Community** vogliamo raccontare qualcosa di concreto emerso dentro salento.dev: un progetto, un esperimento, un ragionamento tecnico, un confronto che vale la pena fermare e condividere.

Per questa prima storia partiamo da un progetto personale di **Simone De Vittorio**, di cui abbiamo parlato in community.

## Il progetto

Simone ha lavorato a un esperimento che mette insieme **Gaussian Splatting** e **Reactylon**: partendo da un video, ha ricreato un ambiente 3D navigabile del Duomo di Lecce, esplorabile direttamente da browser.

La parte interessante, però, non è solo il risultato visivo. È la **pipeline**: video → estrazione dei frame → ricostruzione → raffinamento → navigazione interattiva nel browser. E soprattutto il fatto che sia stata costruita con un approccio pratico e low cost, lavorando in locale e tenendo insieme performance, privacy e costi di elaborazione.

## Cosa è emerso dalla discussione

Nel confronto in community sono emersi diversi spunti interessanti:

- **Trasformazione da video a scena 3D navigabile** — la pipeline completa dalla registrazione alla navigazione browser
- **Miglioramenti alla navigazione** — incluso il supporto ai tasti WASD
- **Pannelli informativi** — integrati nella scena 3D
- **Gestione manuale di collisioni, camera e punti di interesse** dentro Reactylon
- **Casi d'uso concreti** — showcase interattivi, real estate, visite virtuali
- **Limiti attuali su WebXR e visori** — sia per peso computazionale sia per il modo in cui la UI HTML va ripensata in ambienti XR

C'è anche un altro aspetto che ha reso la discussione interessante: l'idea di costruire una pipeline ragionando in termini pratici, quindi lavorando in locale e tenendo insieme performance, privacy e costi di elaborazione.

## Reactylon

Per chi non conoscesse Reactylon, è un **framework open source** basato su Babylon.js e React, pensato per creare esperienze 3D e XR interattive con un approccio dichiarativo. Vale la pena aggiungere un dettaglio non da poco: Reactylon è un framework creato e mantenuto proprio da Simone.

> Se siete da desktop, potete provare la demo: [reactylon.com/showcase/duomo](https://www.reactylon.com/showcase/duomo)

Complimenti a Simone De Vittorio per il lavoro e per aver condiviso spunti che hanno acceso una conversazione interessante.

Se lo provate, scrivete nei commenti come gira sulla vostra macchina: fluidità, lag, qualità visiva, compatibilità. Spesso la parte più interessante non è solo la demo, ma tutto quello che la community riesce a costruirci attorno.
MARKDOWN,
                'image_path' => $this->copyImage('duomo-lecce.jpeg'),
                'is_published' => true,
                'published_at' => '2026-04-16 00:00:00',
            ],
        );

        $story->protagonists()->sync(Author::whereIn('name', ['Simone De Vittorio'])->get()->pluck('id'));

        $story->authors()->sync(Author::whereIn('name', ['Paolo Rizzello'])->get()->pluck('id'));
    }

    private function copyImage(string $filename): string
    {
        $source = $this->sourceDir.DIRECTORY_SEPARATOR.$filename;

        if (! file_exists($source)) {
            return '';
        }

        $destination = 'stories/'.$filename;

        Storage::disk('public')->put(
            $destination,
            file_get_contents($source),
        );

        return $destination;
    }
}
