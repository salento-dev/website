<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Author>
 */
class AuthorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $githubUsernames = ['taylorotwell', 'themsaid', 'jessarcher', 'nunomaduro', 'timacdonald'];

        return [
            'name' => fake()->name(),
            'role' => fake()->randomElement(['Full Stack Dev', 'Frontend Dev', 'Backend Dev', 'DevOps Engineer', 'Tech Lead', 'Open Source Maintainer']),
            'bio' => fake()->optional(0.7)->paragraph(2),
            'avatar_path' => null,
            'github_username' => fake()->optional(0.6)->randomElement($githubUsernames),
            'linkedin_url' => fake()->optional(0.5)->url(),
            'twitter_url' => null,
            'website_url' => fake()->optional(0.3)->url(),
        ];
    }
}
