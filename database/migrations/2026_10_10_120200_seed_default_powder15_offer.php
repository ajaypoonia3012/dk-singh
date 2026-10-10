<?php

use App\Models\Offer;
use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Safe mapping of existing powder promo setting to an actual working Offer
        if (! Offer::query()->where('code', 'POWDER15')->exists()) {
            Offer::query()->create([
                'name' => 'Powder Collection 15% Off',
                'code' => 'POWDER15',
                'description' => 'Introductory 15% promotional discount on authentic fitness and wellness powders.',
                'discount_type' => Offer::TYPE_PERCENTAGE,
                'discount_value' => 15.00,
                'max_discount_cap' => null,
                'min_order_value' => null,
                'applicable_products_type' => 'all',
                'status' => Offer::STATUS_ACTIVE,
                'is_featured' => false,
                'priority' => 5,
                'allow_stacking' => false,
                'banner_headline' => 'SPECIAL WELLNESS COLLECTION — 15% OFF',
                'banner_description' => 'Use code POWDER15 at checkout to receive 15% off.',
                'banner_cta' => 'Explore Powders',
            ]);
        }
    }

    public function down(): void
    {
        Offer::query()->where('code', 'POWDER15')->forceDelete();
    }
};
