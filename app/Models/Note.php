<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Note extends Pivot
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
