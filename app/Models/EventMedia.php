<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

class EventMedia extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'event_id',
        'media_type',
        'source_type',
        'file_path',
        'external_url',
        'caption',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function getFileUrlAttribute(): ?string
    {
        if (filled($this->file_path)) {
            $path = ltrim((string) $this->file_path, '/');
            /** @var FilesystemAdapter $storage */
            $storage = Storage::disk('public');
            if ($storage->exists($path)) {
                return $storage->url($path);
            }
        }

        return null;
    }
}
