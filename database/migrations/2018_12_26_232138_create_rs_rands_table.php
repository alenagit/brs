<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRsRandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_rands', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_rs')->nullable();
            $table->string('rand_date')->nullable();
            $table->integer('rand_round')->nullable();
            $table->string('rand_will')->nullable();
            $table->string('rand_was')->nullable();
            $table->string('type')->nullable();
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
        Schema::dropIfExists('rs_rands');
    }
}
