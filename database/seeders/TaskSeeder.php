<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Meetup;
use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Meetup::query()->with(['team', 'user'])->get() as $meetup) {
            Task::factory()
                ->count(mt_rand(1, 5))
                ->for($meetup)
                ->for($meetup->team, 'team')
                ->for($meetup->user, 'user')
                ->create();
        }
    }
}
