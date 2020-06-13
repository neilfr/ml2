<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodFoodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_food', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_food_id');
            $table->unsignedBigInteger('child_food_id');
            $table->timestamps();

            $table->foreign('parent_food_id')->on('foods')->references('id');
            $table->foreign('child_food_id')->on('foods')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('food_food');
    }
}
