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

        Schema::create('restaurants', function (Blueprint $table) {

            $table->id();
            $table->string('name');
            $table->string('tagline');
            $table->text('address');
            $table->string('phone');
            $table->string("GST")->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->boolean('takeout_enabled')->default(true);
            $table->boolean('delivery_enabled')->default(true);

            // Timing and Synchronization
            $table->unsignedInteger('pending_order_sync_time');
            $table->unsignedInteger('waiter_sync_time');
            $table->unsignedInteger('minimum_delivery_time');
            $table->unsignedInteger('minimum_preparation_time');

            // Live Views
            $table->enum('order_live_view', ['asc', 'desc']);
            $table->enum('kot_live_view', ['asc', 'desc']);

            // Payment Options
            $table->json('payment_options');

            // Social Media Links
            $table->json('social_media')->nullable();

            // Tax Configurations
            $table->unsignedDecimal('tax_rate', 5, 2);
            $table->string('currency_symbol');

            // Reservation Configuration
            $table->boolean('reservation_enabled')->default(true);
            $table->unsignedInteger('reservation_advance_notice');

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
        Schema::dropIfExists('restaurants');
    }
};
