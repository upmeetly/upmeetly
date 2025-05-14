<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\MeetupStatus;
use App\Models\Meetup;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Meetup>
 */
class MeetupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'user_id' => User::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(MeetupStatus::cases()),
            'starts_at' => null,
            'ends_at' => null,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Meetup $meetup): void {
            if (in_array($meetup->status, [MeetupStatus::SCHEDULED, MeetupStatus::CANCELED])) {
                $startsAt = Carbon::parse(fake()->dateTimeBetween('+1 month', '+2 months'));

                $meetup->update([
                    'starts_at' => $startsAt,
                    'ends_at' => $startsAt->addHours(mt_rand(5, 8)),
                ]);
            }

            if ($meetup->status === MeetupStatus::IN_PROGRESS) {
                $meetup->update([
                    'starts_at' => fake()->dateTimeBetween('-1 hour'),
                    'ends_at' => fake()->dateTimeBetween('+1 hour', '+2 hours'),
                ]);
            }

            if ($meetup->status === MeetupStatus::PAST) {
                $startsAt = Carbon::parse(fake()->dateTimeBetween('-1 month'));

                $meetup->update([
                    'starts_at' => $startsAt,
                    'ends_at' => $startsAt->addHours(mt_rand(5, 8)),
                ]);
            }
        });
    }
}
