<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatesBonusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dates_bonuses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('round')->nullable();
            $table->string('date')->nullable();
            $table->string('name')->nullable();
            $table->string('comment')->nullable();          
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
        Schema::dropIfExists('dates_bonuses');
    }
}
