<?php

namespace App\Livewire;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ResourcesPage extends Component
{
    use WithPagination;

    public string $sortBy = 'newest';

    public string $search = '';

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
        $query = Resource::query()
            ->where('is_published', true)
            ->when(
                filled($this->search),
                fn ($q) => $q->where('title', 'like', '%'.$this->search.'%'),
            );

        match ($this->sortBy) {
            'oldest' => $query->oldest(),
            'title-asc' => $query->orderBy('title'),
            'title-desc' => $query->orderByDesc('title'),
            default => $query->latest(),
        };

        $resources = Schema::hasTable('resources')
            ? $query->with('authors')->paginate(15)
            : new Collection;

        return view('livewire.resources-page', [
            'resources' => $resources,
            'sortOptions' => self::sortOptions(),
            'sortBy' => $this->sortBy,
        ])->layout('layouts.app', [
            'pageTitle' => 'Documenti & Slide',
            'metaDescription' => 'Raccolta documenti e slide della community Salento.dev.',
        ]);
    }
}
