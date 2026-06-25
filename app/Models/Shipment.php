<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = [

        'order_id',
        'courier_provider_id',

        'awb_number',
        'tracking_number',

        'shipment_status',
        'pickup_status',

        'label_url',
        'tracking_url',

        'picked_up_at',
        'delivered_at',

        'estimated_delivery',

    ];

    protected $casts = [

        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
        'estimated_delivery' => 'date',

    ];

protected static function booted()
{
    static::updated(function ($shipment) {

        if ($shipment->wasChanged('shipment_status')) {

            $messages = [

                'packed' => 'Shipment packed and ready for pickup',

                'shipped' => 'Shipment picked up by courier',

                'out_for_delivery' => 'Shipment is out for delivery',

                'delivered' => 'Shipment delivered successfully',

                'cancelled' => 'Shipment cancelled',

            ];

            \App\Models\ShipmentEvent::create([

                'shipment_id' => $shipment->id,

                'event_type' => $shipment->shipment_status,

                'message' =>
                    $messages[$shipment->shipment_status]
                    ?? ucfirst($shipment->shipment_status),

                'event_time' => now(),

            ]);
        }

    });
}

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
public function events()
{
    return $this->hasMany(
        ShipmentEvent::class
    )->latest('event_time');
}
    public function courierProvider()
    {
        return $this->belongsTo(
            CourierProvider::class
        );
    }
}