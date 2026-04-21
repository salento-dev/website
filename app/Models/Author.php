<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class Author extends Model
{
    /** @use HasFactory<\Database\Factories\AuthorFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $appends = ['avatar_url', 'github_url'];

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

    public function getDisplayBioAttribute(): ?string
    {
        if (filled($this->bio)) {
            return $this->bio;
        }

        return $this->githubProfile()['bio'] ?? null;
    }

    public function getDisplayRoleAttribute(): ?string
    {
        if (filled($this->role)) {
            return $this->role;
        }

        $company = $this->githubProfile()['company'] ?? null;

        return filled($company) ? ltrim((string) $company, '@') : null;
    }

    public function getDisplayWebsiteUrlAttribute(): ?string
    {
        if (filled($this->website_url)) {
            return $this->website_url;
        }

        $blog = $this->githubProfile()['blog'] ?? null;

        return filled($blog) ? (string) $blog : null;
    }

    public function getDisplayTwitterUrlAttribute(): ?string
    {
        if (filled($this->twitter_url)) {
            return $this->twitter_url;
        }

        $username = $this->githubProfile()['twitter_username'] ?? null;

        return filled($username) ? 'https://twitter.com/'.urlencode((string) $username) : null;
    }

    /**
     * Fetches and caches the GitHub profile for a given username.
     *
     * @return array<string, mixed>
     */
    public static function fetchGithubProfile(string $username): array
    {
        return Cache::remember(
            'github_profile_'.$username,
            now()->addHours(24),
            function () use ($username): array {
                $response = Http::get('https://api.github.com/users/'.urlencode($username));

                return $response->ok() ? $response->json() : [];
            }
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function githubProfile(): array
    {
        if (! filled($this->github_username)) {
            return [];
        }

        return static::fetchGithubProfile((string) $this->github_username);
    }
}
