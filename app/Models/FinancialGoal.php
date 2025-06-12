<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialGoal extends Model
{
    protected $fillable = [
        'user_id', 'title', 'target_amount', 'saved_amount', 'deadline'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
