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
    Schema::table('settings', function ($table) {

        $table->string('blog_label')->nullable();

        $table->string('product_label')->nullable();

        $table->string('service_label')->nullable();

        $table->string('program_label')->nullable();

        $table->string('business_niche')->nullable();

        $table->text('working_hours')->nullable();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            //
        });
    }
};
