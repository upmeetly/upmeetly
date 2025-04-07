<?php

declare(strict_types=1);

namespace App\Contracts\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface BelongsToUser
{
    /**
     * Get the user that owns the model.
     *
     * @return BelongsTo<User, static>
     */
    public function user(): BelongsTo;
}
