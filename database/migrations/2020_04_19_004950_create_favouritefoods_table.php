<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavouritefoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favouritefoods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('foodgroup_id');
            $table->string('code')->nullable();
            $table->string('alias')->nullable();
            $table->string('description');
            $table->integer('kcal');
            $table->integer('potassium');
            $table->timestamps();

            $table->foreign('user_id')->on('users')->references('id');
            $table->foreign('foodgroup_id')->on('foodgroups')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favouritefoods');
    }
}
