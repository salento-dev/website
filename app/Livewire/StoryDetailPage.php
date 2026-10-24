<?php

namespace App\Livewire;

use App\Models\Story;
use Illuminate\View\View;
use Livewire\Component;

class StoryDetailPage extends Component
{
    public Story $story;

    public function mount(Story $story): void
    {
        abort_unless($story->is_published, 404);

        $this->story = $story->load(['category', 'authors', 'protagonists'])->loadTotalPageViews();
    }

    public function render(): View
    {
        $relatedStories = Story::query()
            ->where('is_published', true)
            ->whereKeyNot($this->story->getKey())
            ->with(['category', 'authors'])
            ->when(
                $this->story->story_category_id !== null,
                fn ($q) => $q->where('story_category_id', $this->story->story_category_id),
            )
            ->latest('published_at')
            ->limit(2)
            ->get();

        return view('livewire.story-detail-page', [
            'story' => $this->story,
            'relatedStories' => $relatedStories,
        ])->layout('layouts.app', [
            'pageTitle' => $this->story->title,
            'metaDescription' => $this->story->excerpt,
        ]);
    }
}
