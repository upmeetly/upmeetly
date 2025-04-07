<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Meetup;
use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MeetupSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Team::all() as $team) {
            Meetup::factory()
                ->count(mt_rand(5, 10))
                ->for($team)
                ->create();
        }
    }
}
