<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CasSession extends Model
{
    protected $fillable = [
        'session_token',
        'context',
        'sim_state',
    ];

    protected $casts = [
        'sim_state' => 'array',
    ];
}