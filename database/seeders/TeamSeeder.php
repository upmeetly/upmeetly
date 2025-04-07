<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = User::where('email', 'test@example.com')->first() ?? User::factory()
            ->withPersonalTeam()
            ->state(['name' => 'Test User', 'email' => 'test@example.com'])
            ->create();

        Team::factory()
            ->state([
                'name' => 'Test Team',
                'slug' => 'test-team',
                'user_id' => $owner->id,
            ])
            ->withUsers(Role::ADMIN, 3)
            ->withUsers(Role::MEMBER)
            ->create();

        Team::factory()
            ->count(mt_rand(5, 10))
            ->withUsers(Role::ADMIN, 2)
            ->withUsers(Role::MEMBER)
            ->create();
    }
}
