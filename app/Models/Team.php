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
            setPermissionsTeamId($previousId);
        });
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
            ->withPivot([
                'joined_at',
                'left_at',
            ])
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
