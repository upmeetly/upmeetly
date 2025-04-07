<?php

declare(strict_types=1);

namespace App\Concerns\Models;

use App\Models\Team;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasTeam
{
    /**
     * Get the team that owns the model.
     *
     * @return BelongsTo<Team, static>
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
