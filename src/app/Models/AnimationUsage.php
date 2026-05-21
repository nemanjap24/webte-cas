<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimationUsage extends Model
{
    protected $fillable = [
        'user_token',
        'animation_type',
        'city',
        'country',
        'used_at',
    ];

    protected $casts = [
        'used_at' => 'datetime',
    ];
}