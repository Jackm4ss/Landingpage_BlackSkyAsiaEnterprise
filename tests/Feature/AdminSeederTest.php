<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_creates_working_admin_login(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::query()
            ->where('email', 'admin@blacksky.test')
            ->firstOrFail();

        $this->assertTrue(Hash::check('password', $admin->password));
        $this->assertTrue($admin->is_active);
        $this->assertTrue($admin->hasRole('admin'));
        $this->assertTrue($admin->canAccessPanel(filament()->getPanel('admin')));
    }
}
