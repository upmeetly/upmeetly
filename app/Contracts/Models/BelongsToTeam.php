<?php

declare(strict_types=1);

namespace App\Contracts\Models;

use App\Models\Team;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface BelongsToTeam
{
    /**
     * Get the team that owns the model.
     *
     * @return BelongsTo<Team, static>
     */
    public function team(): BelongsTo;
}
