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
    Schema::create('weekly_check_ins', function (Blueprint $table) {

        $table->id();

        $table->foreignId('user_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->decimal('weight', 8, 2)
            ->nullable();

        $table->decimal('waist', 8, 2)
            ->nullable();

        $table->integer('energy_level')
            ->nullable();

        $table->integer('mood')
            ->nullable();

        $table->decimal('sleep_hours', 4, 1)
            ->nullable();

        $table->text('notes')
            ->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_check_ins');
    }
};
