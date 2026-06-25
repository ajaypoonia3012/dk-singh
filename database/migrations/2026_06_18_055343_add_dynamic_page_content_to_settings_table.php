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

        $table->string('footer_links_heading')->nullable();

        $table->string('footer_services_heading')->nullable();

        $table->string('services_page_label')->nullable();

        $table->text('services_page_description')->nullable();

        $table->string('programs_page_label')->nullable();

        $table->text('programs_page_description')->nullable();

        $table->string('transformations_page_label')->nullable();

        $table->string('transformations_page_title')->nullable();

        $table->text('transformations_page_description')->nullable();

$table->string('supplements_label')->nullable();

$table->string('supplements_heading')->nullable();

$table->string('view_product_text')->nullable();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('settings', function (Blueprint $table) {

        $table->dropColumn([

            'footer_links_heading',
            'footer_services_heading',

            'services_page_label',
            'services_page_description',

            'programs_page_label',
            'programs_page_description',

            'transformations_page_label',
            'transformations_page_title',
            'transformations_page_description',

        ]);

    });
}
};
