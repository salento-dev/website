<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Resource extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'source_type',
        'external_url',
        'file_path',
        'is_published',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class)->withTimestamps();
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class);
    }

    public function getResourceUrlAttribute(): ?string
    {
        if ($this->source_type === 'file' && filled($this->file_path)) {
            $path = ltrim((string) $this->file_path, '/');
            /** @var \Illuminate\Filesystem\FilesystemAdapter $storage */
            $storage = Storage::disk('public');
            if ($storage->exists($path)) {
                return $storage->url($path);
            }

            return null;
        }

        return filled($this->external_url) ? (string) $this->external_url : null;
    }
}
