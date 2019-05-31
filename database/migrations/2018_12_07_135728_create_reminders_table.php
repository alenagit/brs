<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_from');
            $table->integer('id_whom');
            $table->string('theme');
            $table->string('short_info')->nullable();
            $table->text('full_info')->nullable();
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->boolean('seen')->nullable();
            $table->boolean('done')->nullable();
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
        Schema::dropIfExists('reminders');
    }
}
