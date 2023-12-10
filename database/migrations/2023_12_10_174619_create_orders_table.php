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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('KOT');
            $table->decimal('total', 8, 2);
            $table->unsignedBigInteger('tableNo');
            $table->foreign('tableNo')->references('id')->on('tables')->onDelete('cascade');
            $table->enum('status', ['new', 'processing', 'ready_for_pickup', 'served', 'closed'])->default('new');
            $table->text('special_instructions')->nullable();
            $table->enum('order_type', ['dine_in', 'takeaway'])->default('dine_in');
            $table->unsignedBigInteger('waiter_id');
            $table->foreign('waiter_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
