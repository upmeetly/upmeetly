<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Actions\Team\AddRolesToTeam;
use App\Enums\MemberStatus;
use App\Enums\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Team>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company,
            'description' => fake()->paragraph,
            'slug' => fake()->unique()->slug(),
            'user_id' => User::factory(),
            'personal' => false,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Team $team) {
            $team->user->update(['current_team_id' => $team->id]);

            resolve(AddRolesToTeam::class)->execute($team, Role::cases());

            $team->withinPermissionContext(function (Team $team): void {
                $team->user->assignRole(Role::OWNER);

                $team->users()->attach($team->user, [
                    'status' => MemberStatus::ACTIVE,
                    'joined_at' => fake()->dateTimeBetween()
                ]);
            });
        });
    }

    /**
     * Indicate that the team has some users.
     *
     * @param Role|Role[] $roles
     */
    public function withUsers(Role|array $roles, ?int $count = null): static
    {
        return $this->afterCreating(function (Team $team) use($count, $roles) {
            $users = User::factory()
                ->count($count ?? mt_rand(1, 5))
                ->withPersonalTeam()
                ->withRoles($team, $roles)
                ->state(['current_team_id' => $team->id])
                ->create();

            $team->users()->attach($users->pluck('id'), [
                'status' => MemberStatus::ACTIVE,
                'joined_at' => fake()->dateTimeBetween()
            ]);
        });
    }

    /**
     * Indicate that the team is personal team.
     */
    public function personal(): static
    {
        return $this->state([
            'personal' => true,
        ]);
    }
}
