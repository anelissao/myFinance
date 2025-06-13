<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'color',
        'icon',
        'alert_threshold',
    ];

    /**
     * The attributes that should be cast.
     * @var array<string, string>
     */
    protected $casts = [
        'alert_threshold' => 'decimal:2',
    ];

    // Getters
    public function getName(): string
    {
        return $this->name;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function getAlertThreshold(): float
    {
        return $this->alert_threshold;
    }

    // Setters
    public function setName(string $name): void
    {
        $this->name = $name;
        $this->save();
    }

    public function setColor(string $color): void
    {
        $this->color = $color;
        $this->save();
    }

    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
        $this->save();
    }

    public function setAlertThreshold(float $threshold): void
    {
        $this->alert_threshold = $threshold;
        $this->save();
    }

    // Relationships
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class);
    }

    // Helper methods
    public function isOverThreshold(float $amount): bool
    {
        return $amount > $this->alert_threshold;
    }

    public function getTransactionsTotal(): float
    {
        return $this->transactions()->sum('amount');
    }

    public function getCurrentMonthBudget(): ?Budget
    {
        return $this->budgets()
            ->whereYear('month', now()->year)
            ->whereMonth('month', now()->month)
            ->first();
    }
}
