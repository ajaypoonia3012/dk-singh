<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunicationLog extends Model
{
    protected $fillable = [

        'user_id',
        'communication_provider_id',

        'channel',
        'recipient',

        'message',

        'status',
        'response',

        'sent_at',

    ];

    protected $casts = [

        'sent_at' => 'datetime',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function provider()
    {
        return $this->belongsTo(
            CommunicationProvider::class,
            'communication_provider_id'
        );
    }
}