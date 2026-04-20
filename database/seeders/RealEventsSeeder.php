<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Partner;
use App\Models\Resource;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RealEventsSeeder extends Seeder
{
    /** Base directory for all seeder source files, relative paths are resolved from here. */
    private string $seedersDir;

    public function __construct()
    {
        $this->seedersDir = database_path('seeders/events');
    }

    public function run(): void
    {
        $categories = EventCategory::query()->pluck('id', 'slug');

        foreach ($this->events() as $data) {
            $categoryNames = $data['categories'];
            unset($data['categories']);

            $partner_names = $data['_partner_names'] ?? [];
            unset($data['_partner_names']);

            $resources = $data['_resources'] ?? [];
            unset($data['_resources']);

            $sourceImage = $data['_source_image'] ?? null;
            unset($data['_source_image']);

            if ($sourceImage !== null) {
                $copied = $this->copyToStorage($sourceImage, '' . pathinfo($sourceImage, PATHINFO_FILENAME) . '-' . $data['slug'] . '.' . pathinfo($sourceImage, PATHINFO_EXTENSION));
                if ($copied !== '') {
                    $data['image_path'] = $copied;
                }
            }

            $event = Event::query()->updateOrCreate(
                ['slug' => $data['slug']],
                $data,
            );

            $categoryIds = collect($categoryNames)
                ->map(fn(string $name) => $categories->get(Str::slug($name)))
                ->filter()
                ->values()
                ->all();

            if (! empty($categoryIds)) {
                $event->categories()->sync($categoryIds);
            }

            if(! empty($partner_names)) {
                $this->seedPartners($event, $partner_names);
            }

            if (! empty($resources)) {
                $this->seedResources($event, $resources);
            }
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $partner_names
     */
    private function seedPartners(Event $event, array $partner_names): void
    {
        $partnerIds = [];

        $partnerIds = Partner::withoutGlobalScopes()
            ->whereIn('name', $partner_names ?? [])
            ->get()
            ->pluck('id');

        $event->partners()->sync($partnerIds);
    }

    /**
     * @param  array<int, array<string, mixed>>  $resources
     */
    private function seedResources(Event $event, array $resources): void
    {
        $resourceIds = [];

        foreach ($resources as $resourceData) {
            $sourceFile = $resourceData['_source_file'] ?? null;
            unset($resourceData['_source_file']);

            if ($sourceFile !== null) {
                $copied = $this->copyToStorage($sourceFile, $resourceData['file_path']);
                $resourceData['file_path'] = $copied !== '' ? $copied : null;
            }

            $resource = Resource::query()->updateOrCreate(
                ['slug' => $resourceData['slug']],
                $resourceData,
            );

            $resourceIds[] = $resource->id;
        }

        $event->resources()->syncWithoutDetaching($resourceIds);
    }

    private function copyToStorage(string $relativeSource, string $storageDest): string
    {
        $source = $this->seedersDir . DIRECTORY_SEPARATOR . $relativeSource;

        if (! file_exists($source)) {
            return '';
        }

        Storage::disk('public')->put($storageDest, file_get_contents($source));

        return $storageDest;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function events(): array
    {
        return [
            [
                'title' => 'Salento.Dev Meetup #0',
                'slug' => 'salento-dev-meetup-0',
                'location' => 'Normal, Taurisano',
                'address' => null,
                'starts_at' => '2025-11-13 19:30:00',
                'ends_at' => null,
                'excerpt' => 'Il primo meetup post rebranding: obiettivi della community e networking davanti a una birra.',
                'body' => <<<'MD'
Il primo meetup ufficiale dopo il rebranding in **Salento.Dev**.

Una serata leggera per conoscersi, raccontare gli obiettivi della community e fare networking davanti a una birra.

## Di cosa parleremo

- Presentazione della community: cosa vogliamo costruire insieme
- Obiettivi per i prossimi mesi
- Networking libero
MD,
                'is_published' => true,
                'work_in_progress' => false,
                'categories' => ['Meet-up', 'Networking'],
                '_source_image' => 'salento-dev-meetup-0.jpg',
            ],

            [
                'title' => 'Lightning Night @ Ruffano',
                'slug' => 'lightning-night-ruffano',
                'location' => 'Convivya Trattoria & Cocktail Bar, Ruffano',
                'address' => 'Corso Margherita di Savoia 8, Ruffano (LE)',
                'starts_at' => '2025-12-29 19:30:00',
                'ends_at' => '2025-12-29 23:00:00',
                'excerpt' => '6 lightning talk da 10-15 minuti nel centro storico di Ruffano, immersi nell\'atmosfera natalizia.',
                'body' => <<<'MD'
Una serata di **lightning talk** nel cuore del centro storico di Ruffano, addobbato per Natale.

6 slot da 10-15 minuti su temi di sviluppo software, ospitati da **Convivya Trattoria & Cocktail Bar**.

## Programma

- **Diagrams as code** — *Paolo Rizzello*
- **Reactylon: Sviluppare esperienze XR con React e Babylon.js** — *Simone De Vittorio*
- **The Auth Chronicles. Part 1** — *Gianluigi De Marco*
- **MyProject life balance** — *Gabriele Rizzo*
- **Droni e modellismo... dal punto di vista Tech** — *Emanuele Paiano*
- **Introduzione alle JS Intl API** — *Giuseppe Ciullo*

## Come funziona

Ogni slot dura 10 minuti, con possibilità di sforare fino a 15 minuti totali (inclusi tempi tecnici e domande). Tutti i talk sono in italiano e a tema sviluppo software.
MD,
                'is_published' => true,
                'work_in_progress' => false,
                'categories' => ['Talk', 'Meet-up'],
                '_source_image' => 'lightning-night-ruffano.jpg',
                '_resources' => [
                    [
                        'title' => 'Diagram as code',
                        'slug' => 'diagram-as-code',
                        'description' => 'Diagram as Code è un approccio ingegneristico alla modellazione visiva che tratta i diagrammi come artefatti testuali versionabili, generati a partire da codice dichiarativo anziché disegnati manualmente. Attraverso linguaggi e tool testuali, i diagrammi diventano parte integrante del codice sorgente: evolvono insieme all’architettura, sono soggetti alle stesse pratiche di qualità e riducono il divario tra sistema reale e documentazione.',
                        'source_type' => 'link',
                        'external_url' => 'https://rizzello.dev/downloads/2025-12-29/diagram_as_code.pdf',
                        'file_path' => null,
                        'image_path' => null,
                        'is_published' => true,
                    ],
                    [
                        'title' => 'Reactylon: Sviluppare esperienze XR con React e Babylon.js',
                        'slug' => 'reactylon-presentation',
                        'description' => 'Multiplatform framework designed to create interactive and immersive XR experiences.',
                        'source_type' => 'link',
                        'external_url' => 'https://canva.link/dcvvj68ziuy0rg9',
                        'file_path' => null,
                        'image_path' => null,
                        'is_published' => true,
                    ],
                    [
                        'title' => 'Droni e modellismo... dal punto di vista Tech',
                        'slug' => 'droni-e-modellismo-dal-punto-di-vista-tech',
                        'description' => 'Slide del lightning talk di Emanuele Paiano alla Lightning Night @ Ruffano (29 dicembre 2025).',
                        'source_type' => 'file',
                        'external_url' => null,
                        'file_path' => 'resources/files/droni-e-modellismo.pdf',
                        'image_path' => null,
                        'is_published' => true,
                        '_source_file' => 'droni-e-modellismo.pdf',
                    ],
                    
                ],
            ],

            [
                'title' => 'AperiDev @ Galatina',
                'slug' => 'aperidiv-galatina',
                'location' => 'Madrè Drinks & Cucina, Galatina',
                'address' => 'Corso Garibaldi 8, Galatina (LE)',
                'starts_at' => '2026-02-19 20:00:00',
                'ends_at' => null,
                'excerpt' => 'Un appuntamento leggero davanti a una birra: si parla del più e del meno, tra sviluppatori.',
                'body' => <<<'MD'
Un **AperiDev** a Galatina: un appuntamento informale davanti a una birra per parlare del più e del meno tra sviluppatori.

Niente slide, niente agenda fissa. Solo buona compagnia, chiacchiere tech e networking in libertà.

> Per una questione di prenotazione posti, è gradita conferma di partecipazione.
MD,
                'is_published' => true,
                'work_in_progress' => false,
                'categories' => ['Networking'],
                '_source_image' => 'aperidiv-galatina.jpg',
            ],

            [
                'title' => 'AI & Coding: come sta cambiando il modo di programmare',
                'slug' => 'ai-coding-come-sta-cambiando-il-modo-di-programmare',
                'location' => 'Officine Cantelmo, Lecce',
                'address' => 'Officine Cantelmo, Lecce',
                'starts_at' => '2026-03-23 18:30:00',
                'ends_at' => null,
                'excerpt' => 'Un panel con professionisti del settore per discutere di come l\'AI sta trasformando workflow, produttività e qualità del codice.',
                'body' => <<<'MD'
L'intelligenza artificiale sta trasformando il modo in cui gli sviluppatori scrivono codice e affrontano il lavoro quotidiano. Ma quanto e in che modo sta davvero cambiando workflow, produttività e qualità del codice?

Ne parliamo in un **panel organizzato insieme a Google Developer Group Lecce**, con il contributo di professionisti del settore.

## Speaker

- **Giuseppe Mastrandrea** — AI Teacher @ DataMasters
- **Biagio Nuzzo** — CEO @ Hybris Hub
- **Paolo Rizzello** — CEO @ Dinamica Tech
- **Michelantonio Trizio** — CTO @ Wideverse

*Modera Raffaele Grieco, GDG Lecce Organizer.*

🎟️ Partecipazione gratuita con registrazione obbligatoria.
MD,
                'is_published' => true,
                'work_in_progress' => false,
                'categories' => ['Talk'],
                '_source_image' => 'ai-coding-come-sta-cambiando-il-modo-di-programmare.jpg',
            ],

            [
                'title' => 'Bowling Meetup con Lecce Digital Community',
                'slug' => 'bowling-meetup-lecce-digital-community',
                'location' => 'Bowling Surbo, Surbo',
                'address' => 'SS613 Km 32, Surbo',
                'starts_at' => '2026-03-27 19:30:00',
                'ends_at' => null,
                'excerpt' => 'Una serata di bowling e networking informale insieme a Lecce Digital Community.',
                'body' => <<<'MD'
Ci uniamo a **Lecce Digital Community** per una serata di bowling, chiacchiere e networking informale.

Una location ampia con 16 piste e oltre 3.000 mq di spazio, dove sarà anche possibile cenare e bere qualcosa.

> Scaldate i polpastrelli, indossate i calzini buoni e aguzzate la vista: si va a giocare a bowling!
MD,
                'is_published' => true,
                'work_in_progress' => false,
                'categories' => ['Networking'],
                '_source_image' => 'bowling-meetup-lecce-digital-community.jpg',
                '_partner_names' => ['Lecce Digital Community'],
            ],

            [
                'title' => 'GitHub Copilot Dev Days Lecce',
                'slug' => 'github-copilot-dev-days-lecce',
                'location' => 'Officine Cantelmo - Mediateca, Lecce',
                'address' => 'Via Corte dei Mesagnesi 30, Lecce',
                'starts_at' => '2026-03-30 18:00:00',
                'ends_at' => null,
                'excerpt' => 'Una tappa dei GitHub Copilot Dev Days con sessioni pratiche sull\'AI-assisted coding, con due speaker di Microsoft.',
                'body' => <<<'MD'
Arriva a Lecce una tappa dei **GitHub Copilot Dev Days**, l'iniziativa globale community-led dedicata all'AI-assisted coding con GitHub Copilot.

> 🤝 Evento promosso da un partner della community.

Durante la serata ci saranno momenti di approfondimento e una sessione pratica dedicata all'utilizzo di GitHub Copilot in Visual Studio Code.

## Speaker

- **Andrea Giunta** — Software Engineer II @ Microsoft
- **Carlotta Castelluccio** — Sr AI Advocate @ Microsoft

🎟️ Partecipazione gratuita con registrazione obbligatoria.
MD,
                'is_published' => true,
                'work_in_progress' => false,
                'categories' => ['Workshop', 'Talk'],
                '_source_image' => 'github-copilot-dev-days-lecce.jpg',
                '_partner_names' => ['GDG Lecce'],
            ],

            [
                'title' => 'Global Azure Puglia 2026',
                'slug' => 'global-azure-puglia-2026',
                'location' => 'Ugolopez.it, Bari',
                'address' => 'Via Aurelio Carrante 1/F, Bari',
                'starts_at' => '2026-04-17 08:30:00',
                'ends_at' => null,
                'excerpt' => 'Prima edizione pugliese del Global Azure: una giornata dedicata a cloud, Azure, data e AI con due track parallele e oltre 12 sessioni.',
                'body' => <<<'MD'
Il **Global Azure** arriva per la prima volta in Puglia con una giornata interamente dedicata a cloud, Azure, data e intelligenza artificiale.

> 🤝 Evento promosso da un partner della community.

Un'occasione per incontrarsi di persona, seguire sessioni tecniche, confrontarsi con altri professionisti del settore e fare networking.

## Cosa aspettarsi

- Due track parallele
- Oltre 12 sessioni tecniche
- Keynote di apertura
- Workshop pratico

Consulta il programma completo qui: [Global Azure Puglia 2026 | 17 Aprile - Bari | 2 Track, 12+ Sessioni](https://azure-meetup-puglia.github.io/global-azure-puglia)

🎟️ Evento gratuito con registrazione obbligatoria e posti limitati.
MD,
                'is_published' => true,
                'work_in_progress' => false,
                'categories' => ['Talk', 'Workshop'],
                '_source_image' => 'global-azure-puglia-2026.jpg',
                '_partner_names' => ['Azure Meetup Puglia'],
            ],
        ];
    }
}
