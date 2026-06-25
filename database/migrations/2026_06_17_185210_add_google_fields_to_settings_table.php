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

        $table->string('google_site_verification')->nullable();

        $table->string('google_analytics_id')->nullable();

    });
}

public function down(): void
{
    Schema::table('settings', function (Blueprint $table) {

        $table->dropColumn([
            'google_site_verification',
            'google_analytics_id',
        ]);

    });
}
};