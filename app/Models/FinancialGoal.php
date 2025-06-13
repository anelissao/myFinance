<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use DateTime;

class FinancialGoal extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'target_amount',
        'current_amount',
        'due_date',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     * @var array<string, string>
     */
    protected $casts = [
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    // Getters
    public function getName(): string
    {
        return $this->name;
    }

    public function getTargetAmount(): float
    {
        return $this->target_amount;
    }

    public function getCurrentAmount(): float
    {
        return $this->current_amount;
    }

    public function getDueDate(): DateTime
    {
        return $this->due_date;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    // Setters
    public function setName(string $name): void
    {
        $this->name = $name;
        $this->save();
    }

    public function setTargetAmount(float $amount): void
    {
        $this->target_amount = $amount;
        $this->save();
    }

    public function setCurrentAmount(float $amount): void
    {
        $this->current_amount = $amount;
        $this->save();
    }

    public function setDueDate(DateTime $date): void
    {
        $this->due_date = $date;
        $this->save();
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public function getProgressPercentage(): float
    {
        if ($this->target_amount <= 0) {
            return 0;
        }
        return min(100, ($this->current_amount / $this->target_amount) * 100);
    }

    public function getRemainingAmount(): float
    {
        return max(0, $this->target_amount - $this->current_amount);
    }

    public function isCompleted(): bool
    {
        return $this->current_amount >= $this->target_amount;
    }

    public function getDaysRemaining(): int
    {
        return max(0, $this->due_date->diffInDays(now(), false));
    }

    public function isOverdue(): bool
    {
        return !$this->isCompleted() && $this->due_date < now();
    }

    public function getRequiredMonthlyAmount(): float
    {
        if ($this->isCompleted() || $this->isOverdue()) {
            return 0;
        }

        $monthsRemaining = max(1, $this->due_date->diffInMonths(now()));
        return ($this->target_amount - $this->current_amount) / $monthsRemaining;
    }
}
