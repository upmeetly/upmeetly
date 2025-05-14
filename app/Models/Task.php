<?php

declare(strict_types=1);

namespace App\Models;

use App\Concerns\Models\HasTeam;
use App\Concerns\Models\HasUser;
use App\Contracts\Models\BelongsToTeam;
use App\Contracts\Models\BelongsToUser;
use App\Enums\TaskStatus;
use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model implements BelongsToTeam, BelongsToUser
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory,
        HasTeam,
        HasUser,
        SoftDeletes;

    /**
     * The meetup that the task belongs to.
     *
     * @return BelongsTo<Meetup, $this>
     */
    public function meetup(): BelongsTo
    {
        return $this->belongsTo(Meetup::class);
    }

    /**
     * {@inheritDoc}
     */
    protected function casts(): array
    {
        return [
            'status' => TaskStatus::class,
        ];
    }
}
