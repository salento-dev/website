<?php

namespace Database\Factories;

use App\Models\Story;
use App\Models\StoryCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Story>
 */
class StoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(fake()->numberBetween(4, 8));
        $publishedAt = fake()->dateTimeBetween('-2 years', 'now');

        return [
            'story_category_id' => StoryCategory::factory(),
            'title' => rtrim($title, '.'),
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(100, 999),
            'excerpt' => fake()->paragraph(2),
            'body' => $this->markdownBody(),
            'image_path' => null,
            'is_published' => fake()->boolean(80),
            'published_at' => $publishedAt,
        ];
    }

    private function markdownBody(): string
    {
        $sections = [];

        // Intro paragraph
        $sections[] = fake()->paragraph(4);

        // First H2 section
        $sections[] = '## '.fake()->sentence(fake()->numberBetween(3, 6));
        $sections[] = fake()->paragraph(4);
        $sections[] = fake()->paragraph(3);

        // Bulleted list
        $sections[] = '## '.fake()->sentence(fake()->numberBetween(3, 5));
        $items = array_map(
            fn () => '- **'.rtrim(fake()->sentence(3), '.').'**: '.fake()->sentence(8),
            range(1, fake()->numberBetween(3, 5))
        );
        $sections[] = implode("\n", $items);

        // Blockquote
        $sections[] = '> '.fake()->sentence(12);

        // Second body section
        $sections[] = '## '.fake()->sentence(fake()->numberBetween(3, 6));
        $sections[] = fake()->paragraph(4);

        // Code block
        $snippets = [
            "```bash\nnpm install\nnpm run dev\n```",
            "```php\n\$story = Story::find(1);\necho \$story->title;\n```",
            "```bash\n./vendor/bin/sail artisan migrate\n./vendor/bin/sail artisan db:seed\n```",
            "```json\n{\n  \"name\": \"salento.dev\",\n  \"version\": \"1.0.0\"\n}\n```",
        ];
        $sections[] = fake()->randomElement($snippets);

        // Closing paragraph
        $sections[] = fake()->paragraph(3);

        return implode("\n\n", $sections);
    }
}
