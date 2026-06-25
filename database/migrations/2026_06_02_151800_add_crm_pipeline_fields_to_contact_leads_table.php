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

 if (!Schema::hasColumn('contact_leads', 'notes')) { 
$table->text('notes')->nullable();
 } 

if (!Schema::hasColumn('contact_leads', 'follow_up_date')) { 
$table->date('follow_up_date')->nullable();
 } 

if (!Schema::hasColumn('contact_leads', 'assigned_to')) { 
$table->string('assigned_to')->nullable();
 } 

if (!Schema::hasColumn('contact_leads', 'source')) { 
$table->string('source')->default('Website');
 }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_leads', function (Blueprint $table) {

            $table->dropColumn([
                'priority',
                'notes',
                'follow_up_date',
                'assigned_to',
                'source',
            ]);

        });
    }
};