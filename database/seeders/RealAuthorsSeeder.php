<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class RealAuthorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Author::updateOrCreate(
            ['name' => 'Simone De Vittorio'],
            [
                'role' => 'Creatore di Reactylon',
                'bio' => 'Sviluppatore e creatore di Reactylon, un framework open source basato su Babylon.js e React per esperienze 3D e XR interattive.',
                'github_username' => 'simonedevit',
                'linkedin_url' => 'https://www.linkedin.com/in/simone-de-vittorio',
                'website_url' => 'https://www.reactylon.com',
            ],
        );

        Author::updateOrCreate(
            ['name' => 'Paolo Rizzello'],
            [
                'role' => 'Backend Web Developer and Software Engineer',
                'bio' => 'Passionate well-rounded developer... but, let\'s be honest: i hate frontend 😜!',
                'github_username' => 'rizzello',
                'linkedin_url' => 'https://www.linkedin.com/in/paolorizzello',
                'website_url' => 'https://rizzello.dev',
            ],
        );

        Author::updateOrCreate(
            ['name' => 'Emanuele Paiano'],
            [
                'role' => 'Senior Backend & Infrastructure',
                'bio' => '8 anni passati tra microservizi e architetture asincrone, 20 su Linux... ma se mi chiedi di centrare un div, mi licenzio.',
                'github_username' => 'emanuelepaiano',
                'linkedin_url' => 'https://www.linkedin.com/in/emanuelepaiano',
                'website_url' => 'https://emanuelepaiano.it',
            ],
        );

    }
}
