<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentEvent extends Model
{
    protected $fillable = [

        'shipment_id',

        'event_type',

        'message',

        'event_time',

    ];

    protected $casts = [

        'event_time' => 'datetime',

    ];

    public function shipment()
    {
        return $this->belongsTo(
            Shipment::class
        );
    }
}