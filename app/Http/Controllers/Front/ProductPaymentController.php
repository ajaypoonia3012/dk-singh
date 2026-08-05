<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\CourierProvider;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\ShipmentEvent;
use App\Services\CommunicationService;
use App\Services\DelhiveryService;
use App\Services\RazorpayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class ProductPaymentController extends Controller
{
    public function checkout(int $id, RazorpayService $razorpay): View|RedirectResponse
    {
        $product = Product::query()->where('status', true)->findOrFail($id);

        try {
            $razorpayOrder = $razorpay->createOrder('product', $product->id, (int) auth()->id(), $product->price);
        } catch (Throwable $exception) {
            report($exception);

            return redirect('/products')->with('error', 'Payment service is temporarily unavailable.');
        }

        session()->put("razorpay_orders.{$razorpayOrder['id']}", [
            'order_id' => $razorpayOrder['id'],
            'amount' => $razorpayOrder['amount'],
            'currency' => $razorpayOrder['currency'],
            'item_type' => 'product',
            'item_id' => $product->id,
            'user_id' => (int) auth()->id(),
            'created_at' => now()->timestamp,
        ]);

        return view('checkout.product-payment', compact('product', 'razorpayOrder'));
    }

    public function success(Request $request, RazorpayService $razorpay): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'shipping_address' => ['required', 'string', 'max:2000'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'pincode' => ['required', 'string', 'max:20'],
            'razorpay_order_id' => ['required', 'string', 'max:255'],
            'razorpay_payment_id' => ['required', 'string', 'max:255'],
            'razorpay_signature' => ['required', 'string', 'max:512'],
        ]);

        $expected = session("razorpay_orders.{$validated['razorpay_order_id']}");

        if (! is_array($expected)
            || $expected['item_type'] !== 'product'
            || (int) $expected['item_id'] !== (int) $validated['product_id']
            || (int) $expected['user_id'] !== (int) auth()->id()
            || (int) $expected['created_at'] < now()->subHour()->timestamp) {
            return redirect('/products')->with('error', 'Payment session is invalid or expired.');
        }

        try {
            $razorpay->verify($validated, $expected);
        } catch (RuntimeException $exception) {
            report($exception);

            return redirect('/products')->with('error', 'Payment verification failed.');
        }

        if (Order::query()->where('payment_id', $validated['razorpay_payment_id'])->exists()) {
            session()->forget("razorpay_orders.{$validated['razorpay_order_id']}");

            return redirect('/products')->with('error', 'This payment has already been processed.');
        }

        $product = Product::query()->where('status', true)->findOrFail($validated['product_id']);

        $order = DB::transaction(fn (): Order => Order::query()->create([
            'user_id' => auth()->id(),
            'customer_name' => $validated['customer_name'],
            'customer_email' => auth()->user()->email,
            'customer_phone' => $validated['customer_phone'],
            'item_type' => 'product',
            'item_id' => $product->id,
            'amount' => $product->price,
            'payment_status' => 'paid',
            'payment_gateway' => 'razorpay',
            'payment_id' => $validated['razorpay_payment_id'],
            'shipping_address' => $validated['shipping_address'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'pincode' => $validated['pincode'],
            'order_status' => 'pending',
        ]));

        session()->forget("razorpay_orders.{$validated['razorpay_order_id']}");
        $this->createShipment($order, $product);

        app(CommunicationService::class)->send('order_confirmed', auth()->user(), [
            'name' => $order->customer_name,
            'order_number' => $order->order_number,
            'amount' => $order->amount,
        ]);

        return redirect('/products')->with('success', 'Product purchased successfully.');
    }

    private function createShipment(Order $order, Product $product): void
    {
        $provider = CourierProvider::query()->where('is_default', true)->where('is_active', true)->first();

        if (! $provider) {
            return;
        }

        $shipment = Shipment::query()->create([
            'order_id' => $order->id,
            'courier_provider_id' => $provider->id,
            'shipment_status' => 'created',
            'pickup_status' => 'pending',
        ]);

        $response = app(DelhiveryService::class)->sendToDelhivery($order, $product);

        if (($response['success'] ?? false) && ! empty($response['packages'][0]['waybill'])) {
            $awb = $response['packages'][0]['waybill'];
            $shipment->update([
                'awb_number' => $awb,
                'tracking_number' => $awb,
                'tracking_url' => 'https://www.delhivery.com/track/package/'.$awb,
                'shipment_status' => 'packed',
            ]);
        }

        ShipmentEvent::query()->create([
            'shipment_id' => $shipment->id,
            'event_type' => 'created',
            'message' => 'Order created and awaiting packing',
            'event_time' => now(),
        ]);
    }
}
