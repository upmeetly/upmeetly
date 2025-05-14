<?php

declare(strict_types=1);

namespace App\Queries\User;

use App\Models\User;

class GetUserDefaultAvatarUrl
{
    /**
     * Get the default avatar URL for the user.
     */
    public function execute(User $user, bool $anonymous = false): string
    {
        $name = $anonymous ? __('Anonymous') : str_replace(' ', '+', $user->name);

        return 'https://ui-avatars.com/api/?name='.$name.'&color=FFFFFF&background=09090b';
    }
}
