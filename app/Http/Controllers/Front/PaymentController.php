<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Razorpay\Api\Api;

use App\Models\Plan;
use App\Models\Order;
use App\Models\Membership;

class PaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CHECKOUT PAGE
    |--------------------------------------------------------------------------
    */

    public function checkout($id)
    {
        $plan = Plan::findOrFail($id);

        return view('checkout.index', compact('plan'));
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENT SUCCESS
    |--------------------------------------------------------------------------
    */

    public function success(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VERIFY PAYMENT
        |--------------------------------------------------------------------------
        */

        $api = new Api(

            env('RAZORPAY_KEY'),
            env('RAZORPAY_SECRET')

        );

        /*
|--------------------------------------------------------------------------
| TEMPORARY DEV MODE
|--------------------------------------------------------------------------
*/

try {

    $attributes = [

        'razorpay_order_id' =>
            $request->razorpay_order_id,

        'razorpay_payment_id' =>
            $request->razorpay_payment_id,

        'razorpay_signature' =>
            $request->razorpay_signature,

    ];

    $api->utility->verifyPaymentSignature(
        $attributes
    );

} catch (\Exception $e) {

    return redirect('/plans')
        ->with(
            'error',
            'Payment verification failed.'
        );

}
        /*
        |--------------------------------------------------------------------------
        | GET PLAN
        |--------------------------------------------------------------------------
        */

        $plan = Plan::findOrFail($request->plan_id);

        /*
        |--------------------------------------------------------------------------
        | CREATE ORDER
        |--------------------------------------------------------------------------
        */

        

$order = Order::create([

            'user_id' => auth()->id(),

            'customer_name' => auth()->user()->name,

            'customer_email' => auth()->user()->email,

            'customer_phone' => auth()->user()->phone ?? '',

            'item_type' => 'plan',

            'item_id' => $plan->id,

            'amount' => $plan->discount_price
                ?? $plan->price,

            'payment_status' => 'paid',

            'payment_gateway' => 'razorpay',

            'payment_id' => $request->razorpay_payment_id,

        ]);

        /*
        |--------------------------------------------------------------------------
        | CREATE MEMBERSHIP
        |--------------------------------------------------------------------------
        */


Membership::where(
    'user_id',
    auth()->id()
)->update([
    'status' => false,
]);
Membership::create([

    'user_id' => auth()->id(),

    'plan_id' => $plan->id,

    'starts_at' => now(),

    'expires_at' => match ($plan->billing_cycle) {

        'day'   => now()->addDays((int) $plan->duration),

        'week'  => now()->addWeeks((int) $plan->duration),

        'month' => now()->addMonths((int) $plan->duration),

        'year'  => now()->addYears((int) $plan->duration),

        default => now()->addMonths((int) $plan->duration),

    },

    'status' => true,

]);


app(\App\Services\CommunicationService::class)
    ->send(
        'membership_purchased',
        auth()->user(),
        [

            'name' => auth()->user()->name,

            'plan_name' => $plan->name,

            'amount' => $plan->discount_price
                ?? $plan->price,

            'expiry_date' =>
                Membership::latest()
                    ->first()
                    ->expires_at
                    ->format('d M Y'),

        ]
    );



        return redirect('/member/profile')
    ->with(
        'success',
        'Please complete your fitness profile before accessing your dashboard.'
    );

    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENT FAILED
    |--------------------------------------------------------------------------
    */

    public function failed()
    {
        return redirect('/plans')
            ->with('error', 'Payment Failed');
    }
}