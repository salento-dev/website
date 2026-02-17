<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Livewire\Component;

class EventsPage extends Component
{
    public string $search = '';

    public string $period = 'all';

    public string $category = 'all';

    public function render(): View
    {
        $hasEventCategoryTables = Schema::hasTable('event_categories') && Schema::hasTable('event_category_event');

        $events = Schema::hasTable('events')
            ? $this->buildEventsQuery($hasEventCategoryTables)->get()
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

        return $query->orderByDesc('starts_at');
    }
}
