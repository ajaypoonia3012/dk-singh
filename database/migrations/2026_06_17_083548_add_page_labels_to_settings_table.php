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
    Schema::table('settings', function (Blueprint $table) {

        $table->string('home_heading')->nullable();

        $table->string('services_heading')->nullable();

        $table->string('programs_heading')->nullable();

        $table->string('transformations_heading')->nullable();

        $table->string('about_cta_text')->nullable();

        $table->string('contact_cta_text')->nullable();

        $table->string('view_programs_text')->nullable();

        $table->string('view_transformations_text')->nullable();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('settings', function (Blueprint $table) {

        $table->dropColumn([
            'home_heading',
            'services_heading',
            'programs_heading',
            'transformations_heading',
            'about_cta_text',
            'contact_cta_text',
            'view_programs_text',
            'view_transformations_text',
        ]);

    });
}
};
