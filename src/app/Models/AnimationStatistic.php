<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimationStatistic extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'animation_name',
        'session_token',
        'ip_address',
        'city',
        'country',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
