<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    /**
     * Returns the avatar URL, preferring the stored image,
     * then falling back to the GitHub avatar if a username is set.
     */
    public function avatarUrl(): ?string
    {
        if (filled($this->avatar_path)) {
            return asset('storage/'.ltrim((string) $this->avatar_path, '/'));
        }

        if (filled($this->github_username)) {
            return 'https://github.com/'.urlencode((string) $this->github_username).'.png?size=200';
        }

        return null;
    }
}
