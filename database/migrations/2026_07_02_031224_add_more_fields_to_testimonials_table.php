<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {

            $table->string('profession')->nullable()->after('location');

            $table->string('transformation')->nullable()->after('profession');

            $table->string('program')->nullable()->after('transformation');

            $table->integer('sort_order')->default(0)->after('status');

            $table->string('seo_title')->nullable()->after('sort_order');

            $table->text('seo_description')->nullable()->after('seo_title');

        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {

            $table->dropColumn([
                'profession',
                'transformation',
                'program',
                'sort_order',
                'seo_title',
                'seo_description',
            ]);

        });
    }
};