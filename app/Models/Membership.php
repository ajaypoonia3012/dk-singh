<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = [

        'user_id',
        'plan_id',

        'starts_at',
        'expires_at',

        'status',

        'source',
        'auto_renew',
        'payment_reference',

        'cancelled_at',
        'cancel_reason',
        'notes',

    ];

    protected $casts = [

        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'cancelled_at' => 'datetime',

        'status' => 'boolean',
        'auto_renew' => 'boolean',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isActive(): bool
    {
        return $this->status
            && $this->expires_at
            && $this->expires_at->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->expires_at
            && $this->expires_at->isPast();
    }

    public function remainingDays(): int
    {
        if (! $this->expires_at) {
            return 0;
        }

        return now()->diffInDays($this->expires_at, false);
    }
}