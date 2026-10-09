<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'hero_card_title')) {
                $table->text('hero_card_title')->nullable();
            }
            if (!Schema::hasColumn('settings', 'hero_card_text')) {
                $table->text('hero_card_text')->nullable();
            }
            if (!Schema::hasColumn('settings', 'programs_description')) {
                $table->text('programs_description')->nullable();
            }
            if (!Schema::hasColumn('settings', 'services_description')) {
                $table->text('services_description')->nullable();
            }
            if (!Schema::hasColumn('settings', 'testimonials_title')) {
                $table->text('testimonials_title')->nullable();
            }
            if (!Schema::hasColumn('settings', 'testimonials_heading')) {
                $table->text('testimonials_heading')->nullable();
            }
            if (!Schema::hasColumn('settings', 'testimonials_description')) {
                $table->text('testimonials_description')->nullable();
            }
            if (!Schema::hasColumn('settings', 'contact_map_text')) {
                $table->text('contact_map_text')->nullable();
            }
            if (!Schema::hasColumn('settings', 'bmi_label')) {
                $table->text('bmi_label')->nullable();
            }
            if (!Schema::hasColumn('settings', 'bmi_heading')) {
                $table->text('bmi_heading')->nullable();
            }
            if (!Schema::hasColumn('settings', 'bmi_description')) {
                $table->text('bmi_description')->nullable();
            }
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