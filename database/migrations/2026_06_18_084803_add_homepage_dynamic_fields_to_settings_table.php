<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {

            $table->string('hero_card_title')->nullable();
            $table->text('hero_card_text')->nullable();

            $table->text('programs_description')->nullable();
            $table->text('services_description')->nullable();

            $table->string('testimonials_title')->nullable();
            $table->string('testimonials_heading')->nullable();
            $table->text('testimonials_description')->nullable();

            $table->text('contact_map_text')->nullable();

            $table->string('bmi_label')->nullable();
            $table->string('bmi_heading')->nullable();
            $table->text('bmi_description')->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {

            $table->dropColumn([
                'hero_card_title',
                'hero_card_text',
                'programs_description',
                'services_description',
                'testimonials_title',
                'testimonials_heading',
                'testimonials_description',
                'contact_map_text',
                'bmi_label',
                'bmi_heading',
                'bmi_description',
            ]);

        });
    }
};