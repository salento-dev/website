<?php

namespace Tests\Feature;

use App\Livewire\StoriesPage;
use App\Models\Story;
use App\Models\StoryCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class StoriesPageCategoryFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_filter_is_rendered_as_pressable_group_on_every_breakpoint(): void
    {
        $experiment = StoryCategory::factory()->create(['name' => 'Esperimento', 'is_active' => true]);
        $tutorial = StoryCategory::factory()->create(['name' => 'Tutorial', 'is_active' => true]);
        Story::factory()->for($experiment, 'category')->create(['is_published' => true]);
        Story::factory()->for($tutorial, 'category')->create(['is_published' => true]);

        Livewire::test(StoriesPage::class)
            ->assertSeeHtml('role="group" aria-label="Filtra per categoria"')
            ->assertDontSeeHtml('hidden md:flex')
            ->assertSeeHtml('aria-pressed="true"')
            ->set('categoryId', $tutorial->id)
            ->assertSet('categoryId', $tutorial->id)
            ->assertSeeHtmlInOrder([
                "\$set('categoryId', null)",
                'aria-pressed="false"',
                "\$set('categoryId', {$tutorial->id})",
                'aria-pressed="true"',
            ]);
    }

    public function test_category_filter_is_hidden_with_a_single_category(): void
    {
        $experiment = StoryCategory::factory()->create(['is_active' => true]);
        Story::factory()->for($experiment, 'category')->create(['is_published' => true]);

        Livewire::test(StoriesPage::class)
            ->assertDontSeeHtml('aria-label="Filtra per categoria"');
    }

    public function test_category_filter_ignores_categories_with_only_unpublished_stories(): void
    {
        $experiment = StoryCategory::factory()->create(['name' => 'Esperimento', 'is_active' => true]);
        $draftOnly = StoryCategory::factory()->create(['name' => 'Solo bozze', 'is_active' => true]);
        Story::factory()->for($experiment, 'category')->create(['is_published' => true]);
        Story::factory()->for($draftOnly, 'category')->create(['is_published' => false]);

        Livewire::test(StoriesPage::class)
            ->assertDontSeeHtml('aria-label="Filtra per categoria"')
            ->assertDontSee('Solo bozze');
    }
}
