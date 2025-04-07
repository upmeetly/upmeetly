<?php

declare(strict_types=1);

namespace App\Enums;

enum Permission: string
{
    case MANAGE_MEMBERS = 'manage_members';
    case MANAGE_MEETUPS = 'manage_meetups';
    case CREATE_TASKS = 'create_tasks';
    case UPDATE_TASKS = 'update_tasks';
    case ASSIGN_TASKS = 'assign_tasks';
    case ACCEPT_TASK = 'accept_task';
    case REJECT_TASK = 'reject_task';
    case COMPLETE_TASK = 'complete_task';
}
