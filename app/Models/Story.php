<?php

namespace App\Models;

use App\Models\Concerns\HasAnalytics;
use Database\Factories\StoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

class Story extends Model
{
    use HasAnalytics;

    /** @use HasFactory<StoryFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'story_category_id',
        'title',
        'slug',
        'excerpt',
        'body',
        'image_path',
        'is_published',
        'published_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(StoryCategory::class, 'story_category_id');
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class);
    }

    public function protagonists(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'protagonist_story');
    }

    public function getImageUrlAttribute(): ?string
    {
        if (filled($this->image_path)) {
            $path = ltrim((string) $this->image_path, '/');
            /** @var FilesystemAdapter $storage */
            $storage = Storage::disk('public');
            if ($storage->exists($path)) {
                return $storage->url($path);
            }
        }

        return null;
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
