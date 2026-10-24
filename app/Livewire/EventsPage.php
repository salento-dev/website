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
            'newest' => 'Più recenti',
            'oldest' => 'Meno recenti',
            'title-asc' => 'Titolo A-Z',
            'title-desc' => 'Titolo Z-A',
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
        if (! Schema::hasTable('events')) {
            return view('livewire.events-page', [
                'upcomingEvents' => new Collection,
                'wipEvents' => new Collection,
                'pastEvents' => new Collection,
                'sortOptions' => self::sortOptions(),
            ])->layout('layouts.app', [
                'pageTitle' => 'Eventi',
                'metaDescription' => 'Calendario eventi della community Salento.dev.',
            ]);
        }

        $upcomingEvents = Event::query()
            ->where('is_published', true)
            ->where('work_in_progress', false)
            ->where('starts_at', '>', now())
            ->orderBy('starts_at')
            ->with(['categories'])
            ->get();

        $wipEvents = Event::query()
            ->where('is_published', true)
            ->where('work_in_progress', true)
            ->orderBy('starts_at')
            ->with(['categories'])
            ->get();

        $pastEvents = $this->buildPastEventsQuery()->paginate(12);

        return view('livewire.events-page', [
            'upcomingEvents' => $upcomingEvents,
            'wipEvents' => $wipEvents,
            'pastEvents' => $pastEvents,
            'sortOptions' => self::sortOptions(),
        ])->layout('layouts.app', [
            'pageTitle' => 'Eventi',
            'metaDescription' => 'Calendario eventi della community Salento.dev.',
        ]);
    }

    protected function buildPastEventsQuery(): Builder
    {
        $query = Event::query()
            ->where('is_published', true)
            ->where('work_in_progress', false)
            ->where('starts_at', '<=', now())
            ->with(['categories'])
            ->when(
                filled($this->search),
                fn (Builder $builder): Builder => $builder->where(function (Builder $subQuery): void {
                    $subQuery
                        ->where('title', 'like', '%'.$this->search.'%')
                        ->orWhere('location', 'like', '%'.$this->search.'%')
                        ->orWhere('excerpt', 'like', '%'.$this->search.'%')
                        ->orWhere('body', 'like', '%'.$this->search.'%');
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
