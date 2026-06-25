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
    Schema::table('diet_plans', function (Blueprint $table) {

        $table->string('category')->nullable();

        $table->enum(
            'diet_type',
            ['veg', 'non_veg']
        )->default('veg');

        $table->enum(
            'required_access',
            ['public', 'basic', 'pro', 'elite']
        )->default('public');

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('diet_plans', function (Blueprint $table) {

        $table->dropColumn([
            'category',
            'diet_type',
            'required_access',
        ]);

    });
}
};
