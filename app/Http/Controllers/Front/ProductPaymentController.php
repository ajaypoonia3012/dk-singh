<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;

class ProductPaymentController extends Controller
{
   public function checkout($id)
{
    $product = Product::findOrFail($id);

    return view(
        'checkout.product-payment',
        compact('product')
    );
}

public function success(Request $request)
{

$request->validate([

    'product_id' => 'required|exists:products,id',

    'customer_name' => 'required|string|max:255',

    'customer_phone' => 'required|string|max:20',

    'shipping_address' => 'required|string',

    'city' => 'required|string|max:100',

    'state' => 'required|string|max:100',

    'pincode' => 'required|string|max:20',

    'razorpay_order_id' => 'required',

    'razorpay_payment_id' => 'required',

    'razorpay_signature' => 'required',

]);
    if (!auth()->check()) {

    return redirect('/products')
        ->with('error', 'Please login.');

}

/*
|--------------------------------------------------------------------------
| TEMPORARY PAYMENT VERIFY DISABLED
|--------------------------------------------------------------------------
*/

    $product = Product::findOrFail(
        $request->product_id
    );
if (!$product->status) {

    return redirect('/products')
        ->with('error', 'Product unavailable.');

}

    $order = Order::create([


    'user_id' => auth()->id(),

    'customer_name' => $request->customer_name,

    'customer_email' => auth()->user()->email,

    'customer_phone' => $request->customer_phone,

    'item_type' => 'product',

    'item_id' => $product->id,

    'amount' => $product->price,

    'payment_status' => 'paid',

    'payment_gateway' => 'razorpay',

    'payment_id' => $request->razorpay_payment_id,

    'shipping_address' => $request->shipping_address,

    'city' => $request->city,

    'state' => $request->state,

    'pincode' => $request->pincode,

    'order_status' => 'pending',

]);


$defaultCourier = \App\Models\CourierProvider::where(
    'is_default',
    true
)->first();

if ($defaultCourier) {

    $shipment = \App\Models\Shipment::create([

        'order_id' => $order->id,

        'courier_provider_id' => $defaultCourier->id,

        'shipment_status' => 'created',

        'pickup_status' => 'pending',

    ]);
$product = \App\Models\Product::find(
    $order->item_id
);



$response = (
    new \App\Services\DelhiveryService()
)->sendToDelhivery(
    $order,
    $product
);




if (
    isset($response['success'])
    && $response['success']
    && !empty($response['packages'][0]['waybill'])
) {

    $awb =
        $response['packages'][0]['waybill'];

    $shipment->update([

        'awb_number' => $awb,

        'tracking_number' => $awb,

        'tracking_url' =>
            'https://www.delhivery.com/track/package/'
            . $awb,

        'shipment_status' => 'packed',

    ]);
}

    \App\Models\ShipmentEvent::create([

        'shipment_id' => $shipment->id,

        'event_type' => 'created',

        'message' => 'Order created and awaiting packing',

        'event_time' => now(),

    ]);

app(\App\Services\CommunicationService::class)
    ->send(
        'order_confirmed',
        auth()->user(),
        [

            'name' => $order->customer_name,

            'order_number' =>
                $order->order_number,

            'amount' =>
                $order->amount,

        ]
    );


}

    return redirect('/products')
        ->with('success', 'Product purchased successfully.');
}
}