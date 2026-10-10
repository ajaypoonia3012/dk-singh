<?php

namespace Tests\Unit;

use App\Models\Offer;
use App\Services\OfferService;
use PHPUnit\Framework\TestCase;

class OfferCalculationTest extends TestCase
{
    private OfferService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new OfferService();
    }

    public function test_percentage_discount_calculation(): void
    {
        // Example A from specifications:
        // Original: ₹1,499.00, Offer: 15% off, Discount: ₹224.85, Final: ₹1,274.15
        $offer = new Offer([
            'name' => '15% Off',
            'code' => 'TEST15',
            'discount_type' => Offer::TYPE_PERCENTAGE,
            'discount_value' => 15.00,
        ]);

        $result = $this->service->calculateDiscount($offer, 1499.00);

        $this->assertSame(1499.00, $result['original_price']);
        $this->assertSame(224.85, $result['discount_amount']);
        $this->assertSame(1274.15, $result['final_amount']);
        $this->assertSame(127415, $result['amount_in_paise']);
    }

    public function test_flat_discount_calculation(): void
    {
        // Example B from specifications:
        // Original: ₹2,500.00, Offer: ₹1,000 off, Discount: ₹1,000.00, Final: ₹1,500.00
        $offer = new Offer([
            'name' => 'Flat ₹1000 Off',
            'code' => 'FLAT1000',
            'discount_type' => Offer::TYPE_FIXED,
            'discount_value' => 1000.00,
        ]);

        $result = $this->service->calculateDiscount($offer, 2500.00);

        $this->assertSame(2500.00, $result['original_price']);
        $this->assertSame(1000.00, $result['discount_amount']);
        $this->assertSame(1500.00, $result['final_amount']);
        $this->assertSame(150000, $result['amount_in_paise']);
    }

    public function test_percentage_discount_respects_max_discount_cap(): void
    {
        $offer = new Offer([
            'name' => '50% Off Capped',
            'code' => 'HALF500',
            'discount_type' => Offer::TYPE_PERCENTAGE,
            'discount_value' => 50.00,
            'max_discount_cap' => 300.00,
        ]);

        // 50% of ₹2,000 is ₹1,000, but cap is ₹300
        $result = $this->service->calculateDiscount($offer, 2000.00);

        $this->assertSame(2000.00, $result['original_price']);
        $this->assertSame(300.00, $result['discount_amount']);
        $this->assertSame(1700.00, $result['final_amount']);
        $this->assertSame(170000, $result['amount_in_paise']);
    }

    public function test_flat_discount_never_exceeds_eligible_subtotal_and_ensures_minimum_payment(): void
    {
        $offer = new Offer([
            'name' => 'Huge Discount',
            'code' => 'HUGE5000',
            'discount_type' => Offer::TYPE_FIXED,
            'discount_value' => 5000.00,
        ]);

        // Product price is only ₹800. Discount should not exceed price, and payable must be at least ₹1.00 for payment gateways
        $result = $this->service->calculateDiscount($offer, 800.00);

        $this->assertSame(800.00, $result['original_price']);
        $this->assertSame(799.00, $result['discount_amount']);
        $this->assertSame(1.00, $result['final_amount']);
        $this->assertSame(100, $result['amount_in_paise']);
    }

    public function test_discount_cannot_create_negative_final_amount(): void
    {
        $offer = new Offer([
            'name' => 'Freebie Attempt',
            'code' => 'ZERO',
            'discount_type' => Offer::TYPE_FIXED,
            'discount_value' => 100.00,
        ]);

        $result = $this->service->calculateDiscount($offer, 50.00);

        $this->assertGreaterThanOrEqual(1.00, $result['final_amount']);
        $this->assertGreaterThanOrEqual(100, $result['amount_in_paise']);
    }
}
