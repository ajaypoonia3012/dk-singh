<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunicationProvider extends Model
{
    protected $hidden = [
        'api_key',
        'api_secret',
    ];

    protected $fillable = [

        'name',
        'type',
        'provider',

        'api_url',
        'api_key',
        'api_secret',

        'sender_id',
        'instance_id',

        'is_active',
        'is_default',

    ];

    protected $casts = [

        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'api_key' => 'encrypted',
        'api_secret' => 'encrypted',

    ];
}
