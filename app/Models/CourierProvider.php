<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourierProvider extends Model
{
    protected $fillable = [

        'name',
        'provider_type',

        'api_url',
        'api_key',
        'api_secret',

        'is_active',
        'is_default',

    ];

    protected $casts = [

        'is_active' => 'boolean',
        'is_default' => 'boolean',

    ];

    public function shipments()
    {
        return $this->hasMany(
            Shipment::class
        );
    }
}