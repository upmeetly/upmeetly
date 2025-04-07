<?php

declare(strict_types=1);

namespace App\Models;

use App\Concerns\Models\HasTeam;
use App\Concerns\Models\HasUser;
use App\Contracts\Models\BelongsToTeam;
use App\Contracts\Models\BelongsToUser;
use App\Enums\MeetupStatus;
use Database\Factories\MeetupFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meetup extends Model implements BelongsToTeam, BelongsToUser
{
    /** @use HasFactory<MeetupFactory> */
    use HasFactory,
        HasTeam,
        HasUser,
        SoftDeletes;

    /**
     * {@inheritDoc}
     */
    protected function casts(): array
    {
        return [
            'status' => MeetupStatus::class,
        ];
    }
}
