<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourierProvider extends Model
{
    protected $hidden = [
        'api_key',
        'api_secret',
    ];

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
        'api_key' => 'encrypted',
        'api_secret' => 'encrypted',

    ];

    public function shipments()
    {
        return $this->hasMany(
            Shipment::class
        );
    }
}
