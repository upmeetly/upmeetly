<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Permission;
use App\Models\Meetup;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MeetupPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->allTeams()->contains(Team::current())
            ? Response::allow()
            : Response::deny(__('You do not have permission to view any meetups.'));
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Meetup $meetup): Response
    {
        return $user->allTeams()->contains($meetup->team)
            ? Response::allow()
            : Response::deny(__('You do not have permission to view this meetup.'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->can(Permission::MANAGE_MEETUPS)
            ? Response::allow()
            : Response::deny(__('You do not have permission to create meetups.'));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Meetup $meetup): Response
    {
        return $user->can(Permission::MANAGE_MEETUPS) && $user->allTeams()->contains($meetup->team)
            ? Response::allow()
            : Response::deny(__('You do not have permission to update this meetup.'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Meetup $meetup): Response
    {
        return $user->can(Permission::MANAGE_MEETUPS) && $user->allTeams()->contains($meetup->team)
            ? Response::allow()
            : Response::deny(__('You do not have permission to delete this meetup.'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Meetup $meetup): Response
    {
        return $user->can(Permission::MANAGE_MEETUPS) && $user->allTeams()->contains($meetup->team)
            ? Response::allow()
            : Response::deny(__('You do not have permission to restore this meetup.'));
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Meetup $meetup): Response
    {
        return $user->can(Permission::MANAGE_MEETUPS) && $user->allTeams()->contains($meetup->team)
            ? Response::allow()
            : Response::deny(__('You do not have permission to permanently delete this meetup.'));
    }
}
