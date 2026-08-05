<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {

            $table->id();

            $table->string('name');

            $table->string('file_name');

            $table->string('disk')->default('public');

            $table->string('folder')->default('media');

            $table->string('path');

            $table->string('mime_type')->nullable();

            $table->unsignedBigInteger('size')->default(0);

            $table->unsignedInteger('width')->nullable();

            $table->unsignedInteger('height')->nullable();

            $table->enum('type', [
                'image',
                'video',
                'document',
                'icon',
            ])->default('image');

            $table->string('alt')->nullable();

            $table->string('title')->nullable();

            $table->text('description')->nullable();

            $table->json('tags')->nullable();

            $table->boolean('featured')->default(false);

            $table->boolean('active')->default(true);

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
