<?php

namespace Tests\Feature\Offers;

use App\Filament\Resources\OfferResource;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OfferManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_guests_cannot_access_filament_offers_admin(): void
    {
        $response = $this->get('/admin/offers');

        $response->assertRedirect('/admin/login');
    }

    public function test_non_admin_users_cannot_access_offers_admin(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'account_type' => 'customer',
        ]);

        $response = $this->actingAs($user)->get('/admin/offers');

        $response->assertForbidden();
    }

    public function test_admin_can_access_offers_index(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'account_type' => 'admin',
        ]);
        $offer = Offer::create([
            'name' => 'Monsoon Sale',
            'code' => 'RAIN20',
            'discount_type' => Offer::TYPE_PERCENTAGE,
            'discount_value' => 20.00,
            'status' => Offer::STATUS_ACTIVE,
        ]);

        $response = $this->actingAs($admin)->get('/admin/offers');

        $response->assertOk();
        $response->assertSee('Offers &amp; Discounts', false);
        $response->assertSee('RAIN20');
        $response->assertSee('Monsoon Sale');
    }

    public function test_admin_can_change_discount_from_percentage_to_flat_amount(): void
    {
        $offer = Offer::create([
            'name' => 'Flexible Offer',
            'code' => 'FLEX500',
            'discount_type' => Offer::TYPE_PERCENTAGE,
            'discount_value' => 15.00,
            'status' => Offer::STATUS_ACTIVE,
        ]);

        $this->assertSame(Offer::TYPE_PERCENTAGE, $offer->discount_type);
        $this->assertEquals(15.00, $offer->discount_value);

        // Administrator changes discount to Flat ₹1,000
        $offer->update([
            'discount_type' => Offer::TYPE_FIXED,
            'discount_value' => 1000.00,
        ]);

        $offer->refresh();
        $this->assertSame(Offer::TYPE_FIXED, $offer->discount_type);
        $this->assertEquals(1000.00, $offer->discount_value);
    }

    public function test_featured_selection_rule_enforces_only_one_featured_offer(): void
    {
        $offer1 = Offer::create([
            'name' => 'First Featured',
            'code' => 'FIRST',
            'discount_type' => Offer::TYPE_PERCENTAGE,
            'discount_value' => 10.00,
            'is_featured' => true,
            'status' => Offer::STATUS_ACTIVE,
        ]);

        $this->assertTrue($offer1->fresh()->is_featured);

        // Creating or updating a second offer as featured unfeatures the previous one
        $offer2 = Offer::create([
            'name' => 'Second Featured',
            'code' => 'SECOND',
            'discount_type' => Offer::TYPE_PERCENTAGE,
            'discount_value' => 20.00,
            'is_featured' => true,
            'status' => Offer::STATUS_ACTIVE,
        ]);

        $this->assertFalse($offer1->fresh()->is_featured);
        $this->assertTrue($offer2->fresh()->is_featured);
    }

    public function test_offer_code_is_automatically_uppercased(): void
    {
        $offer = Offer::create([
            'name' => 'Lowercase input test',
            'code' => 'save25now',
            'discount_type' => Offer::TYPE_PERCENTAGE,
            'discount_value' => 25.00,
            'status' => Offer::STATUS_ACTIVE,
        ]);

        $this->assertSame('SAVE25NOW', $offer->code);
    }

    public function test_computed_status_accurately_derives_lifecycle_states(): void
    {
        $draft = Offer::create([
            'name' => 'Draft Offer',
            'code' => 'DRAFT1',
            'discount_type' => Offer::TYPE_PERCENTAGE,
            'discount_value' => 10.00,
            'status' => Offer::STATUS_DRAFT,
        ]);
        $this->assertSame('draft', $draft->computed_status);
        $this->assertFalse($draft->isValidNow());

        $disabled = Offer::create([
            'name' => 'Disabled Offer',
            'code' => 'DISABLED1',
            'discount_type' => Offer::TYPE_PERCENTAGE,
            'discount_value' => 10.00,
            'status' => Offer::STATUS_DISABLED,
        ]);
        $this->assertSame('disabled', $disabled->computed_status);
        $this->assertFalse($disabled->isValidNow());

        $scheduled = Offer::create([
            'name' => 'Future Offer',
            'code' => 'FUTURE1',
            'discount_type' => Offer::TYPE_PERCENTAGE,
            'discount_value' => 10.00,
            'status' => Offer::STATUS_ACTIVE,
            'starts_at' => now()->addDays(5),
        ]);
        $this->assertSame('scheduled', $scheduled->computed_status);
        $this->assertFalse($scheduled->isValidNow());

        $expired = Offer::create([
            'name' => 'Expired Offer',
            'code' => 'EXPIRED1',
            'discount_type' => Offer::TYPE_PERCENTAGE,
            'discount_value' => 10.00,
            'status' => Offer::STATUS_ACTIVE,
            'ends_at' => now()->subDay(),
        ]);
        $this->assertSame('expired', $expired->computed_status);
        $this->assertFalse($expired->isValidNow());

        $exhausted = Offer::create([
            'name' => 'Exhausted Offer',
            'code' => 'EXHAUSTED1',
            'discount_type' => Offer::TYPE_PERCENTAGE,
            'discount_value' => 10.00,
            'status' => Offer::STATUS_ACTIVE,
            'max_redemptions' => 5,
            'total_redemptions' => 5,
        ]);
        $this->assertSame('exhausted', $exhausted->computed_status);
        $this->assertFalse($exhausted->isValidNow());

        $active = Offer::create([
            'name' => 'Live Offer',
            'code' => 'LIVE1',
            'discount_type' => Offer::TYPE_PERCENTAGE,
            'discount_value' => 10.00,
            'status' => Offer::STATUS_ACTIVE,
        ]);
        $this->assertSame('active', $active->computed_status);
        $this->assertTrue($active->isValidNow());
    }

    public function test_product_eligibility_scoping(): void
    {
        $productA = Product::create([
            'name' => 'Product A',
            'slug' => 'product-a',
            'price' => 1000,
            'status' => true,
        ]);
        $productB = Product::create([
            'name' => 'Product B',
            'slug' => 'product-b',
            'price' => 2000,
            'status' => true,
        ]);

        $storewideOffer = Offer::create([
            'name' => 'Storewide',
            'code' => 'ALL10',
            'discount_type' => Offer::TYPE_PERCENTAGE,
            'discount_value' => 10.00,
            'applicable_products_type' => 'all',
            'status' => Offer::STATUS_ACTIVE,
        ]);

        $specificOffer = Offer::create([
            'name' => 'Specific A only',
            'code' => 'ONLYA',
            'discount_type' => Offer::TYPE_PERCENTAGE,
            'discount_value' => 15.00,
            'applicable_products_type' => 'specific',
            'status' => Offer::STATUS_ACTIVE,
        ]);
        $specificOffer->products()->attach($productA->id);

        $this->assertTrue($storewideOffer->appliesToProduct($productA));
        $this->assertTrue($storewideOffer->appliesToProduct($productB));

        $this->assertTrue($specificOffer->appliesToProduct($productA));
        $this->assertFalse($specificOffer->appliesToProduct($productB));
    }
}
