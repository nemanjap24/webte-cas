<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CasLog extends Model
{
    protected $fillable = [
        'session_token',
        'command',
        'output',
        'is_success',
        'error_message',
        'executed_at',
    ];

    protected $casts = [
        'is_success' => 'boolean',
        'executed_at' => 'datetime',
    ];
}