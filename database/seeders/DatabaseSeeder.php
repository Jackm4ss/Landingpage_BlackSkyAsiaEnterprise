<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole = Role::findOrCreate('admin');
        Role::findOrCreate('user');

        $admin = User::query()->updateOrCreate([
            'email' => env('BLACK_SKY_ADMIN_EMAIL', 'admin@blacksky.test'),
        ], [
            'name' => env('BLACK_SKY_ADMIN_NAME', 'Black Sky Admin'),
            'email_verified_at' => now(),
            'password' => Hash::make(env('BLACK_SKY_ADMIN_PASSWORD', 'password')),
            'is_active' => true,
        ]);

        $admin->syncRoles([$adminRole]);

        $this->call([
            EventSeeder::class,
            BlogSeeder::class,
            PortfolioWorkSeeder::class,
        ]);
    }
}
