<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('homepage_cards', function (Blueprint $table) {

            $table->text('description')->nullable()->after('subtitle');

            $table->string('button_text')->nullable()->after('description');

            $table->string('button_link')->nullable()->after('button_text');

            $table->string('background_image')->nullable()->after('button_link');

            $table->string('seo_title')->nullable()->after('is_active');

            $table->text('seo_description')->nullable()->after('seo_title');

        });
    }

    public function down(): void
    {
        Schema::table('homepage_cards', function (Blueprint $table) {

            $table->dropColumn([
                'description',
                'button_text',
                'button_link',
                'background_image',
                'seo_title',
                'seo_description',
            ]);

        });
    }
};