<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hero_settings', function (Blueprint $table) {

            $table->foreignId('background_media_id')
                ->nullable()
                ->after('background')
                ->constrained('media')
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('hero_settings', function (Blueprint $table) {

            $table->dropForeign(['background_media_id']);

            $table->dropColumn('background_media_id');

        });
    }
};
