<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavouritefoodMealTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favouritefood_meal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('favouritefood_id');
            $table->unsignedBigInteger('meal_id');
            $table->timestamps();

            $table->foreign('favouritefood_id')->references('id')->on('favouritefoods');
            $table->foreign('meal_id')->references('id')->on('meals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favouritefood_meal');
    }
}
