<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Plan;

class Order extends Model
{
    protected $fillable = [

        'user_id',

        'customer_name',
        'customer_email',
        'customer_phone',

        'item_type',
        'item_id',

        'amount',

        'payment_status',
        'payment_gateway',
        'payment_id',

'shipping_address',
'city',
'state',
'pincode',

'order_status',
'tracking_number',
'courier',

'order_number',
'invoice_number',

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

public function product()
{
    return $this->belongsTo(
        Product::class,
        'item_id'
    );
}

public function plan()
{
    return $this->belongsTo(
        Plan::class,
        'item_id'
    );
}

protected static function booted()
{
    static::created(function ($order) {

        $order->update([

            'order_number' =>
                'DK' . str_pad(
                    $order->id,
                    6,
                    '0',
                    STR_PAD_LEFT
                ),

            'invoice_number' =>
                'INV' . str_pad(
                    $order->id,
                    6,
                    '0',
                    STR_PAD_LEFT
                ),

        ]);

    });
}
public function shipment()
{
    return $this->hasOne(
        \App\Models\Shipment::class
    );
}
}