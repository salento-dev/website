<?php

namespace App\Livewire;

use App\Models\Story;
use App\Models\StoryCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class StoriesPage extends Component
{
    use WithPagination;

    public string $search = '';

    public string $sortBy = 'newest';

    public ?int $categoryId = null;

    /**
     * @return array<string, string>
     */
    public static function sortOptions(): array
    {
        return [
            'newest' => 'Newest',
            'oldest' => 'Oldest',
            'title-asc' => 'Title A-Z',
            'title-desc' => 'Title Z-A',
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSortBy(): void
    {
        $this->resetPage();
    }

    public function updatedCategoryId(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        if (! Schema::hasTable('stories')) {
            return view('livewire.stories-page', [
                'featuredStory' => null,
                'stories' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 9),
                'categories' => new Collection,
                'sortOptions' => self::sortOptions(),
            ])->layout('layouts.app', [
                'pageTitle' => 'Stories',
                'metaDescription' => 'Storie della community Salento.dev.',
            ]);
        }

        $featuredStory = Story::query()
            ->where('is_published', true)
            ->with(['category', 'authors'])
            ->latest('published_at')
            ->first();

        $stories = $this->buildStoriesQuery($featuredStory?->id)->paginate(9);

        $categories = Schema::hasTable('story_categories')
            ? StoryCategory::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get()
            : new Collection;

        return view('livewire.stories-page', [
            'featuredStory' => $featuredStory,
            'stories' => $stories,
            'categories' => $categories,
            'sortOptions' => self::sortOptions(),
        ])->layout('layouts.app', [
            'pageTitle' => 'Stories',
            'metaDescription' => 'Storie della community Salento.dev: esperimenti, tutorial e riflessioni condivise.',
        ]);
    }

    protected function buildStoriesQuery(?int $excludeId): Builder
    {
        $query = Story::query()
            ->where('is_published', true)
            ->with(['category', 'authors'])
            ->when($excludeId !== null, fn (Builder $q) => $q->whereKeyNot($excludeId))
            ->when(
                filled($this->search),
                fn (Builder $q) => $q->where(function (Builder $sub): void {
                    $sub->where('title', 'like', '%'.$this->search.'%')
                        ->orWhere('excerpt', 'like', '%'.$this->search.'%')
                        ->orWhere('author', 'like', '%'.$this->search.'%');
                }),
            )
            ->when(
                $this->categoryId !== null,
                fn (Builder $q) => $q->where('story_category_id', $this->categoryId),
            );

        return match ($this->sortBy) {
            'oldest' => $query->oldest('published_at'),
            'title-asc' => $query->orderBy('title'),
            'title-desc' => $query->orderByDesc('title'),
            default => $query->latest('published_at'),
        };
    }
}
