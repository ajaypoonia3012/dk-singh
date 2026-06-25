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
        Schema::create('courier_providers', function (Blueprint $table) {

    $table->id();

    $table->string('name');

    $table->string('provider_type');

    $table->text('api_url')->nullable();

    $table->text('api_key')->nullable();

    $table->text('api_secret')->nullable();

    $table->boolean('is_active')->default(true);

    $table->boolean('is_default')->default(false);

    $table->timestamps();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courier_providers');
    }
};
