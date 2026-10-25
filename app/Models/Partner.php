<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Filesystem\FilesystemAdapter;
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
        'cover_logo_path',
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
        return $this->publicUrl($this->logo_path);
    }

    /**
     * Get the white, transparent logo used on the event covers.
     */
    public function getCoverLogoUrlAttribute(): ?string
    {
        return $this->publicUrl($this->cover_logo_path);
    }

    private function publicUrl(?string $path): ?string
    {
        if (filled($path)) {
            $path = ltrim($path, '/');
            /** @var FilesystemAdapter $storage */
            $storage = Storage::disk('public');
            if ($storage->exists($path)) {
                return $storage->url($path);
            }
        }

        return null;
    }
}
