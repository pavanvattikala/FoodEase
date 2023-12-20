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
        //

        Schema::table('tables', function (Blueprint $table) {
            // Add a new timestamp column named 'taken_at'
            $table->timestamp('taken_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('your_table_name', function (Blueprint $table) {
            // Remove the 'taken_at' column if needed
            $table->dropColumn('taken_at');
        });
    }
};
