<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('orders', function (Blueprint $table) {

        $table->text('shipping_address')->nullable();

        $table->string('city')->nullable();

        $table->string('state')->nullable();

        $table->string('pincode')->nullable();

        $table->string('order_status')
              ->default('pending');

        $table->string('tracking_number')
              ->nullable();

        $table->string('courier')
              ->nullable();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {

        $table->dropColumn([
            'shipping_address',
            'city',
            'state',
            'pincode',
            'order_status',
            'tracking_number',
            'courier',
        ]);

    });
}
};
