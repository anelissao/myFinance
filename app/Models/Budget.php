<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use DateTime;

class Budget extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array<string>
     */
    protected $fillable = [
        'month',
        'planned_amount',
        'actual_amount',
        'user_id',
        'category_id',
    ];

    /**
     * The attributes that should be cast.
     * @var array<string, string>
     */
    protected $casts = [
        'month' => 'date',
        'planned_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
    ];

    // Getters
    public function getMonth(): DateTime
    {
        return $this->month;
    }

    public function getPlannedAmount(): float
    {
        return $this->planned_amount;
    }

    public function getActualAmount(): float
    {
        return $this->actual_amount;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    // Setters
    public function setMonth(DateTime $month): void
    {
        $this->month = $month;
        $this->save();
    }

    public function setPlannedAmount(float $amount): void
    {
        $this->planned_amount = $amount;
        $this->save();
    }

    public function setActualAmount(float $amount): void
    {
        $this->actual_amount = $amount;
        $this->save();
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Helper methods
    public function isOverBudget(): bool
    {
        return $this->actual_amount > $this->planned_amount;
    }

    public function getProgressPercentage(): float
    {
        if ($this->planned_amount <= 0) {
            return 0;
        }
        return min(100, ($this->actual_amount / $this->planned_amount) * 100);
    }

    public function getRemainingAmount(): float
    {
        return max(0, $this->planned_amount - $this->actual_amount);
    }

    public function updateActualAmount(): void
    {
        $this->actual_amount = $this->category
            ->transactions()
            ->whereYear('date', $this->month->year)
            ->whereMonth('date', $this->month->month)
            ->sum('amount');
        $this->save();
    }
}
