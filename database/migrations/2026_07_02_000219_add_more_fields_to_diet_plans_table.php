<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('diet_plans', function (Blueprint $table) {

            $table->boolean('featured')
                ->default(false)
                ->after('status');

            $table->integer('sort_order')
                ->default(0)
                ->after('featured');

            $table->string('seo_title')
                ->nullable()
                ->after('sort_order');

            $table->text('seo_description')
                ->nullable()
                ->after('seo_title');

        });
    }

    public function down(): void
    {
        Schema::table('diet_plans', function (Blueprint $table) {

            $table->dropColumn([
                'featured',
                'sort_order',
                'seo_title',
                'seo_description',
            ]);

        });
    }
};