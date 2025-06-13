<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use DateTime;

class Connection extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'login_time',
        'ip_address',
    ];

    /**
     * The attributes that should be cast.
     * @var array<string, string>
     */
    protected $casts = [
        'login_time' => 'datetime',
    ];

    // Getters
    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getLoginTime(): DateTime
    {
        return $this->login_time;
    }

    public function getIpAddress(): string
    {
        return $this->ip_address;
    }

    // Setters
    public function setLoginTime(DateTime $loginTime): void
    {
        $this->login_time = $loginTime;
        $this->save();
    }

    public function setIpAddress(string $ipAddress): void
    {
        $this->ip_address = $ipAddress;
        $this->save();
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public function getFormattedLoginTime(string $format = 'd/m/Y H:i:s'): string
    {
        return $this->login_time->format($format);
    }

    public function getDurationSinceLogin(): string
    {
        return $this->login_time->diffForHumans();
    }

    public function isCurrentSession(): bool
    {
        return $this->ip_address === request()->ip();
    }
} 