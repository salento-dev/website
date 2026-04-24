<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'location',
        'address',
        'starts_at',
        'ends_at',
        'body',
        'excerpt',
        'image_path',
        'is_published',
        'work_in_progress',
        'is_community_event',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_published' => 'boolean',
            'work_in_progress' => 'boolean',
            'is_community_event' => 'boolean',
        ];
    }

    public function pageViews(): MorphMany
    {
        return $this->morphMany(PageView::class, 'viewable');
    }

    public function isPast(): bool
    {
        return $this->ends_at <= now();
    }

    public function media(): HasMany
    {
        return $this->hasMany(EventMedia::class)->orderBy('sort_order')->orderBy('id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(EventCategory::class, 'event_category_event', 'event_id', 'event_category_id')
            ->orderBy('sort_order')
            ->orderBy('name');
    }

    public function resources(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class)->withTimestamps()
            ->with('authors');
    }

    public function partners(): BelongsToMany
    {
        return $this->belongsToMany(Partner::class)->orderBy('name');
    }

    public function getImageUrlAttribute(): string
    {
        /** @var \Illuminate\Filesystem\FilesystemAdapter $storage */
        $storage = Storage::disk('public');

        if (filled($this->image_path)) {
            $path = ltrim((string) $this->image_path, '/');
            if ($storage->exists($path)) {
                return $storage->url($path);
            }
        }

        return $storage->url('placeholders/events/event-placeholder.jpg');
    }

    public function getLocalStartsAtAttribute(): ?Carbon
    {
        return $this->starts_at?->tz('Europe/Rome');
    }

    public function getLocalEndsAtAttribute(): ?Carbon
    {
        return $this->ends_at?->tz('Europe/Rome');
    }

    /**
     * Get the human-friendly estimated reading time of a given text.
     *
     * @return string
     */
    public function getReadTimeMinutesAttribute(): float
    {
        $texts = $this->body;

        // Only count words in our estimation
        $words = str_word_count(strip_tags($texts));

        // Divide by the average number of words per minute
        return ceil($words / 250);
    }
}
