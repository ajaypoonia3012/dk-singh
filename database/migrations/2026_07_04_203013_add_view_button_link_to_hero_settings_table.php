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
    Schema::table('hero_settings', function (Blueprint $table) {

        $table->string('view_button_link')
              ->default('/transformations')
              ->after('view_button_text');

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('hero_settings', function (Blueprint $table) {

        $table->dropColumn('view_button_link');

    });
}
};
