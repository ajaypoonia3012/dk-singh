<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('transformations', function (Blueprint $table) {

        if (!Schema::hasColumn('transformations', 'image')) {
            $table->string('image')->nullable();
        }

        if (!Schema::hasColumn('transformations', 'review')) {
            $table->longText('review')->nullable();
        }

        if (!Schema::hasColumn('transformations', 'result')) {
            $table->string('result')->nullable();
        }

        if (!Schema::hasColumn('transformations', 'duration')) {
            $table->string('duration')->nullable();
        }

    });
}

    public function down(): void
    {
        //
    }
};