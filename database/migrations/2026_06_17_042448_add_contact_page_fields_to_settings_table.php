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

        $table->string('contact_title')->nullable();

        $table->string('contact_heading')->nullable();

        $table->text('contact_description')->nullable();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('settings', function ($table) {

        $table->dropColumn([

            'contact_title',
            'contact_heading',
            'contact_description',

        ]);

    });
}
};
