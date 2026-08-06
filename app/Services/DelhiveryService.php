<?php

namespace App\Services;

use App\Models\CourierProvider;
use Illuminate\Support\Facades\Http;

class DelhiveryService
{
    public function config()
    {
        $credentials = $this->credentials();

        return [

            'url' => $credentials['url'],

            'configured' => filled($credentials['token']),

            'pickup_location' => env('DELHIVERY_PICKUP_LOCATION'),

        ];
    }

    public function testConnection()
    {
        $credentials = $this->credentials();

        return [

            'configured' => filled($credentials['token']),

            'pickup_location' => env('DELHIVERY_PICKUP_LOCATION'),

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

                    'shipment_length' => env('DELHIVERY_LENGTH'),

                    'shipment_width' => env('DELHIVERY_WIDTH'),

                    'shipment_height' => env('DELHIVERY_HEIGHT'),

                ],

            ],

            'pickup_location' => [

                'name' => env('DELHIVERY_PICKUP_LOCATION'),

            ],

        ];
    }

    public function sendToDelhivery($order, $product)
    {
        $credentials = $this->credentials();

        if (blank($credentials['url']) || blank($credentials['token'])) {
            return [
                'success' => false,
                'message' => 'Delhivery is not configured.',
            ];
        }

        $payload = $this->createOrder(
            $order,
            $product
        );

        return Http::asForm()->withHeaders([

            'Authorization' => 'Token '.$credentials['token'],

            'Accept' => 'application/json',

        ])->post(

            rtrim($credentials['url'], '/')
            .'/api/cmu/create.json',

            [

                'format' => 'json',

                'data' => json_encode(
                    $payload
                ),

            ]

        )->json();
    }

    /**
     * Resolve decrypted credentials only within this backend integration.
     *
     * @return array{url: string|null, token: string|null}
     */
    private function credentials(): array
    {
        $provider = CourierProvider::query()
            ->where('provider_type', 'delhivery')
            ->where('is_active', true)
            ->orderByDesc('is_default')
            ->first();

        return [
            'url' => $provider?->api_url ?: config('services.delhivery.url'),
            'token' => $provider?->api_key ?: config('services.delhivery.token'),
        ];
    }
}
