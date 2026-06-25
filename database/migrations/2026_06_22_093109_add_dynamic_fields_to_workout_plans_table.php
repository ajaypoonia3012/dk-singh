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
    Schema::table('workout_plans', function (Blueprint $table) {

        $table->string('category')
            ->nullable()
            ->after('difficulty');

        $table->string('required_access')
            ->default('public')
            ->after('category');

        $table->string('video_type')
            ->nullable()
            ->after('required_access');

        $table->text('video_url')
            ->nullable()
            ->after('video_type');

        $table->string('video_file')
            ->nullable()
            ->after('video_url');

    });
}

    /**
     * Reverse the migrations.
     */
   public function down(): void
{
    Schema::table('workout_plans', function (Blueprint $table) {

        $table->dropColumn([
            'category',
            'required_access',
            'video_type',
            'video_url',
            'video_file',
        ]);

    });
}
};
