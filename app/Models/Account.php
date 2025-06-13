<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'balance',
        'user_id',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function updateBalance()
    {
        $this->balance = $this->transactions()
            ->selectRaw('SUM(CASE WHEN type = "INCOME" THEN amount ELSE -amount END) as balance')
            ->value('balance') ?? 0;
        $this->save();
    }
}
