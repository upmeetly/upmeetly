<?php

declare(strict_types=1);

namespace App\Actions\Team;

use App\Enums\Role;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role as RoleModel;
use Spatie\QueueableAction\QueueableAction;
use Throwable;

class AddRolesToTeam
{
    use QueueableAction;

    /**
     * Execute the action.
     *
     * @param  Role|Role[]  $roles
     *
     * @throws Throwable
     */
    public function execute(Team $team, array|Role $roles): void
    {
        DB::transaction(function () use ($team, $roles) {
            $team->withinPermissionContext(function () use ($roles) {
                $roles = is_array($roles) ? $roles : [$roles];

                foreach ($roles as $roleEnum) {
                    $roleModel = RoleModel::create(['name' => $roleEnum]);

                    $roleModel->givePermissionTo($roleEnum->permissions());
                }
            });
        });
    }
}
