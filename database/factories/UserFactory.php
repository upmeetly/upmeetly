<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Add a personal team for the User.
     */
    public function withPersonalTeam(array $states = []): static
    {
        return $this->afterCreating(function (User $user) use ($states) {
            Team::factory()
                ->for($user)
                ->personal()
                ->state(array_merge([
                    'name' => $user->name.' personal team',
                    'slug' => Str::slug($user->name).'-personal-team-'.time(),
                ], $states))
                ->create();
        });
    }

    public function withRoles(Team $team, Role|array $roles): static
    {
        return $this->afterCreating(function (User $user) use ($team, $roles): void {
            $team->withinPermissionContext(function (Team $team, User $user, array|Role $roles) {
                $user->assignRole($roles);
            }, $user, $roles);
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
