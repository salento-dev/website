<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class EventsPage extends Component
{
    use WithPagination;

    public string $search = '';

    public string $period = 'all';

    public string $category = 'all';

    public string $sortBy = 'newest';

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

    public function updatedPeriod(): void
    {
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function updatedSortBy(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $hasEventCategoryTables = Schema::hasTable('event_categories') && Schema::hasTable('event_category_event');

        $events = Schema::hasTable('events')
            ? $this->buildEventsQuery($hasEventCategoryTables)->paginate(12)
            : new Collection;

        $categories = $hasEventCategoryTables
            ? EventCategory::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get()
            : new Collection;

        return view('livewire.events-page', [
            'events' => $events,
            'hasEventCategoryTables' => $hasEventCategoryTables,
            'categories' => $categories,
            'sortOptions' => self::sortOptions(),
        ])->layout('layouts.app', [
            'pageTitle' => 'Eventi',
            'metaDescription' => 'Calendario eventi della community Salento.dev.',
        ]);
    }

    protected function buildEventsQuery(bool $hasEventCategoryTables): Builder
    {
        $query = Event::query()
            ->where('is_published', true)
            ->when(
                filled($this->search),
                fn (Builder $builder): Builder => $builder->where(function (Builder $subQuery): void {
                    $subQuery
                        ->where('title', 'like', '%'.$this->search.'%')
                        ->orWhere('location', 'like', '%'.$this->search.'%')
                        ->orWhere('description', 'like', '%'.$this->search.'%');
                }),
            )
            ->when(
                $this->period === 'upcoming',
                fn (Builder $builder): Builder => $builder->where('starts_at', '>=', now()),
            )
            ->when(
                $this->period === 'past',
                fn (Builder $builder): Builder => $builder->where('starts_at', '<', now()),
            );

        if ($hasEventCategoryTables) {
            $query
                ->with('categories:id,name,slug')
                ->when(
                    $this->category !== 'all',
                    fn (Builder $builder): Builder => $builder->whereHas(
                        'categories',
                        fn (Builder $categoryQuery): Builder => $categoryQuery->where('slug', $this->category),
                    ),
                );
        }

        return match ($this->sortBy) {
            'oldest' => $query->orderBy('starts_at'),
            'title-asc' => $query->orderBy('title'),
            'title-desc' => $query->orderByDesc('title'),
            default => $query->orderByDesc('starts_at'),
        };
    }
}
