<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('discount_type')->default('percentage'); // 'percentage', 'fixed'
            $table->decimal('discount_value', 10, 2);
            $table->decimal('max_discount_cap', 10, 2)->nullable();
            $table->decimal('min_order_value', 10, 2)->nullable();
            $table->string('applicable_products_type')->default('all'); // 'all', 'specific'
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->string('status')->default('active'); // 'draft', 'active', 'disabled'
            $table->unsignedInteger('max_redemptions')->nullable();
            $table->unsignedInteger('total_redemptions')->default(0);
            $table->unsignedInteger('max_redemptions_per_customer')->nullable()->default(1);
            $table->boolean('is_featured')->default(false);
            $table->integer('priority')->default(0);
            $table->boolean('allow_stacking')->default(false);
            $table->string('banner_headline')->nullable();
            $table->text('banner_description')->nullable();
            $table->string('banner_cta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'starts_at', 'ends_at']);
            $table->index('code');
            $table->index(['is_featured', 'priority']);
        });

        Schema::create('offer_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_id')->constrained('offers')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['offer_id', 'product_id']);
        });

        Schema::create('offer_redemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_id')->constrained('offers')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->decimal('discount_amount', 10, 2);
            $table->timestamps();

            $table->index(['offer_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offer_redemptions');
        Schema::dropIfExists('offer_product');
        Schema::dropIfExists('offers');
    }
};
