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
        Schema::table('contact_leads', function (Blueprint $table) {

            if (!Schema::hasColumn('contact_leads', 'status')) {
                $table->string('status')->default('new');
            }

            if (!Schema::hasColumn('contact_leads', 'priority')) {
                $table->string('priority')->default('medium');
            }

            if (!Schema::hasColumn('contact_leads', 'admin_notes')) {
                $table->text('admin_notes')->nullable();
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};