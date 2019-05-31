<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfoMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info_marks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_rs'); //количество баллов за работу
            $table->integer('five'); //процент на оценку
            $table->integer('four');
            $table->integer('three');
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
        Schema::dropIfExists('info_marks');
    }
}
