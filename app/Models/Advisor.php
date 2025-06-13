<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advisor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'specialization',
        'rating',
        'description',
        'is_public',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'is_public' => 'boolean',
    ];

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }
} 