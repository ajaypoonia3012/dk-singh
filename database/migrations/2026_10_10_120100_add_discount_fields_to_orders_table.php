<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->nullable()->after('amount');
            $table->decimal('discount_amount', 10, 2)->default(0.00)->after('subtotal');
            $table->foreignId('offer_id')->nullable()->after('discount_amount')->constrained('offers')->nullOnDelete();
            $table->string('promo_code')->nullable()->after('offer_id');
            $table->json('pricing_snapshot')->nullable()->after('promo_code');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['offer_id']);
            $table->dropColumn(['subtotal', 'discount_amount', 'offer_id', 'promo_code', 'pricing_snapshot']);
        });
    }
};
