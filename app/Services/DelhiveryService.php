<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;

class DelhiveryService
{
    public function config()
    {
        return [

            'url' => env('DELHIVERY_API_URL'),

            'token' => env('DELHIVERY_TOKEN'),

            'pickup_location' =>
                env('DELHIVERY_PICKUP_LOCATION'),

        ];
    }

    public function testConnection()
    {
        return [

            'configured' =>
                !empty(env('DELHIVERY_TOKEN')),

            'pickup_location' =>
                env('DELHIVERY_PICKUP_LOCATION'),

        ];
    }

public function createOrder($order, $product)
{
    return [

        'shipments' => [

            [

                'name' => $order->customer_name,

                'order' => $order->order_number,

                'phone' => $order->customer_phone,

                'add' => $order->shipping_address,

                'pin' => $order->pincode,

                'city' => $order->city,

                'state' => $order->state,

                'country' => 'India',

                'payment_mode' => 'Prepaid',

                'products_desc' => $product->name,

                'quantity' => 1,

                'weight' => ($product->weight * 1000),

                'shipping_mode' => 'Surface',

                'seller_name' => env('SELLER_NAME'),

                'seller_add' => env('SELLER_ADDRESS'),

                'seller_inv' => $order->invoice_number,

                'total_amount' => $order->amount,

                'shipment_length' =>
                    env('DELHIVERY_LENGTH'),

                'shipment_width' =>
                    env('DELHIVERY_WIDTH'),

                'shipment_height' =>
                    env('DELHIVERY_HEIGHT'),

            ]

        ],

        'pickup_location' => [

            'name' =>
                env('DELHIVERY_PICKUP_LOCATION')

        ]

    ];
}

public function sendToDelhivery($order, $product)
{
    $payload = $this->createOrder(
        $order,
        $product
    );

    return Http::asForm()->withHeaders([

        'Authorization' =>
            'Token ' . env('DELHIVERY_TOKEN'),

        'Accept' => 'application/json',

    ])->post(

        env('DELHIVERY_API_URL')
        . '/api/cmu/create.json',

        [

            'format' => 'json',

            'data' => json_encode(
                $payload
            ),

        ]

    )->json();
}
}