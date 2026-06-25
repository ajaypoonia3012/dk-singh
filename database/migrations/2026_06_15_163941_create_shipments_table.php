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
    Schema::create('shipments', function (Blueprint $table) {

        $table->id();

        $table->foreignId('order_id');

        $table->foreignId('courier_provider_id')
              ->nullable();

        $table->string('awb_number')
              ->nullable();

        $table->string('tracking_number')
              ->nullable();

        $table->string('shipment_status')
              ->default('pending');

        $table->string('pickup_status')
              ->default('pending');

        $table->text('label_url')
              ->nullable();

        $table->text('tracking_url')
              ->nullable();

        $table->dateTime('picked_up_at')
              ->nullable();

        $table->dateTime('delivered_at')
              ->nullable();

        $table->date('estimated_delivery')
              ->nullable();

        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
