<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\Order;
use App\Models\Plan;
use App\Services\CommunicationService;
use App\Services\RazorpayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class PaymentController extends Controller
{
    public function checkout(int $id, RazorpayService $razorpay): View|RedirectResponse
    {
        $plan = Plan::query()->findOrFail($id);

        try {
            $razorpayOrder = $razorpay->createOrder(
                'plan',
                $plan->id,
                (int) auth()->id(),
                $plan->discount_price ?? $plan->price,
            );
        } catch (Throwable $exception) {
            report($exception);

            return redirect('/plans')->with('error', 'Payment service is temporarily unavailable.');
        }

        session()->put("razorpay_orders.{$razorpayOrder['id']}", [
            'order_id' => $razorpayOrder['id'],
            'amount' => $razorpayOrder['amount'],
            'currency' => $razorpayOrder['currency'],
            'item_type' => 'plan',
            'item_id' => $plan->id,
            'user_id' => (int) auth()->id(),
            'created_at' => now()->timestamp,
        ]);

        return view('checkout.index', compact('plan', 'razorpayOrder'));
    }

    public function success(Request $request, RazorpayService $razorpay): RedirectResponse
    {
        $validated = $request->validate([
            'plan_id' => ['required', 'integer', 'exists:plans,id'],
            'razorpay_order_id' => ['required', 'string', 'max:255'],
            'razorpay_payment_id' => ['required', 'string', 'max:255'],
            'razorpay_signature' => ['required', 'string', 'max:512'],
        ]);

        $expected = session("razorpay_orders.{$validated['razorpay_order_id']}");

        if (! is_array($expected)
            || $expected['item_type'] !== 'plan'
            || (int) $expected['item_id'] !== (int) $validated['plan_id']
            || (int) $expected['user_id'] !== (int) auth()->id()
            || (int) $expected['created_at'] < now()->subHour()->timestamp) {
            return redirect('/plans')->with('error', 'Payment session is invalid or expired.');
        }

        try {
            $razorpay->verify($validated, $expected);
        } catch (RuntimeException $exception) {
            report($exception);

            return redirect('/plans')->with('error', 'Payment verification failed.');
        }

        if (Order::query()->where('payment_id', $validated['razorpay_payment_id'])->exists()) {
            session()->forget("razorpay_orders.{$validated['razorpay_order_id']}");

            return redirect('/plans')->with('error', 'This payment has already been processed.');
        }

        $plan = Plan::query()->findOrFail($validated['plan_id']);

        [$order, $membership] = DB::transaction(function () use ($plan, $validated): array {
            $order = Order::query()->create([
                'user_id' => auth()->id(),
                'customer_name' => auth()->user()->name,
                'customer_email' => auth()->user()->email,
                'customer_phone' => auth()->user()->phone ?? '',
                'item_type' => 'plan',
                'item_id' => $plan->id,
                'amount' => $plan->discount_price ?? $plan->price,
                'payment_status' => 'paid',
                'payment_gateway' => 'razorpay',
                'payment_id' => $validated['razorpay_payment_id'],
            ]);

            Membership::query()
                ->where('user_id', auth()->id())
                ->update(['status' => false]);

            $membership = Membership::query()->create([
                'user_id' => auth()->id(),
                'plan_id' => $plan->id,
                'starts_at' => now(),
                'expires_at' => match ($plan->billing_cycle) {
                    'day' => now()->addDays((int) $plan->duration),
                    'week' => now()->addWeeks((int) $plan->duration),
                    'month' => now()->addMonths((int) $plan->duration),
                    'year' => now()->addYears((int) $plan->duration),
                    default => now()->addMonths((int) $plan->duration),
                },
                'status' => true,
            ]);

            return [$order, $membership];
        });

        session()->forget("razorpay_orders.{$validated['razorpay_order_id']}");

        app(CommunicationService::class)->send('membership_purchased', auth()->user(), [
            'name' => auth()->user()->name,
            'plan_name' => $plan->name,
            'amount' => $order->amount,
            'expiry_date' => $membership->expires_at->format('d M Y'),
        ]);

        return redirect('/member/profile')
            ->with('success', 'Please complete your fitness profile before accessing your dashboard.');
    }

    public function failed(): RedirectResponse
    {
        return redirect('/plans')->with('error', 'Payment failed.');
    }
}
