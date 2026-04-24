<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Partner extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'logo_path',
        'website_url',
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
        return $this->belongsToMany(Event::class);
    }

    public function getLogoUrlAttribute(): ?string
    {
        if (filled($this->logo_path)) {
            $path = ltrim((string) $this->logo_path, '/');
            /** @var \Illuminate\Filesystem\FilesystemAdapter $storage */
            $storage = Storage::disk('public');
            if ($storage->exists($path)) {
                return $storage->url($path);
            }
        }

        return null;
    }
}
