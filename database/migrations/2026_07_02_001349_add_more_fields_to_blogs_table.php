<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {

            $table->text('excerpt')->nullable()->after('content');

            $table->string('category')->nullable()->after('excerpt');

            $table->string('author')->nullable()->after('category');

            $table->string('reading_time')->nullable()->after('author');

            $table->integer('sort_order')
                ->default(0)
                ->after('status');

            $table->string('seo_title')->nullable();

            $table->text('seo_description')->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {

            $table->dropColumn([
                'excerpt',
                'category',
                'author',
                'reading_time',
                'sort_order',
                'seo_title',
                'seo_description',
            ]);

        });
    }
};