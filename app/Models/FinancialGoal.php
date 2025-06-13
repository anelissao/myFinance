<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'target_amount',
        'current_amount',
        'due_date',
        'user_id',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getProgressPercentage()
    {
        if ($this->target_amount <= 0) {
            return 0;
        }
        return min(100, ($this->current_amount / $this->target_amount) * 100);
    }

    public function getRemainingAmount()
    {
        return max(0, $this->target_amount - $this->current_amount);
    }

    public function isCompleted()
    {
        return $this->current_amount >= $this->target_amount;
    }
}
