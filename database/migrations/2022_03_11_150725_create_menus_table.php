.<?php

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
            Schema::create('menus', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('shortcode')->unique();
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                $table->decimal('price', 10, 2);
                $table->string('type')->default('service');
                $table->unsignedInteger('quantity')->nullable()->default(0);

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
            Schema::dropIfExists('menus');
        }
    };
