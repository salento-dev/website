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

    public string $mediaFilter = 'all';

    public string $category = 'all';

    public function render(): View
    {
        $hasEventMediaTable = Schema::hasTable('event_media');
        $hasEventCategoryTables = Schema::hasTable('event_categories') && Schema::hasTable('event_category_event');

        $events = Schema::hasTable('events')
            ? $this->buildEventsQuery($hasEventMediaTable, $hasEventCategoryTables)->get()
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
            'hasEventMediaTable' => $hasEventMediaTable,
            'hasEventCategoryTables' => $hasEventCategoryTables,
            'categories' => $categories,
        ])->layout('layouts.app', [
            'pageTitle' => 'Eventi',
            'metaDescription' => 'Calendario eventi della community Salento.dev.',
        ]);
    }

    protected function buildEventsQuery(bool $hasEventMediaTable, bool $hasEventCategoryTables): Builder
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

        if ($hasEventMediaTable) {
            $query
                ->withCount('media')
                ->withCount([
                    'media as video_media_count' => fn (Builder $builder): Builder => $builder->where('media_type', 'video'),
                    'media as image_media_count' => fn (Builder $builder): Builder => $builder->where('media_type', 'image'),
                ])
                ->when(
                    $this->mediaFilter === 'with_media',
                    fn (Builder $builder): Builder => $builder->whereHas('media'),
                )
                ->when(
                    $this->mediaFilter === 'with_video',
                    fn (Builder $builder): Builder => $builder->whereHas('media', fn (Builder $mediaQuery): Builder => $mediaQuery->where('media_type', 'video')),
                );
        }

        return $query->orderByDesc('starts_at');
    }
}
