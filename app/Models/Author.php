<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Author extends Model
{
    /** @use HasFactory<\Database\Factories\AuthorFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'role',
        'bio',
        'avatar_path',
        'github_username',
        'linkedin_url',
        'twitter_url',
        'website_url',
    ];

    public function stories(): BelongsToMany
    {
        return $this->belongsToMany(Story::class)->withTimestamps();
    }

    public function resources(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class);
    }

    /**
     * Returns the avatar URL, preferring the stored image,
     * then falling back to the GitHub avatar if a username is set.
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (filled($this->avatar_path)) {
            $path = ltrim((string) $this->avatar_path, '/');
            $storage = Storage::disk('public');
            if ($storage->exists($path)) {
                return $storage->url($path);
            }
        }

        if (filled($this->github_username)) {
            return 'https://github.com/'.urlencode((string) $this->github_username).'.png?size=200';
        }

        if (filled($this->name)) {
            return 'https://ui-avatars.com/api/?name='.urlencode((string) $this->name).'&background=0a0a0a&color=06b6d4';
        }

        return '';
    }

    public function getGithubUrlAttribute(): ?string
    {
        if (filled($this->github_username)) {
            return 'https://github.com/'.$this->github_username;
        }

        return null;
    }
}
