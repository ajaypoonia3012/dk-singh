<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hero_settings', function (Blueprint $table) {

            $table->string('followers_label')
                ->default('Followers')
                ->after('enabled');

            $table->string('years_label')
                ->default('Years')
                ->after('followers_label');

            $table->string('transformations_label')
                ->default('Transformations')
                ->after('years_label');

        });
    }

    public function down(): void
    {
        Schema::table('hero_settings', function (Blueprint $table) {

            $table->dropColumn([
                'followers_label',
                'years_label',
                'transformations_label',
            ]);

        });
    }
};
