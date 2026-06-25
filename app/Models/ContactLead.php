<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactLead extends Model
{
    protected $fillable = [

        'name',
        'email',
        'phone',
        'message',
        'status',
        'notes',
        'source',
        'follow_up_date',

    ];

    protected $casts = [

        'follow_up_date' => 'date',

    ];
}