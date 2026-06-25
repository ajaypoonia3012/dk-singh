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
        Schema::create('communication_providers', function (Blueprint $table) {

    $table->id();

    $table->string('name');

    $table->enum('type', [
        'whatsapp',
        'email'
    ]);

    $table->string('provider');

    $table->string('api_url')->nullable();

    $table->longText('api_key')->nullable();

    $table->longText('api_secret')->nullable();

    $table->string('sender_id')->nullable();

    $table->string('instance_id')->nullable();

    $table->boolean('is_active')
        ->default(true);

    $table->boolean('is_default')
        ->default(false);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communication_providers');
    }
};
