<?php

namespace App\Livewire;

use App\Models\Event;
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

    public function updatedSortBy(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $events = Schema::hasTable('events')
            ? $this->buildEventsQuery()->paginate(12)
            : new Collection;

        return view('livewire.events-page', [
            'events' => $events,
            'sortOptions' => self::sortOptions(),
        ])->layout('layouts.app', [
            'pageTitle' => 'Eventi',
            'metaDescription' => 'Calendario eventi della community Salento.dev.',
        ]);
    }

    protected function buildEventsQuery(): Builder
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
            );

        return match ($this->sortBy) {
            'oldest' => $query->orderBy('starts_at'),
            'title-asc' => $query->orderBy('title'),
            'title-desc' => $query->orderByDesc('title'),
            default => $query->orderByDesc('starts_at'),
        };
    }
}
