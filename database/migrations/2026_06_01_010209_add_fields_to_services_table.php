<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {

            $table->longText('features')->nullable()->after('description');

            $table->string('duration')->nullable()->after('price');

            $table->string('button_text')->default('Enroll')->after('duration');

        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {

            $table->dropColumn([
                'features',
                'duration',
                'button_text'
            ]);

        });
    }
};