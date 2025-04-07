<?php

declare(strict_types=1);

namespace App\Enums;

enum Role: string
{
    case OWNER = 'owner';
    case ADMIN = 'admin';
    case MEMBER = 'member';

    /**
     * Permissions associated with the role.
     */
    public function permissions(): array
    {
        return match ($this) {
            self::OWNER, self::ADMIN => Permission::cases(),
            self::MEMBER => [
                Permission::CREATE_TASKS,
                Permission::UPDATE_TASKS,
                Permission::ASSIGN_TASKS,
                Permission::ACCEPT_TASK,
                Permission::REJECT_TASK,
                Permission::COMPLETE_TASK,
            ],
        };
    }
}
