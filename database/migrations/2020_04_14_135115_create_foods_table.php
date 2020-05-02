<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('foods', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('description');
            $table->integer('kcal');
            $table->integer('fat');
            $table->integer('protein');
            $table->integer('carbohydrate');
            $table->integer('potassium');
            $table->unsignedBigInteger('foodgroup_id');
            $table->timestamps();

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
        Schema::dropIfExists('foods');
    }
}
