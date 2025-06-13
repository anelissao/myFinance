<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use DateTime;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array<string>
     */
    protected $fillable = [
        'type',
        'amount',
        'description',
        'date',
        'user_id',
        'account_id',
        'category_id',
    ];

    /**
     * The attributes that should be cast.
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    // Getters
    public function getType(): string
    {
        return $this->type;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getAccountId(): int
    {
        return $this->account_id;
    }

    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    // Setters
    public function setType(string $type): void
    {
        $this->type = $type;
        $this->save();
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
        $this->account?->updateBalance();
        $this->save();
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
        $this->save();
    }

    public function setDate(DateTime $date): void
    {
        $this->date = $date;
        $this->save();
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Helper methods
    public function isIncome(): bool
    {
        return $this->amount > 0;
    }

    public function isExpense(): bool
    {
        return $this->amount < 0;
    }

    public function getFormattedAmount(): string
    {
        return number_format(abs($this->amount), 2, ',', ' ') . ' €';
    }
}
