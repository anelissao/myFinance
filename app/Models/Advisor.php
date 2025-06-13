<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Advisor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'specialization',
        'rating',
        'description',
        'is_public',
    ];

    /**
     * The attributes that should be cast.
     * @var array<string, string>
     */
    protected $casts = [
        'rating' => 'decimal:1',
        'is_public' => 'boolean',
    ];

    // Getters
    public function getName(): string
    {
        return $this->name;
    }

    public function getSpecialization(): string
    {
        return $this->specialization;
    }

    public function getRating(): float
    {
        return $this->rating;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isPublic(): bool
    {
        return $this->is_public;
    }

    // Setters
    public function setName(string $name): void
    {
        $this->name = $name;
        $this->save();
    }

    public function setSpecialization(string $specialization): void
    {
        $this->specialization = $specialization;
        $this->save();
    }

    public function setRating(float $rating): void
    {
        $this->rating = min(5, max(0, $rating));
        $this->save();
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
        $this->save();
    }

    public function setIsPublic(bool $isPublic): void
    {
        $this->is_public = $isPublic;
        $this->save();
    }

    // Scopes
    public function scopePublic(Builder $query): Builder
    {
        return $query->where('is_public', true);
    }

    public function scopeTopRated(Builder $query, int $limit = 5): Builder
    {
        return $query->where('is_public', true)
                    ->orderByDesc('rating')
                    ->limit($limit);
    }

    public function scopeBySpecialization(Builder $query, string $specialization): Builder
    {
        return $query->where('specialization', $specialization);
    }

    // Helper methods
    public function getFormattedRating(): string
    {
        return number_format($this->rating, 1) . '/5.0';
    }

    public function isTopRated(): bool
    {
        return $this->rating >= 4.0;
    }

    public function getPublicProfile(): array
    {
        if (!$this->is_public) {
            return [];
        }

        return [
            'name' => $this->name,
            'specialization' => $this->specialization,
            'rating' => $this->getFormattedRating(),
            'description' => $this->description,
        ];
    }
} 