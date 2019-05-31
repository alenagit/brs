<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user')->nullable();
            $table->integer('id_rs')->nullable();
            $table->string('path')->nullable();
            $table->integer('score')->nullable();
            $table->string('date', 12)->nullable();
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
        Schema::dropIfExists('memes');
    }
}
