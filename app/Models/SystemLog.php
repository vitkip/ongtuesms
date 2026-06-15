<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    protected $table = 'system_logs';

    const UPDATED_AT = null;

    protected $fillable = [
        'level',
        'message',
        'user_id',
        'ip_address',
        'user_agent',
        'context',
    ];

    protected $casts = [
        'context' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
