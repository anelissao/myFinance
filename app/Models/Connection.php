<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'login_time',
        'ip_address',
    ];

    protected $casts = [
        'login_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 