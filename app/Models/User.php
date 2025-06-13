<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'preferences',
    ];

    /**
     * The attributes that should be hidden for serialization.
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'preferences' => 'json',
    ];

    // Getters
    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getPreferences(): array
    {
        return $this->preferences;
    }

    public function getEmailVerifiedAt(): ?\DateTime
    {
        return $this->email_verified_at;
    }

    // Setters
    public function setName(string $name): void
    {
        $this->name = $name;
        $this->save();
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
        $this->save();
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
        $this->save();
    }

    public function setPreferences(array $preferences): void
    {
        $this->preferences = $preferences;
        $this->save();
    }

    /**
     * Get the accounts associated with the user.
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    /**
     * Get the transactions associated with the user.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the budgets associated with the user.
     */
    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class);
    }

    /**
     * Get the financial goals associated with the user.
     */
    public function goals(): HasMany
    {
        return $this->hasMany(FinancialGoal::class);
    }

    /**
     * Get the connections associated with the user.
     */
    public function connections(): HasMany
    {
        return $this->hasMany(Connection::class);
    }
}
