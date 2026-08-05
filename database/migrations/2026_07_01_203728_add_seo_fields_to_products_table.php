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
    Schema::table('products', function (Blueprint $table) {

        $table->string('category')->nullable()->after('weight');

        $table->integer('sort_order')
            ->default(0)
            ->after('status');

        $table->string('seo_title')->nullable();

        $table->text('seo_description')->nullable();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('products', function (Blueprint $table) {

        $table->dropColumn([
            'category',
            'sort_order',
            'seo_title',
            'seo_description',
        ]);

    });
}
};
