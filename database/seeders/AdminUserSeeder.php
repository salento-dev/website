<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Creates the Filament admin user from `auth.seed_admin`, never in production and never with a hardcoded password.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isProduction()) {
            $this->command?->warn('Utente admin non creato in produzione: usa `php artisan make:filament-user`.');

            return;
        }

        /** @var array{name: string, email: string, password: ?string} $admin */
        $admin = config('auth.seed_admin');
        $hasConfiguredPassword = filled($admin['password']);
        $password = $hasConfiguredPassword ? $admin['password'] : Str::password(20);

        User::query()->updateOrCreate(
            ['email' => $admin['email']],
            ['name' => $admin['name'], 'password' => $password],
        );

        if (! $hasConfiguredPassword) {
            $this->command?->warn("Utente admin {$admin['email']} creato con password generata: {$password}");
            $this->command?->line('Imposta ADMIN_PASSWORD nel file .env per sceglierla tu.');
        }
    }
}
