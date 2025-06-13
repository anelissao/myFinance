<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'month',
        'planned_amount',
        'actual_amount',
        'user_id',
        'category_id',
    ];

    protected $casts = [
        'month' => 'date',
        'planned_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function isOverBudget()
    {
        return $this->actual_amount > $this->planned_amount;
    }

    public function getProgressPercentage()
    {
        if ($this->planned_amount <= 0) {
            return 0;
        }
        return min(100, ($this->actual_amount / $this->planned_amount) * 100);
    }
}
