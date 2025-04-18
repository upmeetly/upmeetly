<?php

declare(strict_types=1);

namespace App\Models;

use App\Concerns\Models\HasUser;
use App\Contracts\Models\BelongsToUser;
use Database\Factories\TeamFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Context;

class Team extends Model implements BelongsToUser
{
    /** @use HasFactory<TeamFactory> */
    use HasFactory,
        HasUser,
        SoftDeletes;

    /**
     * Execute the given callback within the context of this team.
     */
    public function withinPermissionContext(callable $callback, ...$args): mixed
    {
        $previousId = getPermissionsTeamId();

        setPermissionsTeamId($this->id);

        return tap($callback($this, ...$args), function () use ($previousId) {
            if ($previousId) {
                setPermissionsTeamId($previousId);
            } else {
                setPermissionsTeamId(null);
            }
        });
    }

    /**
     * Get the current team.
     */
    public static function current(): Team
    {
        if (Context::get('team') instanceof Team) {
            return Context::get('team');
        }

        $teamIdFromSession = getPermissionsTeamId() ?? auth()->user()->current_team_id;

        return Team::findOrFail($teamIdFromSession);
    }

    /**
     * Set the current team.
     */
    public static function setCurrent(Team $team): void
    {
        setPermissionsTeamId($team);
        Context::add('team', $team);
    }

    /**
     * Scope a query to only include non-personal teams.
     */
    #[Scope]
    public function nonPersonal(Builder $query): Builder
    {
        return $query->where('personal', false);
    }

    /**
     * Users that are members of the team.
     *
     * @return BelongsToMany<User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->using(Membership::class)
            ->as('membership')
            ->withPivot(Membership::pivotFields())
            ->withTimestamps();
    }

    /**
     * {@inheritDoc}
     */
    protected function casts(): array
    {
        return [
            'personal' => 'boolean',
        ];
    }
}
