<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Membership extends Pivot
{
    /**
     * Pivot fields that are filled into the model.
     */
    public static function pivotFields(): array
    {
        return [
            'status',
            'joined_at',
            'left_at',
        ];
    }
}
