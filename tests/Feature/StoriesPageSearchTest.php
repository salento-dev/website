<?php

namespace Tests\Feature;

use App\Livewire\StoriesPage;
use App\Models\Author;
use App\Models\Story;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class StoriesPageSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_matches_stories_by_title_excerpt_and_author_name(): void
    {
        $byTitle = Story::factory()->create(['title' => 'Duomo di Lecce in 3D', 'excerpt' => 'Gaussian Splatting.', 'is_published' => true]);
        $byExcerpt = Story::factory()->create(['title' => 'Cluster in casa', 'excerpt' => 'Kubernetes su tre Raspberry.', 'is_published' => true]);
        $byAuthor = Story::factory()->create(['title' => 'Bot per il meetup', 'excerpt' => 'Un bot Telegram.', 'is_published' => true]);
        $byAuthor->authors()->attach(Author::factory()->create(['name' => 'Giulia Taurisano']));

        Livewire::test(StoriesPage::class)
            ->set('search', 'Duomo')
            ->assertOk()
            ->assertViewHas('stories', fn ($stories): bool => $stories->pluck('id')->all() === [$byTitle->id])
            ->set('search', 'Kubernetes')
            ->assertViewHas('stories', fn ($stories): bool => $stories->pluck('id')->all() === [$byExcerpt->id])
            ->set('search', 'Giulia')
            ->assertViewHas('stories', fn ($stories): bool => $stories->pluck('id')->all() === [$byAuthor->id]);
    }
}
