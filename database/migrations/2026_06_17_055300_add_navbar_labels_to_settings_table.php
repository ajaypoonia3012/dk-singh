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
        Schema::table('settings', function ($table) {

    $table->string('home_label')->nullable();

    $table->string('about_label')->nullable();

    $table->string('contact_label')->nullable();

    $table->string('plan_label')->nullable();

    $table->string('transformation_label')->nullable();

    $table->string('login_label')->nullable();

    $table->string('register_label')->nullable();

    $table->string('admin_panel_label')->nullable();

    $table->string('my_plan_label')->nullable();

    $table->string('my_orders_label')->nullable();

    $table->string('logout_label')->nullable();

});
    }

    /**
     * Reverse the migrations.
     */
   public function down(): void
{
    Schema::table('settings', function (Blueprint $table) {

        $table->dropColumn([
            'home_label',
            'about_label',
            'contact_label',
            'plan_label',
            'transformation_label',
            'login_label',
            'register_label',
            'admin_panel_label',
            'my_plan_label',
            'my_orders_label',
            'logout_label',
        ]);

    });
}
};
