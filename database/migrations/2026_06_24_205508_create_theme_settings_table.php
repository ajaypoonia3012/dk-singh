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
    Schema::create('theme_settings', function (Blueprint $table) {

        $table->id();

        $table->string('primary_color')->nullable();
        $table->string('secondary_color')->nullable();
        $table->string('accent_color')->nullable();

        $table->boolean('show_programs')->default(true);
        $table->boolean('show_services')->default(true);
        $table->boolean('show_products')->default(true);
        $table->boolean('show_blogs')->default(true);
        $table->boolean('show_transformations')->default(true);
        $table->boolean('show_plans')->default(true);

        $table->boolean('announcement_enabled')->default(false);
        $table->text('announcement_text')->nullable();
        $table->string('announcement_link')->nullable();

        $table->boolean('popup_enabled')->default(false);
        $table->string('popup_title')->nullable();
        $table->text('popup_description')->nullable();
        $table->string('popup_button_text')->nullable();
        $table->string('popup_button_link')->nullable();
        $table->string('popup_image')->nullable();

        $table->integer('clients_count')->default(0);
        $table->integer('coached_count')->default(0);
        $table->integer('programs_count')->default(0);
        $table->integer('countries_count')->default(0);

        $table->string('theme_name')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_settings');
    }
};
