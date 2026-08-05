<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transformations', function (Blueprint $table) {

            $table->foreignId('before_media_id')
                ->nullable()
                ->after('before_image')
                ->constrained('media')
                ->nullOnDelete();

            $table->foreignId('after_media_id')
                ->nullable()
                ->after('after_image')
                ->constrained('media')
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('transformations', function (Blueprint $table) {

            $table->dropConstrainedForeignId('before_media_id');
            $table->dropConstrainedForeignId('after_media_id');

        });
    }
};