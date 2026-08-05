<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {

            $table->integer('sort_order')
                ->default(0)
                ->after('status');

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
        Schema::table('services', function (Blueprint $table) {

            $table->dropColumn([
                'sort_order',
                'seo_title',
                'seo_description',
            ]);

        });
    }
};