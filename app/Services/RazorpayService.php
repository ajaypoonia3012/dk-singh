<?php

namespace App\Services;

use Razorpay\Api\Api;
use RuntimeException;
use Throwable;

class RazorpayService
{
    private Api $api;

    public function __construct()
    {
        $key = (string) config('services.razorpay.key');
        $secret = (string) config('services.razorpay.secret');

        if ($key === '' || $secret === '') {
            throw new RuntimeException('Razorpay is not configured.');
        }

        $this->api = new Api($key, $secret);
    }

    public function createOrder(string $type, int $itemId, int $userId, string|float|int $amount): array
    {
        $amountInPaise = $this->amountInPaise($amount);
        $receipt = sprintf('%s-%d-%d-%s', $type, $itemId, $userId, now()->format('YmdHis'));

        $order = $this->api->order->create([
            'amount' => $amountInPaise,
            'currency' => 'INR',
            'receipt' => mb_substr($receipt, 0, 40),
            'notes' => [
                'item_type' => $type,
                'item_id' => (string) $itemId,
                'user_id' => (string) $userId,
            ],
        ]);

        return [
            'id' => (string) $order['id'],
            'amount' => $amountInPaise,
            'currency' => 'INR',
        ];
    }

    public function verify(array $payload, array $expected): void
    {
        try {
            $this->api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $payload['razorpay_order_id'],
                'razorpay_payment_id' => $payload['razorpay_payment_id'],
                'razorpay_signature' => $payload['razorpay_signature'],
            ]);

            $order = $this->api->order->fetch($payload['razorpay_order_id']);
            $payment = $this->api->payment->fetch($payload['razorpay_payment_id']);
        } catch (Throwable $exception) {
            report($exception);

            throw new RuntimeException('Payment verification failed.');
        }

        $valid = hash_equals((string) $expected['order_id'], (string) $order['id'])
            && hash_equals((string) $order['id'], (string) $payment['order_id'])
            && (int) $order['amount'] === (int) $expected['amount']
            && (int) $payment['amount'] === (int) $expected['amount']
            && (string) $order['currency'] === (string) $expected['currency']
            && (string) $payment['currency'] === (string) $expected['currency']
            && (string) $payment['status'] === 'captured';

        if (! $valid) {
            throw new RuntimeException('Payment details do not match the checkout session.');
        }
    }

    private function amountInPaise(string|float|int $amount): int
    {
        $amountInPaise = (int) round((float) $amount * 100);

        if ($amountInPaise < 100) {
            throw new RuntimeException('Payment amount is invalid.');
        }

        return $amountInPaise;
    }
}
