<?php

namespace App\Services;

use App\Models\Offer;
use App\Models\OfferRedemption;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class OfferService
{
    /**
     * Retrieve all active and valid offers for a given product.
     *
     * @return Collection<int, Offer>
     */
    public function getValidOffersForProduct(Product $product, ?User $user = null): Collection
    {
        $offers = Offer::query()
            ->active()
            ->orderByDesc('priority')
            ->orderByDesc('id')
            ->get();

        return $offers->filter(function (Offer $offer) use ($product, $user) {
            if (! $offer->appliesToProduct($product)) {
                return false;
            }

            if ($offer->min_order_value !== null && (float) $product->price < (float) $offer->min_order_value) {
                return false;
            }

            if ($user && $offer->max_redemptions_per_customer !== null) {
                $count = OfferRedemption::query()
                    ->where('offer_id', $offer->id)
                    ->where('user_id', $user->id)
                    ->count();

                if ($count >= $offer->max_redemptions_per_customer) {
                    return false;
                }
            }

            return true;
        })->values();
    }

    /**
     * Find and validate an offer by its promo code.
     *
     * @return array{valid: bool, message?: string, offer?: Offer}
     */
    public function findAndValidateOffer(string $code, Product $product, ?User $user = null): array
    {
        $cleanedCode = Str::upper(trim($code));

        if ($cleanedCode === '') {
            return [
                'valid' => false,
                'message' => 'Please enter a valid promo code.',
            ];
        }

        $offer = Offer::query()
            ->whereRaw('UPPER(code) = ?', [$cleanedCode])
            ->first();

        if (! $offer) {
            return [
                'valid' => false,
                'message' => 'Invalid promo code. Please check and try again.',
            ];
        }

        if ($offer->status === Offer::STATUS_DRAFT) {
            return [
                'valid' => false,
                'message' => 'This promo code is currently in draft and not available.',
            ];
        }

        if ($offer->status === Offer::STATUS_DISABLED) {
            return [
                'valid' => false,
                'message' => 'This offer has been disabled.',
            ];
        }

        if ($offer->starts_at && $offer->starts_at->isFuture()) {
            return [
                'valid' => false,
                'message' => 'This promo code is not active yet.',
            ];
        }

        if ($offer->ends_at && $offer->ends_at->isPast()) {
            return [
                'valid' => false,
                'message' => 'This promo code has expired.',
            ];
        }

        if ($offer->max_redemptions !== null && $offer->total_redemptions >= $offer->max_redemptions) {
            return [
                'valid' => false,
                'message' => 'This offer has reached its maximum redemption limit.',
            ];
        }

        if (! $offer->appliesToProduct($product)) {
            return [
                'valid' => false,
                'message' => 'This offer is not applicable to the selected product.',
            ];
        }

        $subtotal = (float) $product->price;

        if ($offer->min_order_value !== null && $subtotal < (float) $offer->min_order_value) {
            return [
                'valid' => false,
                'message' => sprintf(
                    'This offer requires a minimum order subtotal of ₹%s (current price: ₹%s).',
                    number_format((float) $offer->min_order_value, 2),
                    number_format($subtotal, 2)
                ),
            ];
        }

        if ($user && $offer->max_redemptions_per_customer !== null) {
            $userRedemptions = OfferRedemption::query()
                ->where('offer_id', $offer->id)
                ->where('user_id', $user->id)
                ->count();

            if ($userRedemptions >= $offer->max_redemptions_per_customer) {
                return [
                    'valid' => false,
                    'message' => sprintf(
                        'You have already reached the maximum redemption limit (%d) for this promo code.',
                        $offer->max_redemptions_per_customer
                    ),
                ];
            }
        }

        return [
            'valid' => true,
            'offer' => $offer,
        ];
    }

    /**
     * Compute safe decimal and minor-unit discount breakdown.
     *
     * @return array{
     *     original_price: float,
     *     discount_amount: float,
     *     final_amount: float,
     *     amount_in_paise: int,
     *     discount_type: string,
     *     discount_value: float,
     *     offer_name: string,
     *     promo_code: string
     * }
     */
    public function calculateDiscount(Offer $offer, float|int|string $subtotal): array
    {
        $subtotalFloat = (float) $subtotal;
        $discountValue = (float) $offer->discount_value;
        $discountAmount = 0.0;

        if ($offer->discount_type === Offer::TYPE_PERCENTAGE) {
            $discountAmount = round(($subtotalFloat * $discountValue) / 100, 2);

            if ($offer->max_discount_cap !== null && $discountAmount > (float) $offer->max_discount_cap) {
                $discountAmount = (float) $offer->max_discount_cap;
            }
        } else {
            // Flat / Fixed amount discount
            $discountAmount = min($discountValue, $subtotalFloat);
        }

        // Flat discount must never exceed the subtotal, and payable amount must be at least ₹1.00 for payment gateways
        if ($subtotalFloat > 1.00 && ($subtotalFloat - $discountAmount) < 1.00) {
            $discountAmount = round($subtotalFloat - 1.00, 2);
        } elseif ($subtotalFloat <= 1.00) {
            $discountAmount = 0.0;
        }

        $finalAmount = max(1.00, round($subtotalFloat - $discountAmount, 2));
        $amountInPaise = (int) round($finalAmount * 100);

        return [
            'original_price' => round($subtotalFloat, 2),
            'discount_amount' => round($discountAmount, 2),
            'final_amount' => round($finalAmount, 2),
            'amount_in_paise' => $amountInPaise,
            'discount_type' => $offer->discount_type,
            'discount_value' => round($discountValue, 2),
            'offer_name' => $offer->name,
            'promo_code' => $offer->code,
        ];
    }

    /**
     * Retrieve the currently active featured offer if available.
     */
    public function getFeaturedActiveOffer(): ?Offer
    {
        return Offer::query()->featured()->first();
    }

    /**
     * Record an offer redemption on an order.
     */
    public function recordRedemption(Offer $offer, Order $order, ?User $user, float $discountAmount): OfferRedemption
    {
        $offer->increment('total_redemptions');

        return OfferRedemption::query()->create([
            'offer_id' => $offer->id,
            'user_id' => $user?->id,
            'order_id' => $order->id,
            'discount_amount' => $discountAmount,
        ]);
    }
}
