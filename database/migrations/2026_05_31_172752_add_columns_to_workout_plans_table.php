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
    Schema::table('workout_plans', function (Blueprint $table) {

        $table->string('title')->nullable();

        $table->longText('description')->nullable();

        $table->longText('content')->nullable();

        $table->string('difficulty')->nullable();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('workout_plans', function (Blueprint $table) {

        $table->dropColumn([
            'title',
            'description',
            'content',
            'difficulty'
        ]);

    });
}
};
