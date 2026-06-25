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

    ];

    protected $casts = [

        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'status' => 'boolean',

    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
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
    | ACTIVE CHECK
    |--------------------------------------------------------------------------
    */

    public function isActive()
    {
        return $this->status && 
	$this->expires_at && 
	$this->expires_at >= now();
    }
}