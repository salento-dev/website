<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_is_created_with_the_configured_credentials(): void
    {
        config(['auth.seed_admin' => ['name' => 'Staff', 'email' => 'staff@salento.dev', 'password' => 'una-password-scelta']]);

        $this->seed(AdminUserSeeder::class);

        $admin = User::query()->where('email', 'staff@salento.dev')->sole();

        $this->assertSame('Staff', $admin->name);
        $this->assertTrue(Hash::check('una-password-scelta', $admin->password));
    }

    public function test_a_random_password_is_generated_when_none_is_configured(): void
    {
        config(['auth.seed_admin' => ['name' => 'Admin', 'email' => 'admin@salento.dev', 'password' => null]]);

        $this->seed(AdminUserSeeder::class);

        $admin = User::query()->where('email', 'admin@salento.dev')->sole();

        $this->assertFalse(Hash::check('password123', $admin->password));
        $this->assertFalse(Hash::check('', $admin->password));
    }

    public function test_seeding_twice_updates_the_existing_admin(): void
    {
        config(['auth.seed_admin' => ['name' => 'Admin', 'email' => 'admin@salento.dev', 'password' => 'prima']]);
        $this->seed(AdminUserSeeder::class);

        config(['auth.seed_admin.password' => 'dopo']);
        $this->seed(AdminUserSeeder::class);

        $this->assertSame(1, User::query()->count());
        $this->assertTrue(Hash::check('dopo', User::query()->sole()->password));
    }

    public function test_no_admin_is_created_in_production(): void
    {
        $this->app->detectEnvironment(fn (): string => 'production');
        config(['auth.seed_admin' => ['name' => 'Admin', 'email' => 'admin@salento.dev', 'password' => 'segreta']]);

        $this->app->make(AdminUserSeeder::class)->run();

        $this->assertSame(0, User::query()->count());
    }

    public function test_the_seeder_source_contains_no_hardcoded_password(): void
    {
        foreach (['DatabaseSeeder', 'AdminUserSeeder'] as $seeder) {
            $this->assertStringNotContainsString('password123', (string) file_get_contents(database_path("seeders/{$seeder}.php")));
        }
    }
}
