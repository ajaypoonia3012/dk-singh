<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | RENAME OLD COLUMNS
            |--------------------------------------------------------------------------
            */

            $table->renameColumn('product_type', 'item_type');

            $table->renameColumn('product_id', 'item_id');

            $table->renameColumn('status', 'payment_status');

        });

        Schema::table('orders', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | NEW PAYMENT FIELDS
            |--------------------------------------------------------------------------
            */

            $table->string('payment_gateway')
                ->nullable()
                ->after('payment_status');

            $table->string('payment_id')
                ->nullable()
                ->after('payment_gateway');

        });
    }

    public function down(): void
    {
        //
    }
};