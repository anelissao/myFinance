<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'type',
        'balance',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     * @var array<string, string>
     */
    protected $casts = [
        'balance' => 'decimal:2',
    ];

    // Getters
    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getBalance(): float
    {
        return $this->balance;
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

    public function setType(string $type): void
    {
        $this->type = $type;
        $this->save();
    }

    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
        $this->save();
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    // Helper methods
    public function updateBalance(): void
    {
        $this->balance = $this->transactions()
            ->selectRaw('SUM(CASE WHEN type = "INCOME" THEN amount ELSE -amount END) as balance')
            ->value('balance') ?? 0;
        $this->save();
    }

    public function getMonthlyBalance(int $year, int $month): float
    {
        return $this->transactions()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('amount');
    }

    public function getFormattedBalance(): string
    {
        return number_format($this->balance, 2, ',', ' ') . ' €';
    }

    public function hasInsufficientFunds(float $amount): bool
    {
        return ($this->balance - abs($amount)) < 0;
    }
}
