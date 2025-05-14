<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MemberStatus;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasDefaultTenant;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasDefaultTenant, HasTenants, MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory,
        HasRoles,
        Notifiable;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->allTeams()->contains($tenant);
    }

    /**
     * {@inheritDoc}
     */
    public function getTenants(Panel $panel): array|Collection
    {
        return $this->allTeams();
    }

    public function getDefaultTenant(Panel $panel): ?Model
    {
        return $this->currentTeam;
    }

    /**
     * Switch the current team to the given team.
     */
    public function switch(Team $team): void
    {
        $this->update([
            'current_team_id' => $team->id,
        ]);
    }

    /**
     * The teams that the user owns and belongs to.
     */
    public function allTeams(): Collection
    {
        return $this->ownedTeams->merge($this->teams)->unique();
    }

    /**
     * The teams that the user is currently assigned to.
     *
     * @return BelongsTo<Team, $this>
     */
    public function currentTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * The teams that the user owns.
     *
     * @return HasMany<Team, $this>
     */
    public function ownedTeams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    /**
     * Teams that the user belongs to.
     *
     * @return BelongsToMany<Team, $this>
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class)
            ->using(Membership::class)
            ->as('membership')
            ->withPivot(Membership::pivotFields())
            ->withTimestamps();
    }

    /**
     * Scope a query to include users of the current team only.
     */
    #[Scope]
    protected function withinCurrentTeam(Builder $query, MemberStatus $memberStatus = MemberStatus::ACTIVE): void
    {
        $team = Team::current();

        $query
            ->whereHas('ownedTeams', fn (Builder $teams) => $teams->where('id', $team->id))
            ->orWhereHas('teams', fn (Builder $teams) => $teams->where('id', $team->id)->where('team_user.status', $memberStatus));
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
