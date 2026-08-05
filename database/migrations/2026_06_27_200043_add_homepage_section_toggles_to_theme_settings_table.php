<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('theme_settings', function (Blueprint $table) {

            $table->boolean('show_hero')->default(true)->after('accent_color');

            $table->boolean('show_about')->default(true)->after('show_plans');

            $table->boolean('show_bmi')->default(true)->after('show_about');

            $table->boolean('show_homepage_cards')->default(true)->after('show_bmi');

            $table->boolean('show_testimonials')->default(true)->after('show_homepage_cards');

            $table->boolean('show_contact')->default(true)->after('show_testimonials');

        });
    }

    public function down(): void
    {
        Schema::table('theme_settings', function (Blueprint $table) {

            $table->dropColumn([
                'show_hero',
                'show_about',
                'show_bmi',
                'show_homepage_cards',
                'show_testimonials',
                'show_contact',
            ]);

        });
    }
};