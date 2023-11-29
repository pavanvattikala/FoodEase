<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add category_id column
            $table->foreignId('category_id')->nullable()->constrained('employee_categories');

            // Remove is_admin column (if you want to use category for role)
            $table->dropColumn('is_admin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the added columns in the 'up' method
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');

            // Add back the is_admin column if needed
            $table->boolean('is_admin')->default(false);
        });
    }
};
