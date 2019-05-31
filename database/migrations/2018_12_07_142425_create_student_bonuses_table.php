<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentBonusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_bonuses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_student');
            $table->integer('id_group');
            $table->integer('id_rs');
            $table->float('value');
            $table->string('theme')->nullable();
            $table->string('comment')->nullable();
            $table->integer('counter')->nullable();
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
        Schema::dropIfExists('student_bonuses');
    }
}
