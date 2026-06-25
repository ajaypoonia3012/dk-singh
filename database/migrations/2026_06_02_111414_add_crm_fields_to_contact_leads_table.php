<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_leads', function (Blueprint $table) {

            if (!Schema::hasColumn('contact_leads', 'status')) {

                $table->string('status')
                    ->default('new')
                    ->after('message');

            }

            if (!Schema::hasColumn('contact_leads', 'notes')) {

                $table->longText('notes')
                    ->nullable()
                    ->after('status');

            }

            if (!Schema::hasColumn('contact_leads', 'source')) {

                $table->string('source')
                    ->nullable()
                    ->after('notes');

            }

            if (!Schema::hasColumn('contact_leads', 'follow_up_date')) {

                $table->date('follow_up_date')
                    ->nullable()
                    ->after('source');

            }

        });
    }

    public function down(): void
    {
        Schema::table('contact_leads', function (Blueprint $table) {

            $columns = [

                'status',
                'notes',
                'source',
                'follow_up_date',

            ];

            foreach ($columns as $column) {

                if (Schema::hasColumn('contact_leads', $column)) {

                    $table->dropColumn($column);

                }

            }

        });
    }
};