<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\OrderStatus;
use App\Enums\OrderType;

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
            $table->unsignedBigInteger('table_id')->nullable();
            $table->foreign('table_id')->references('id')->on('tables')->onDelete('cascade');
            $table->string('status')->default(OrderStatus::New->value);
            $table->text('special_instructions')->nullable();
            $table->string('order_type')->default(OrderType::DineIn->value);
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
